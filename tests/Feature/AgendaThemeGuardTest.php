<?php

/**
 * TODO-A501 — Guard tema:
 * - tidak ada `<input>`, `<select>`, `<button>` mentah di view agenda;
 * - `<label>` HANYA sebagai wrapper `x-core::checkbox` (pola peer sr-only);
 * - warna hex hanya dari allowlist hero header (#667eea/#764ba2/#f093fb);
 * - tidak ada kelas `indigo-`.
 *
 * TODO-A503 — uji mutasi: sisipkan `<button>` mentah → guard harus RED.
 */

/**
 * Kembalikan daftar pelanggaran tema untuk satu source view.
 */
function collectAgendaThemeViolations(string $source): array
{
    $violations = [];

    foreach (['<input', '<select', '<button'] as $tag) {
        if (str_contains($source, $tag)) {
            $violations[] = "Tag mentah {$tag}";
        }
    }

    preg_match_all('/<label\b[^>]*>(.*?)<\/label>/s', $source, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        if (! str_contains($match[1], '<x-core::checkbox')) {
            $violations[] = '<label> tanpa x-core::checkbox di dalamnya: '.mb_substr(strip_tags($match[1]), 0, 40);
        }
    }

    $openLabels = preg_match_all('/<label\b/', $source);

    if ($openLabels !== count($matches)) {
        $violations[] = '<label> tidak balance (jumlah buka/tutup tidak sama).';
    }

    preg_match_all('/#(?:[0-9a-fA-F]{3}){1,2}\b/', $source, $hexes);

    $allowedHex = ['#667eea', '#764ba2', '#f093fb'];

    foreach ($hexes[0] ?? [] as $hex) {
        if (! in_array(strtolower($hex), array_map('strtolower', $allowedHex), true)) {
            $violations[] = "Hex tidak di-allowlist: {$hex}";
        }
    }

    if (str_contains($source, 'indigo-')) {
        $violations[] = 'Kelas indigo-';
    }

    return $violations;
}

$agendaViews = [
    'index',
    'create-new-agenda',
    'edit-agenda',
    'section/agenda-header',
    'section/agenda-table',
    'section/agenda-row',
];

it('tema core: semua view agenda bebas kontrol mentah (guard A5.1)', function () use ($agendaViews) {
    foreach ($agendaViews as $view) {
        $path = __DIR__.'/../../resources/views/livewire/pages/agenda/'.$view.'.blade.php';

        expect($path)->toBeFile();

        $violations = collectAgendaThemeViolations(file_get_contents($path));

        expect($violations, "{$view} melanggar guard tema")->toBeEmpty();
    }
});

it('tema core: label yang dipakai hanya sebagai wrapper x-core::checkbox', function () {
    foreach (['create-new-agenda', 'edit-agenda'] as $view) {
        $html = file_get_contents(__DIR__.'/../../resources/views/livewire/pages/agenda/'.$view.'.blade.php');

        preg_match_all('/<label\b[^>]*>(.*?)<\/label>/s', $html, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            expect($match[1])->toContain('<x-core::checkbox');
        }
    }
});

it('guard tema RED saat disisipi <button> mentah (mutasi A5.2)', function () {
    $path = __DIR__.'/../../resources/views/livewire/pages/agenda/create-new-agenda.blade.php';
    $source = file_get_contents($path);

    $mutated = str_replace(
        '</x-core::page-container>',
        '<button type="button">Hapus semua</button></x-core::page-container>',
        $source,
    );

    $violations = collectAgendaThemeViolations($mutated);

    expect($violations)->not->toBeEmpty();
    expect(collect($violations)->contains(fn ($v) => str_contains($v, '<button')))->toBeTrue();
});

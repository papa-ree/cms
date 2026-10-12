<?php

/**
 * Translation file for Indonesian language.
 *
 * Mirrors the structure of `rlanvin/php-rrule` i18n files so the human
 * readable recurrence summary can be rendered in Bahasa Indonesia.
 * Loaded by `AgendaRecurrenceService::describe()` via `custom_path`.
 */
return [
    'yearly' => [
        '1' => 'tahunan',
        'else' => 'setiap %{interval} tahun',
    ],
    'monthly' => [
        '1' => 'bulanan',
        'else' => 'setiap %{interval} bulan',
    ],
    'weekly' => [
        '1' => 'mingguan',
        '2' => 'setiap 2 minggu',
        'else' => 'setiap %{interval} minggu',
    ],
    'daily' => [
        '1' => 'harian',
        '2' => 'setiap 2 hari',
        'else' => 'setiap %{interval} hari',
    ],
    'hourly' => [
        '1' => 'setiap jam',
        'else' => 'setiap %{interval} jam',
    ],
    'minutely' => [
        '1' => 'setiap menit',
        'else' => 'setiap %{interval} menit',
    ],
    'secondly' => [
        '1' => 'setiap detik',
        'else' => 'setiap %{interval} detik',
    ],
    'dtstart' => ', mulai dari %{date}',
    'timeofday' => ' pukul %{date}',
    'startingtimeofday' => ' mulai pukul %{date}',
    'infinite' => ', selamanya',
    'until' => ', sampai %{date}',
    'count' => [
        '1' => ', satu kali',
        'else' => ', %{count} kali',
    ],
    'and' => 'dan ',
    'x_of_the_y' => [
        'yearly' => '%{x}',
        'monthly' => '%{x}',
    ],
    'bymonth' => ' pada %{months}',
    'months' => [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ],
    'byweekday' => ' pada %{weekdays}',
    'weekdays' => [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ],
    'nth_weekday' => [
        '1' => '%{weekday} pertama',
        '2' => '%{weekday} kedua',
        '3' => '%{weekday} ketiga',
        'else' => '%{weekday} ke-%{n}',
    ],
    '-nth_weekday' => [
        '-1' => '%{weekday} terakhir',
        '-2' => '%{weekday} kedua dari akhir',
        '-3' => '%{weekday} ketiga dari akhir',
        'else' => '%{weekday} ke-%{n} dari akhir',
    ],
    'byweekno' => [
        '1' => ' pada minggu ke-%{weeks}',
        'else' => ' pada minggu-minggu nomor %{weeks}',
    ],
    'nth_weekno' => '%{n}',
    'bymonthday' => ' pada %{monthdays}',
    'nth_monthday' => [
        '1' => 'tanggal 1',
        '2' => 'tanggal 2',
        '3' => 'tanggal 3',
        '21' => 'tanggal 21',
        '22' => 'tanggal 22',
        '23' => 'tanggal 23',
        '31' => 'tanggal 31',
        'else' => 'tanggal %{n}',
    ],
    '-nth_monthday' => [
        '-1' => 'hari terakhir bulan',
        '-2' => 'hari kedua dari akhir bulan',
        '-3' => 'hari ketiga dari akhir bulan',
        '-21' => 'hari ke-21 dari akhir bulan',
        '-22' => 'hari ke-22 dari akhir bulan',
        '-23' => 'hari ke-23 dari akhir bulan',
        '-31' => 'hari ke-31 dari akhir bulan',
        'else' => 'hari ke-%{n} dari akhir bulan',
    ],
    'byyearday' => [
        '1' => ' pada hari ke-%{yeardays}',
        'else' => ' pada hari-hari ke-%{yeardays}',
    ],
    'nth_yearday' => [
        '1' => 'pertama',
        '2' => 'kedua',
        '3' => 'ketiga',
        'else' => 'ke-%{n}',
    ],
    '-nth_yearday' => [
        '-1' => 'terakhir',
        '-2' => 'kedua dari akhir',
        '-3' => 'ketiga dari akhir',
        'else' => 'ke-%{n} dari akhir',
    ],
    'byhour' => [
        '1' => ' pukul %{hours}',
        'else' => ' pukul %{hours}',
    ],
    'nth_hour' => '%{n}',
    'byminute' => [
        '1' => ' pada menit ke-%{minutes}',
        'else' => ' pada menit-menit ke-%{minutes}',
    ],
    'nth_minute' => '%{n}',
    'bysecond' => [
        '1' => ' pada detik ke-%{seconds}',
        'else' => ' pada detik-detik ke-%{seconds}',
    ],
    'nth_second' => '%{n}',
    'bysetpos' => ', namun hanya %{setpos} dari kumpulan ini',
    'nth_setpos' => [
        '1' => 'yang pertama',
        '2' => 'yang kedua',
        '3' => 'yang ketiga',
        'else' => 'yang ke-%{n}',
    ],
    '-nth_setpos' => [
        '-1' => 'yang terakhir',
        '-2' => 'yang kedua dari akhir',
        '-3' => 'yang ketiga dari akhir',
        'else' => 'yang ke-%{n} dari akhir',
    ],
];

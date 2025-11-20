<div>
    <div class="max-w-2xl py-10 mx-auto">
        <h1 class="mb-6 text-2xl font-semibold">Pilih Bale</h1>

        <div class="grid gap-4">
            @foreach ($bales as $bale)
                <button wire:click="selectBale('{{ $bale->id }}')"
                    class="w-full px-4 py-3 transition bg-white shadow dark:bg-gray-800 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="text-lg font-medium">{{ $bale->name }}</div>
                    <div class="text-sm text-gray-500">{{ $bale->slug }}</div>
                </button>
            @endforeach
        </div>
    </div>

</div>
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-2">
        <a href="{{ route('bale.cms.sections.index') }}"
            class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Sections
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Edit Section: <span class="text-blue-600">{{ $slug }}</span>
        </h1>
    </div>

    {{-- Content --}}
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        @if ($slug == 'hero-section')
            <livewire:cms.pages.section.section.hero-section-form :slug="$slug" />
        @elseif ($slug == 'post-section')
            <livewire:cms.pages.section.section.post-section-form :slug="$slug" />
        @else
            <livewire:cms.pages.section.section.extension-section-form :slug="$slug" />
        @endif
    </div>
</div>
<div>
    <x-core::back-breadcrumb :href="route('bale.cms.sections.index')" label="section list" />

    <div class="mb-6">
        <h1 class="text-xl font-bold dark:text-white">Edit Section: {{ $slug }}</h1>
    </div>

    @if ($slug == 'hero-section')
        <livewire:cms.pages.section.section.hero-section-form :slug="$slug" />
    @elseif ($slug == 'post-section')
        <livewire:cms.pages.section.section.post-section-form :slug="$slug" />
    @else
        <livewire:cms.pages.section.section.extension-section-form :slug="$slug" />
    @endif

</div>
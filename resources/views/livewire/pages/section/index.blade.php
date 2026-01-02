<div>
    <x-core::page-header title="section" subtitle="Manage all your existing section or add a new one">
        <x-slot name="action">
            <x-core::button type="button" label="create section" link :href="route('bale.cms.sections.create')"
                class="justify-center" />
        </x-slot>
    </x-core::page-header>

    <livewire:cms.pages.section.section.section-table />
</div>
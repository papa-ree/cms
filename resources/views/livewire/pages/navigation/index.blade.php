<div>
    <x-bale.page-header title="Navigation" subtitle="Manage all your existing navigation or add a new one">
        <x-slot name="action">
            <x-bale.button type="button" label="create page" link :href="route('bale.cms.navigations.create')"
                class="justify-center" />
        </x-slot>
    </x-bale.page-header>

    <livewire:cms.pages.navigation.section.navigation-sortable />
</div>
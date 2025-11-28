<div>
    <x-core::page-header title="Navigation" subtitle="Manage all your existing navigation or add a new one">
        <x-slot name="action">
            <x-core::button type="button" label="create page" link :href="route('bale.cms.navigations.create', 'new')"
                class="justify-center" />
        </x-slot>
    </x-core::page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        <x-core::page-container class="col-span-1">
            <livewire:cms.pages.navigation.section.navigation-sortable :navItemMode="false" />
        </x-core::page-container>

    </div>

</div>
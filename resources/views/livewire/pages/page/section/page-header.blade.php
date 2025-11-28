<div>
    <x-core::page-header title="Page Management" subtitle="Manage all your existing page or add a new one">
        <x-slot name="action">
            <x-core::button type="button" label="create page" link :href="route('bale.cms.pages.create')"
                class="justify-center" />
        </x-slot>
    </x-core::page-header>
</div>
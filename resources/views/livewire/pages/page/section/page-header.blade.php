<div>
    <x-bale.page-header title="Page Management" subtitle="Manage all your existing page or add a new one">
        <x-slot name="action">
            <x-bale.button type="button" label="create page" link :href="route('bale.cms.pages.create')"
                class="justify-center" />
        </x-slot>
    </x-bale.page-header>
</div>
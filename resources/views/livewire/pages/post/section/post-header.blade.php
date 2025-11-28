<div>
    <x-core::page-header title="Post Management" subtitle="Manage all your existing posts or add a new one">
        <x-slot name="action">
            <x-core::button type="button" label="create post" link :href="route('bale.cms.posts.create')"
                class="justify-center" />
        </x-slot>
    </x-core::page-header>
</div>
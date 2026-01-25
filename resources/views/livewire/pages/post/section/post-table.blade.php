<div>
    <x-core::table :links="$this->availablePosts" header :activeFilters="array_filter(['published' => $filterPublished])">

        <x-slot name="filters">
            <div class="space-y-4">
                <select wire:model.live="filterPublished">
                    <option value="">All Published</option>
                    <option value="published">Published</option>
                    <option value="unpublished">Unpublished</option>
                </select>
            </div>
        </x-slot>

        <x-slot name="thead">
            <tr>
                <x-core::table-th
                    label="Post Title"
                    sortBy="title"
                    :sortField="$sortField"
                    :sortDirection="$sortDirection"
                />
                <x-core::table-th
                    class="hidden lg:table-cell"
                    label="Category"
                    sortBy="category"
                    :sortField="$sortField"
                    :sortDirection="$sortDirection"
                />
                <x-core::table-th
                    class="hidden md:table-cell"
                    label="Author"
                    sortBy="author"
                    :sortField="$sortField"
                    :sortDirection="$sortDirection"
                />
                <x-core::table-th
                    class="hidden sm:table-cell"
                    label="Status"
                    sortBy="published"
                    :sortField="$sortField"
                    :sortDirection="$sortDirection"
                />
                <x-core::table-th
                    label="Action"
                />
            </tr>
        </x-slot>

        <x-slot name="tbody">
            @foreach ($this->availablePosts as $post)
                <tr wire:key='post-{{ $post->slug }}'
                    class="transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                    {{-- Title --}}
                    <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 max-w-0 sm:w-auto sm:max-w-none">
                        <div class="block py-3 pe-6">
                            <div class="flex items-center gap-x-3">

                                @if ($post->thumbnail)
                                    <img class="sm:inline-block hidden size-[38px] rounded-full object-cover" loading="lazy"
                                        src="{{ $post->thumbnail_url }}"
                                        alt="{{ $post->title }}">
                                @else
                                    <div
                                        class="size-[38px] rounded-full bg-gray-200 items-center justify-center hidden sm:flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="text-gray-400 sm:size-6 size-4">
                                            <path fill-rule="evenodd"
                                                d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="grow">
                                    <a href="{{ route('bale.cms.posts.edit', $post->slug) }}"
                                        class="block text-sm text-gray-800 transition ease-in-out dark:text-gray-200 hover:text-emerald-600 dark:hover:text-emerald-400">
                                        {{ Illuminate\Support\Str::of($post->title)->words(5, '...') }}
                                    </a>
                                    <div class="block text-sm text-gray-500">
                                        {{-- @foreach ($post->unquotationString($post->tags->pluck('name')) as $tag)
                                        <span
                                            class="px-2 py-0.5 text-xs bg-slate-100 text-slate-800 rounded-full dark:bg-slate-500/10 dark:text-slate-500">{{
                                            $tag }}</span>
                                        @endforeach --}}
                                    </div>

                                    <dl class="font-normal lg:hidden">
                                        <dt class="sr-only">Post Slug</dt>
                                        <dd class="text-gray-700 truncate">
                                            <span class="block text-xs text-gray-600 dark:text-gray-200">by
                                                {{ $post->author->name ?? 'unknown' }}</span>
                                            <span class="block text-xs text-gray-500">created at
                                                {{ $post->created_at }}</span>
                                        </dd>
                                        <dt class="sr-only sm:hidden">Status</dt>
                                        <dd class="text-gray-500 truncate sm:hidden">
                                            <span class="block text-xs text-gray-500">
                                                @if ($post->published)
                                                    <button wire:click="unpublishPost('{{ $post->id }}')"
                                                        class="py-1 px-1.5 inline-flex items-center cursor-pointer gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Published
                                                    </button>
                                                    <span class="block text-xs text-gray-500">published at
                                                        {{ $post->published_at }}</span>
                                                @else
                                                    <button wire:click="publishPost('{{ $post->id }}')"
                                                        class="py-1 px-1.5 inline-flex items-center cursor-pointer gap-x-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full dark:bg-slate-500/10 dark:text-slate-500">
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                        </svg>
                                                        Unpublished
                                                    </button>
                                                @endif
                                            </span>
                                        </dd>
                                    </dl>
                                </div>

                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        <span class="block text-sm text-gray-800 dark:text-gray-200">{{ $post->category_slug }}</span>
                    </td>

                    {{-- Author, Created At --}}
                    <td class="hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                        <span
                            class="block text-sm text-gray-800 dark:text-gray-200">{{ $post->author->name ?? 'unknown' }}</span>
                        <span class="block text-xs text-gray-500">created at
                            {{ $post->created_at }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                        @if ($post->published)
                            <button wire:click="unpublishPost('{{ $post->id }}')"
                                class="py-1 px-1.5 inline-flex items-center cursor-pointer gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                Published
                            </button>
                            <span class="block text-xs text-gray-500">published at
                                {{ $post->published_at }}</span>
                        @else
                            <button wire:click="publishPost('{{ $post->id }}')"
                                class="py-1 px-1.5 inline-flex items-center cursor-pointer gap-x-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full dark:bg-slate-500/10 dark:text-slate-500">
                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                Unpublished
                            </button>
                        @endif
                    </td>

                    <td class="size-px whitespace-nowrap">
                        <div class="px-6 py-1.5">
                            <livewire:core.shared-components.item-actions
                                :editUrl="route('bale.cms.posts.edit', $post->slug)"
                                :deleteId="$post->id"
                                wire:key="item-actions-{{ $post->id }}"
                                confirmMessage="Yakin ingin menghapus data ini?">
                            </livewire:core.shared-components.item-actions>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-slot>

    </x-core::table>
</div>
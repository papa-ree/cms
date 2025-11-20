<div>
    <x-bale.table :links="$this->availablePosts" header>

        <x-slot name="thead">
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Post Title
                        </span>
                    </div>
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Category
                        </span>
                    </div>
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Author
                        </span>
                    </div>
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Status
                        </span>
                    </div>
                </th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </x-slot>

        <x-slot name="tbody">
            {{-- @dump(session()->all()) --}}
            @foreach ($this->availablePosts as $post)
                <tr wire:key='post-{{ $post->slug }}'
                    class="transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                    {{-- Title --}}
                    <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 max-w-0 sm:w-auto sm:max-w-none">
                        <div class="block py-3 pe-6">
                            <div class="flex items-center gap-x-3">

                                @if ($post->thumbnail)
                                    <img class="sm:inline-block hidden size-[38px] rounded-full object-cover" loading="lazy"
                                        src="{{ route('bale.cms.media', ['path' => session('bale_active_slug') . '//thumbnails/' . $post->thumbnail]) ?? null }}"
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
                                            class="px-2 py-[2px] text-xs bg-slate-100 text-slate-800 rounded-full dark:bg-slate-500/10 dark:text-slate-500">{{
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
                                                    <span
                                                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                        </svg>
                                                        Published
                                                    </span>
                                                    <span class="block text-xs text-gray-500">published at
                                                        {{ $post->publish_at }}</span>
                                                @else
                                                    <span
                                                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full dark:bg-slate-500/10 dark:text-slate-500">
                                                        <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path
                                                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                        </svg>
                                                        Unpublished
                                                    </span>
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
                            <span
                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                                Published
                            </span>
                            <span class="block text-xs text-gray-500">published at
                                {{ $post->publish_at }}</span>
                        @else
                            <span
                                class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-slate-100 text-slate-800 rounded-full dark:bg-slate-500/10 dark:text-slate-500">
                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                Unpublished
                            </span>
                        @endif
                    </td>

                    <td class="size-px whitespace-nowrap">
                        <div class="px-6 py-1.5">
                            <div class="hs-dropdown relative inline-block [--placement:bottom|left]">
                                <button id="hs-table-dropdown-{{ $post->id }}" type="button"
                                    class="hs-dropdown-toggle py-1.5 px-2 inline-flex justify-center items-center gap-2 rounded-lg text-gray-700 align-middle disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-300 transition-all text-sm dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                    </svg>
                                </button>
                                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-40 z-10 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700"
                                    aria-labelledby="hs-table-dropdown-{{ $post->id }}">
                                    <div class="py-2 first:pt-0 last:pb-0">
                                        @if ($post->published)
                                            <button wire:click="unpublishPost('{{ $post->id }}')"
                                                class="flex items-center w-full px-3 py-2 text-sm text-gray-800 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
                                                Unpublish
                                            </button>
                                        @else
                                            <button wire:click="publishPost('{{ $post->id }}')"
                                                class="flex items-center w-full px-3 py-2 text-sm text-gray-800 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
                                                Publish
                                            </button>
                                        @endif
                                        <a wire:navigate.hover
                                            class="flex items-center px-3 py-2 text-sm text-gray-800 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300"
                                            href="{{ route('bale.cms.posts.edit', $post->id) }}">
                                            Edit
                                        </a>
                                    </div>
                                    <div class="py-2 first:pt-0 last:pb-0">
                                        <button {{--
                                            wire:click="$dispatch('openModal', { component: 'pages.post.modal.post-delete-confirmation-modal', arguments: { post: '{{ $post->id }}' } })"
                                            --}}
                                            class="flex items-center w-full px-3 py-2 text-sm text-red-600 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-red-500 dark:hover:bg-neutral-700">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-slot>

    </x-bale.table>
</div>
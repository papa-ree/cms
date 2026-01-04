<div>
    @if(count($this->availableSections) > 0)
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($this->availableSections as $section)
                <div class="group p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                    wire:key='section-{{ $section->slug }}' data-aos="fade-up">

                    <div class="flex items-start justify-between mb-4">
                        {{-- Type Icon with Gradient --}}
                        <div class="p-3 rounded-xl shadow-md {{ 
                                    $section->type === 'hero' ? 'bg-linear-to-br from-indigo-500 to-indigo-600' :
                    ($section->type === 'post' ? 'bg-linear-to-br from-emerald-500 to-emerald-600' :
                        'bg-linear-to-br from-purple-500 to-purple-600') 
                                }}">
                            @if($section->type === 'hero')
                                <x-lucide-sparkles class="w-6 h-6 text-white" />
                            @elseif($section->type === 'post')
                                <x-lucide-newspaper class="w-6 h-6 text-white" />
                            @else
                                <x-lucide-box class="w-6 h-6 text-white" />
                            @endif
                        </div>

                        {{-- Type Badge --}}
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{
                    $section->type === 'hero' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' :
                    ($section->type === 'post' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' :
                        'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300')
                                }}">
                            {{ ucfirst($section->type) }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <a href="{{ route($section->usage == 'searchable' ? 'bale.cms.sections.edit-searchable' : 'bale.cms.sections.edit', $section->slug) }}"
                            wire:navigate.hover
                            class="block text-xl font-bold text-gray-900 dark:text-white hover:text-purple-600 dark:hover:text-purple-400 transition-colors mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400">
                            {{ $section->name }}
                        </a>

                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <x-lucide-tag class="w-4 h-4" />
                            <span class="font-mono">{{ $section->usage }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <x-lucide-calendar class="w-4 h-4" />
                            <span>{{ $section->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route($section->usage == 'searchable' ? 'bale.cms.sections.edit-searchable' : 'bale.cms.sections.edit', $section->slug) }}"
                                wire:navigate.hover
                                class="p-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-lg transition-all">
                                <x-lucide-edit class="w-4 h-4" />
                            </a>
                            @if($section->type != 'core')
                                <livewire:core.shared-components.item-actions
                                    :deleteId="$section->id"
                                    confirmMessage="Yakin ingin menghapus section ini?">
                                </livewire:core.shared-components.item-actions>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl">
            <div
                class="w-20 h-20 mx-auto mb-6 rounded-full bg-linear-to-br from-purple-100 to-purple-200 dark:from-purple-900/20 dark:to-purple-800/20 flex items-center justify-center">
                <x-lucide-layout class="w-10 h-10 text-purple-600 dark:text-purple-400" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Sections Found</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Get started by creating your first section.</p>
            <a href="{{ route('bale.cms.sections.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                <x-lucide-plus class="w-5 h-5" />
                Create Section
            </a>
        </div>
    @endif
</div>
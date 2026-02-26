<div>
    {{-- Hero Section --}}
    <div class="relative overflow-hidden p-8 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);" data-aos="fade-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-6 md:mb-0">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                        <x-lucide-file-text class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white md:text-4xl">{{ __('Post Management') }}</h1>
                    </div>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">
                    {{ __('Manage all your existing posts or create new engaging content for your audience.') }}
                </p>
            </div>
            <div class="shrink-0">
                <x-core::button type="button" label="{{ __('Create Post') }}" link
                    :href="route('bale.cms.posts.create')" class="gap-x-2 bg-white text-purple-600 hover:bg-white/90">
                    <x-slot name="icon">
                        <x-lucide-plus class="w-5 h-5" />
                    </x-slot>
                </x-core::button>
            </div>
        </div>
    </div>
</div>
<!-- Card Blog -->
<div class="max-w-240 px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Title -->
    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">Select Section Usage</h2>
        <p class="mt-1 text-gray-600 dark:text-gray-400">
            Select section specific usage for Bale landing page
        </p>
    </div>
    <!-- End Title -->

    <!-- Grid -->
    <div class="grid sm:grid-cols-2 gap-6">
        <!-- Card -->
        <a class="group flex flex-col h-full border border-gray-200 hover:border-transparent hover:shadow-lg focus:outline-hidden focus:border-transparent focus:shadow-lg transition duration-300 rounded-xl p-5 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-transparent dark:hover:shadow-black/40 dark:focus:border-transparent dark:focus:shadow-black/40"
            href="{{ route('bale.cms.sections.create') }}">
            <div class="aspect-w-16 aspect-h-11">
                <img class="w-full h-87.5 object-cover rounded-xl"
                    src="https://images.unsplash.com/photo-1669828230990-9b8583a877ab?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80"
                    alt="Blog Image">
            </div>
            <div class="my-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-300 dark:group-hover:text-white">
                    General Section
                </h3>
                <p class="mt-5 text-gray-600 dark:text-gray-400">
                    General Section for static layout. Hero, Post, Contact, Footer.
                </p>
            </div>
        </a>
        <!-- End Card -->

        <!-- Card -->
        <a class="group flex flex-col h-full border border-gray-200 hover:border-transparent hover:shadow-lg focus:outline-hidden focus:border-transparent focus:shadow-lg transition duration-300 rounded-xl p-5 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-transparent dark:hover:shadow-black/40 dark:focus:border-transparent dark:focus:shadow-black/40"
            href=" {{ route('bale.cms.sections.create-searchable') }}">
            <div class="aspect-w-16 aspect-h-11">
                <img class="w-full h-87.5 object-cover rounded-xl"
                    src="https://images.unsplash.com/photo-1669824774762-65ddf29bee56?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80"
                    alt="Blog Image">
            </div>
            <div class="my-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-300 dark:group-hover:text-white">
                    Searchable Section
                </h3>
                <p class="mt-5 text-gray-600 dark:text-gray-400">
                    Searchable Section for searching item. Portal, Application.
                </p>
            </div>
        </a>
        <!-- End Card -->

    </div>
    <!-- End Grid -->


</div>
<!-- End Card Blog -->
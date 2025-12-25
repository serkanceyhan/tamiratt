<header class="sticky top-0 z-50 w-full bg-background-light/90 dark:bg-background-dark/90 backdrop-blur-md border-b border-[#e7ebf3] dark:border-[#2a303c]">
    <div class="max-w-[1280px] mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2 group cursor-pointer">
            <img alt="Flux360 Logo" class="h-10 w-auto object-contain" src="https://www.tamiratt.com/assets/images/logo.png"/>
        </div>
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary transition-colors" href="#services">{{ T('nav.services') }}</a>
            <a class="text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary transition-colors" href="#process">{{ T('nav.how_it_works') }}</a>
            <a class="text-sm font-medium text-gray-600 hover:text-primary dark:text-gray-300 dark:hover:text-primary transition-colors" href="#sustainability">{{ T('nav.sustainability') }}</a>
        </nav>
        <div class="flex items-center gap-4">
            <button @click="quoteModalOpen = true" class="bg-primary hover:bg-blue-700 text-white text-sm font-bold py-2.5 px-6 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                {{ T('btn.get_quote') }}
            </button>
        </div>
    </div>
</header>

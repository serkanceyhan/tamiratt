<x-landing-layout>
    <x-slot:head>
        <title>404 - Sayfa Bulunamadı | ta'miratt</title>
        <meta name="description" content="Aradığınız sayfa bulunamadı. Tamir hizmetlerimize ulaşmak için ana sayfamıza dönebilirsiniz.">
        <meta name="robots" content="noindex, follow">
    </x-slot:head>

    <x-landing.header />

    <main class="flex-grow flex items-center justify-center py-20 px-6">
        <div class="text-center max-w-xl">
            {{-- 404 Illustration --}}
            <div class="relative mb-8">
                <div class="text-[180px] font-black text-primary/10 leading-none">404</div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="material-symbols-outlined text-8xl text-primary">search_off</span>
                </div>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Sayfa Bulunamadı
            </h1>
            
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir. 
                Endişelenmeyin, sizi doğru yöne yönlendirelim.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/" class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span class="material-symbols-outlined">home</span>
                    Ana Sayfaya Dön
                </a>
                <a href="{{ route('services') }}" class="inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold py-3 px-8 rounded-xl transition-all">
                    <span class="material-symbols-outlined">handyman</span>
                    Hizmetlerimiz
                </a>
            </div>

            {{-- Quick Links --}}
            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Belki bunlar işinize yarar:</p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <a href="{{ route('provider.apply') }}" class="text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">store</span>
                        Hizmet Veren Ol
                    </a>
                    <a href="/giris" class="text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">login</span>
                        Giriş Yap
                    </a>
                    <a href="/#process" class="text-primary hover:underline flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">help</span>
                        Nasıl Çalışır?
                    </a>
                </div>
            </div>
        </div>
    </main>

    <x-landing.footer />
</x-landing-layout>

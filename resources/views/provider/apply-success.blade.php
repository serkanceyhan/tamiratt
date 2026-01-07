<x-landing-layout>
    <x-slot:head>
        <title>Başvurunuz Alındı | ta'miratt</title>
    </x-slot:head>

    <x-landing.header />

    <main class="flex-grow py-20 bg-gray-50 dark:bg-background-dark">
        <div class="max-w-2xl mx-auto px-6 text-center">
            <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl p-12 overflow-hidden relative">
                {{-- Decorative Background Elements --}}
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-green-400 to-emerald-600"></div>
                
                <div class="w-24 h-24 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce-short">
                    <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-6xl">check_circle</span>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 tracking-tight">
                    Başvurunuz Başarıyla Alındı!
                </h1>
                
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-10 leading-relaxed max-w-lg mx-auto">
                    Ekibimiz başvurunuzu en kısa sürede inceleyecek ve size e-posta ile dönüş yapacaktır.
                </p>

                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-8 mb-10 text-left border border-gray-100 dark:border-gray-700">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">timeline</span>
                        Sonraki Adımlar
                    </h4>
                    
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm shrink-0">1</div>
                            <div>
                                <h5 class="font-medium text-gray-900 dark:text-white">İnceleme Süreci</h5>
                                <p class="text-sm text-gray-500 mt-0.5">Başvurunuz uzman ekibimiz tarafından 1-2 iş günü içinde incelenir.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-sm shrink-0">2</div>
                            <div>
                                <h5 class="font-medium text-gray-900 dark:text-white">Aktivasyon</h5>
                                <p class="text-sm text-gray-500 mt-0.5">Onaylanmanız durumunda e-posta adresinize şifre oluşturma linki gönderilir.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center font-bold text-sm shrink-0">3</div>
                            <div>
                                <h5 class="font-medium text-gray-900 dark:text-white">İş Fırsatları</h5>
                                <p class="text-sm text-gray-500 mt-0.5">Hesabınıza giriş yaparak bölgenizdeki iş fırsatlarını görüntüleyebilir ve teklif verebilirsiniz.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl hover:bg-blue-700 transition-colors font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                    <span class="material-symbols-outlined">home</span>
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </main>

    <x-landing.footer />
</x-landing-layout>

<x-landing-layout>
    <x-slot:head>
        <title>Başvurunuz Alındı | ta'miratt</title>
    </x-slot:head>

    <x-landing.header />

    <main class="flex-grow py-20 bg-gray-50 dark:bg-background-dark">
        <div class="max-w-xl mx-auto px-6 text-center">
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-xl p-10">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-green-600 text-4xl">check_circle</span>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    Başvurunuz Başarıyla Alındı!
                </h1>
                
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    Ekibimiz başvurunuzu en kısa sürede inceleyecek ve size e-posta ile dönüş yapacaktır.
                    Onay sürecinden sonra hesabınızı aktive edebilir ve iş fırsatlarına erişebilirsiniz.
                </p>

                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-8">
                    <div class="flex items-start gap-3 text-left">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">Sonraki Adımlar</h4>
                            <ol class="text-sm text-gray-600 dark:text-gray-400 space-y-1 list-decimal list-inside">
                                <li>Başvurunuz incelenir (1-2 iş günü)</li>
                                <li>Onay sonrası şifre oluşturma linki gönderilir</li>
                                <li>Hesabınızı aktive ederek iş fırsatlarına erişirsiniz</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary hover:text-blue-700 font-medium">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </main>

    <x-landing.footer />
</x-landing-layout>

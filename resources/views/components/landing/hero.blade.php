<section class="relative pt-12 pb-20 lg:pt-24 lg:pb-32 bg-surface-light dark:bg-background-dark overflow-hidden">
    <div class="max-w-[1280px] mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            <div class="flex-1 flex flex-col gap-8 text-center lg:text-left z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 w-fit mx-auto lg:mx-0">
                    <span class="material-symbols-outlined text-primary text-sm">eco</span>
                    <span class="text-xs font-semibold text-primary uppercase tracking-wide">Sürdürülebilir Ofis Çözümleri</span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black leading-tight tracking-tight text-gray-900 dark:text-white">
                    ömür katar <br/>
                    <span class="text-primary relative inline-block">
                        bütçe artar
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-yellow-300 -z-10 opacity-60" preserveAspectRatio="none" viewBox="0 0 100 10">
                            <path d="M0 5 Q 50 10 100 5" fill="none" stroke="currentColor" stroke-width="8"></path>
                        </svg>
                    </span>
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Türkiye'nin lider ofis mobilyası tamirat pazaryeri. Eski mobilyalarınızı fabrikadan çıkmış gibi yeniliyoruz.
                    <span class="font-bold text-gray-900 dark:text-white">%40 tasarruf edin</span>, bütçenizi ve gezegeni koruyun.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <button @click="quoteModalOpen = true" class="flex items-center justify-center gap-2 h-14 px-8 bg-primary hover:bg-blue-700 text-white text-base font-bold rounded-lg shadow-lg hover:shadow-primary/50 transition-all transform hover:-translate-y-1">
                        <span>Hemen Teklif Al</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                    <button class="flex items-center justify-center gap-2 h-14 px-8 bg-white dark:bg-surface-dark border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-base font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                        <span class="material-symbols-outlined text-lg">play_circle</span>
                        <span>Süreci İzle</span>
                    </button>
                </div>
            <div class="flex flex-wrap justify-center gap-6 mt-12">
                <div class="flex items-center gap-3 px-5 py-3 bg-white dark:bg-surface-dark rounded-full shadow-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600 dark:text-green-400 text-xl">eco</span>
                    </div>
                    <div class="text-left">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Karbon Ayak İzi</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Azaltım Garantili</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-5 py-3 bg-white dark:bg-surface-dark rounded-full shadow-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-xl">local_shipping</span>
                    </div>
                    <div class="text-left">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">Ücretsiz Nakliye</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Hizmet verilen bölgelere</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-5 py-3 bg-white dark:bg-surface-dark rounded-full shadow-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl">verified_user</span>
                    </div>
                    <div class="text-left">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">2 Yıl Garanti</span>
                        <p class="text-xs text-gray-600 dark:text-gray-400">İşçilik ve Malzeme</p>
                    </div>
                </div>
            </div>
            </div>
            <div class="flex-1 w-full relative group perspective-1000">
                <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl border-4 border-white dark:border-gray-800 transform rotate-1 transition-transform duration-500 hover:rotate-0">
                    <div class="absolute inset-0 flex">
                        <div class="w-1/2 h-full bg-gray-200 relative overflow-hidden border-r-2 border-white z-10">
                            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" data-alt="Ofis mobilyası tamir öncesi" style='background-image: url("/storage/2/anasayfa-ust-before.jpg");'></div>
                            <div class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">ÖNCESİ</div>
                            <div class="absolute inset-0 bg-black/10"></div>
                        </div>
                        <div class="w-1/2 h-full bg-gray-100 relative overflow-hidden">
                            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" data-alt="Ofis mobilyası tamir sonrası" style='background-image: url("/storage/2/anasayfa-ust-after.jpg");'></div>
                            <div class="absolute top-4 right-4 bg-secondary text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">SONRASI</div>
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-6 lg:bottom-6 left-1/2 transform -translate-x-1/2 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-5 py-3 rounded-xl shadow-lg flex items-center gap-3 z-20 w-max">
                    <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-300" data-alt="user avatar 1" style="background-image: url('{{ asset('assets/images/badges/avatar-1.jpg') }}'); background-size: cover;"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-300" data-alt="user avatar 2" style="background-image: url('{{ asset('assets/images/badges/avatar-2.jpg') }}'); background-size: cover;"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-300" data-alt="user avatar 3" style="background-image: url('{{ asset('assets/images/badges/avatar-3.jpg') }}'); background-size: cover;"></div>
                        </div>
                        <div class="text-xs">
                            <p class="font-bold text-gray-900 dark:text-white">500+ Mutlu Şirket</p>
                            <p class="text-gray-500">Ofisini yeniledi</p>
                        </div>
                    </div>
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-400/20 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-green-400/20 rounded-full blur-3xl -z-10"></div>
            </div>
        </div>
    </div>
</section>

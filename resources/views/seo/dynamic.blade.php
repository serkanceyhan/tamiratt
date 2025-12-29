<x-landing-layout>
    {{-- SEO meta tags --}}
    <x-slot:head>
        <title>{{ $metaTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        <link rel="canonical" href="{{ $canonicalUrl }}">
        
        <!-- Open Graph -->
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:type" content="website">
        
        <!-- Schema.org LocalBusiness -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": "Tamiratt - {{ $service->name }}",
            "description": "{{ $metaDescription }}",
            "areaServed": {
                "@type": "City",
                "name": "{{ $location->name }}"
            },
            "serviceType": "{{ $service->name }}"
        }
        </script>

        <style>
            .before-after-container {
                position: relative;
                overflow: hidden;
                user-select: none;
            }
            .before-after-slider {
                -webkit-appearance: none;
                appearance: none;
                width: 100%;
                height: 100%;
                position: absolute;
                top: 0;
                left: 0;
                background: transparent;
                z-index: 20;
                cursor: ew-resize;
                margin: 0;
                outline: none;
            }
            .before-after-slider::-webkit-slider-thumb {
                -webkit-appearance: none;
                appearance: none;
                width: 4px;
                height: 100vh;
                background: white;
                cursor: ew-resize;
                box-shadow: 0 0 10px rgba(0,0,0,0.5);
            }
            .before-after-slider::-moz-range-thumb {
                width: 4px;
                height: 100vh;
                background: white;
                cursor: ew-resize;
                box-shadow: 0 0 10px rgba(0,0,0,0.5);
                border: none;
            }
            .slider-handle {
                pointer-events: none;
                position: absolute;
                top: 50%;
                z-index: 30;
                transform: translate(-50%, -50%);
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background: white;
                border-radius: 50%;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                color: #2463eb;
            }
        </style>
    </x-slot:head>

    <x-landing.header />
    
    <main class="flex-grow">
        {{-- Hero Section --}}
        <section class="bg-gray-50 dark:bg-background-dark py-8">
            <div class="max-w-[1280px] mx-auto px-6">
                {{-- Breadcrumb - Aligned with logo --}}
                <nav class="mb-6">
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 font-medium">
                        @foreach($breadcrumb as $index => $item)
                            @if($loop->last)
                                <span class="text-gray-900 dark:text-white font-semibold">{{ $item['name'] }}</span>
                            @else
                                @if($item['url'])
                                    <a href="{{ $item['url'] }}" class="hover:text-primary transition-colors">{{ $item['name'] }}</a>
                                @else
                                    <span>{{ $item['name'] }}</span>
                                @endif
                                <span class="mx-2 text-gray-400">&gt;</span>
                            @endif
                        @endforeach
                    </div>
                </nav>

                {{-- Hero Content --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-8">
                    {{-- Left Column - Content --}}
                    <div>
                        {{-- Orange Badge --}}
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-white shadow-md mb-6" style="background-color: #f97316;">
                            <span class="material-symbols-outlined text-sm">local_shipping</span>
                            <span class="text-xs font-bold uppercase tracking-wide">{{ $location->name }} Bölgesine Ücretsiz Teslimat</span>
                        </div>

                        {{-- Main Title --}}
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 dark:text-white mb-6 leading-tight">
                            {{ $location->name }}'de <span style="color: #2463eb;">{{ $service->name }}</span>
                        </h1>

                        {{-- Description --}}
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                            Ofis mobilyalarınızı yenileyerek bütçenizden <span class="text-orange-600 font-bold">%60 tasarruf edin</span>. {{ $location->name }} bölgesindeki ofisinizden ücretsiz alıyor, yenileyip teslim ediyoruz.
                        </p>

                        {{-- CTA Button --}}
                        <button @click="quoteModalOpen = true" class="inline-flex items-center justify-center gap-2 px-8 py-4 text-white text-lg font-bold rounded-lg shadow-xl transition-all transform hover:-translate-y-1" style="background-color: #2463eb;">
                            <span>Hemen Teklif Al</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>

                        {{-- Trust Badges --}}
                        <div class="flex flex-wrap items-center gap-x-8 gap-y-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span>🛡️</span>
                                <span>40+ Yıl Tecrübe</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span>🏢</span>
                                <span>500+ Kurumsal Referans</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span>🪑</span>
                                <span>5000+ Mutlu Müşteri</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column - Image --}}
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl aspect-[4/3]">
                        <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800" 
                             alt="Modern Office Interior" 
                             class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </section>

        {{-- Why Choose Section --}}
        <section class="py-20 bg-white dark:bg-background-dark">
            <div class="max-w-[1100px] mx-auto px-6">
                <div class="flex flex-col lg:flex-row gap-12 items-center mb-16">
                    <div class="w-full lg:w-1/2">
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-blue-900 dark:text-white leading-tight mb-6">
                            Neden Yeni Koltuk Alıp <br/>
                            <span class="text-orange-600 underline decoration-4 decoration-orange-200 dark:decoration-orange-900/50">10.000₺ Harcayasınız?</span>
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                            Markalı bir ofis koltuğunun iskelet ve mekanizma ömrü 10-15 yıldır. Kumaşı yıprandığında çöpe atmak, sağlam bir arabayı lastiği patladı diye hurdaya ayırmak gibidir.
                        </p>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                            Tamirat ile mobilyanızı <strong class="text-blue-900 dark:text-blue-200">fabrika çıkış kalitesine</strong> döndürüyoruz. Üstelik yeni alacağınız orta kalite bir koltuktan daha dayanıklı, ergonomik ve %60 daha ekonomik.
                        </p>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-100 dark:border-blue-800">
                            <div class="flex items-start gap-4">
                                <span class="material-symbols-outlined text-blue-600 text-3xl">business_center</span>
                                <div>
                                    <h4 class="font-bold text-blue-900 dark:text-white mb-1">{{ $location->name }} Bölgesi Kurumsal Referanslarımız</h4>
                                    <p class="text-sm text-blue-800 dark:text-blue-200">Bölgenizdeki banka genel müdürlükleri, hukuk büroları ve teknoloji şirketlerinin mobilyalarını biz yeniliyoruz.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2 grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-surface-dark p-6 rounded-xl text-center border border-gray-100 dark:border-gray-700">
                            <span class="block text-4xl font-black text-gray-300 dark:text-gray-600 mb-2 line-through">10.000₺</span>
                            <span class="block text-sm text-gray-500 font-medium">Yeni Koltuk Maliyeti</span>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-xl text-center border border-green-100 dark:border-green-800 relative overflow-hidden">
                            <div class="absolute top-0 right-0 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-bl-lg">TASARRUF</div>
                            <span class="block text-4xl font-black text-green-600 dark:text-green-400 mb-2">3.500₺</span>
                            <span class="block text-sm text-green-700 dark:text-green-300 font-bold">Tamirat Maliyeti (Ort.)</span>
                        </div>
                        <div class="col-span-2 bg-surface-light dark:bg-surface-dark p-6 rounded-xl border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-orange-600">handyman</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Orjinal Yedek Parça</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Marka ve modele özel orjinal sünger ve mekanizma değişimi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Before/After Slider --}}
                <div class="mt-16 text-center">
                    <h3 class="text-2xl md:text-3xl font-bold text-blue-900 dark:text-white mb-8">Yenilenen Mobilyalarınız İlk Günkü Gibi Olur</h3>
                    <div class="max-w-4xl mx-auto before-after-container rounded-2xl shadow-2xl overflow-hidden aspect-[4/3] relative group">
                        <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=1200');">
                            <div class="absolute top-4 right-4 bg-white/80 dark:bg-black/60 backdrop-blur px-3 py-1 rounded text-xs font-bold uppercase tracking-wider text-green-700 dark:text-green-400">
                                Yenilenmiş (After)
                            </div>
                        </div>
                        <div class="absolute inset-0 w-full h-full bg-cover bg-center" id="before-image" style="background-image: url('https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=1200'); width: 50%; border-right: 2px solid white;">
                            <div class="absolute top-4 left-4 bg-white/80 dark:bg-black/60 backdrop-blur px-3 py-1 rounded text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Öncesi (Before)
                            </div>
                        </div>
                        <input type="range" min="0" max="100" value="50" class="before-after-slider" id="slider" 
                            oninput="document.getElementById('before-image').style.width = this.value + '%'; document.getElementById('slider-handle').style.left = this.value + '%';" />
                        <div class="slider-handle" id="slider-handle" style="left: 50%;">
                            <span class="material-symbols-outlined text-xl">code</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-4 italic">Farkı görmek için oku sağa-sola sürükleyin</p>
                </div>
            </div>
        </section>

        {{-- How It Works --}}
        <section class="py-20 bg-surface-light dark:bg-surface-dark border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-[1280px] mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-blue-900 dark:text-white mb-4">Nasıl Çalışır?</h2>
                    <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">"Sürtünmeyi Azalt" prensibiyle geliştirdiğimiz 3 adımlı basit iş akışı ile ofisinizden çıkmadan mobilyalarınızı yeniliyoruz.</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <div class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-gray-200 dark:bg-gray-700 z-0"></div>
                    
                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full shadow-lg border-4 border-white dark:border-gray-700 flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-primary text-5xl font-light">add_a_photo</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">1. Fotoğraf Gönder</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 px-4">
                            WhatsApp veya form üzerinden mobilyalarınızın fotoğrafını gönderin, 30 dk içinde net fiyat teklifinizi alın.
                        </p>
                    </div>

                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full shadow-lg border-4 border-white dark:border-gray-700 flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-primary text-5xl font-light">home_repair_service</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">2. Yerinde veya Atölyede Tamir</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 px-4">
                            İster ofisinizde yerinde tamir edelim, isterseniz atölyemize alıp yenileyip getirelim.
                        </p>
                    </div>

                    <div class="relative z-10 flex flex-col items-center text-center">
                        <div class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full shadow-lg border-4 border-white dark:border-gray-700 flex items-center justify-center mb-6">
                            <span class="material-symbols-outlined text-primary text-5xl font-light">chair</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">3. Yenilenmiş Olarak Teslim</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 px-4">
                            Tüm işlemler tamamlandığında, ambalajlı ve yenilenmiş mobilyalarınızı ofisinize geri getirip kuralım.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Trust Logos --}}
        <section class="py-16 bg-white dark:bg-background-dark border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-[1280px] mx-auto px-6 text-center">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-10">{{ $location->name }} Bölgesinde Bizi Tercih Eden Kurumlar</h3>
                <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
                    @for($i = 1; $i <= 5; $i++)
                        <div class="px-8 py-3 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kurum {{ $i }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        {{-- Location CTA --}}
        <section class="py-20 bg-white dark:bg-background-dark border-t border-gray-100 dark:border-gray-800">
            <div class="max-w-[1280px] mx-auto px-6">
                <div class="rounded-3xl overflow-hidden shadow-2xl relative" style="background-color: #1e3a8a;">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-800 rounded-full -translate-y-1/2 translate-x-1/2 opacity-50"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-950 rounded-full translate-y-1/2 -translate-x-1/2 opacity-50"></div>
                    
                    <div class="relative z-10 p-8 lg:p-16 flex flex-col lg:flex-row items-center gap-12">
                        <div class="flex-1 text-center lg:text-left">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-800 border border-blue-700 w-fit mb-6">
                                <span class="material-symbols-outlined text-white text-sm">location_on</span>
                                <span class="text-xs font-semibold text-white uppercase tracking-wide">{{ $location->name }} ve Çevresi</span>
                            </div>
                            <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">{{ $location->name }} Bölgesi Mobil Ekiplerimiz Hizmetinizde</h2>
                            <p class="text-blue-100 text-lg mb-8 leading-relaxed">
                                Bölgedeki kurumsal bakım anlaşmalarımız sayesinde, {{ $location->name }}'de her gün mobil ekibimiz bulunmaktadır. Bu sayede en hızlı servis ve teslimat süresini garanti ediyoruz.
                            </p>
                            <ul class="space-y-4 mb-8 text-white">
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                                    <span>{{ $location->name }}{{ $locationType === 'district' && $location->parent ? ', '.$location->parent->name : '' }} hattında günlük servis</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                                    <span>Yerinde keşif ve ekspertiz imkanı</span>
                                </li>
                                <li class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-green-400">check_circle</span>
                                    <span>Yedek parça stoklu mobil araçlar</span>
                                </li>
                            </ul>
                            <button @click="quoteModalOpen = true" class="bg-white text-blue-900 text-lg font-bold py-4 px-10 rounded-lg shadow-xl hover:bg-gray-50 transition-colors inline-flex items-center gap-2">
                                <span>Hemen Randevu Oluştur</span>
                                <span class="material-symbols-outlined">calendar_month</span>
                            </button>
                        </div>
                        <div class="flex-1 w-full max-w-md lg:max-w-full">
                            <div class="relative rounded-2xl overflow-hidden border-4 border-blue-800 shadow-2xl aspect-video bg-gray-800" style='background-image: url("https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800"); background-size: cover; background-position: center;'>
                                <div class="absolute inset-0 bg-blue-900/40"></div>
                                <div class="absolute bottom-4 left-4 right-4 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                                            <span class="material-symbols-outlined">check</span>
                                        </div>
                                        <div class="text-white">
                                            <p class="font-bold text-sm">{{ $location->name }} Operasyon Merkezi</p>
                                            <p class="text-xs text-blue-100">Aktif - 3 Ekip Sahada</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ Section --}}
        <section class="py-20 bg-surface-light dark:bg-surface-dark border-t border-gray-200 dark:border-gray-800">
            <div class="max-w-[960px] mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-blue-900 dark:text-white mb-4">Sıkça Sorulan Sorular</h2>
                    <p class="text-gray-600 dark:text-gray-400">Aklınıza takılan tüm soruların cevapları burada.</p>
                </div>
                
                <div class="space-y-4">
                    <div class="bg-white dark:bg-background-dark rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <h3 class="flex items-center gap-3 font-bold text-lg text-gray-900 dark:text-white mb-3">
                            <span class="material-symbols-outlined text-primary">local_shipping</span>
                            {{ $location->name }} bölgesinden alım için nakliye ücreti ödüyor muyum?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-9">
                            Hayır. {{ $location->name }} ve çevresindeki tüm ofisler için nakliye hizmetimiz tamamen <strong>ücretsizdir</strong>. Kendi araçlarımız ve personelimiz ile mobilyalarınızı katınızdan alıp, işlemleri bittikten sonra tekrar yerine teslim ediyoruz.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-background-dark rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <h3 class="flex items-center gap-3 font-bold text-lg text-gray-900 dark:text-white mb-3">
                            <span class="material-symbols-outlined text-primary">schedule</span>
                            Tamirat süresi ne kadar sürüyor?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-9">
                            Ortalama işlem süremiz <strong>3-5 iş günüdür</strong>. Adetli ve büyük projelerde (50+ koltuk) operasyonu bölerek, ofisinizdeki işleyişi aksatmadan parça parça alım ve teslimat yapıyoruz.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-background-dark rounded-xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <h3 class="flex items-center gap-3 font-bold text-lg text-gray-900 dark:text-white mb-3">
                            <span class="material-symbols-outlined text-primary">verified</span>
                            Yapılan işleme ve parçalara garanti veriyor musunuz?
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed pl-9">
                            Evet. Değiştirilen tüm yedek parçalara (amortisör, mekanizma, tekerlek) ve kumaş/döşeme işçiliğine <strong>2 Yıl Garanti</strong> veriyoruz. Garanti süresince oluşabilecek sorunlarda yerinde ücretsiz servis sağlıyoruz.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <x-landing.footer />
    <x-landing.quote-modal />
</x-landing-layout>

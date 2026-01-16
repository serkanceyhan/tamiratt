<x-landing-layout>
    <x-slot name="head">
        <title>{{ $provider->company_name }} - Hizmet Veren Profili</title>
    </x-slot>

    <x-landing.header />
    
    <main class="bg-gray-50 min-h-screen py-6">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Left Sidebar - Profile Card --}}
            <aside class="lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    {{-- Profile Photo --}}
                    <div class="flex justify-center mb-4">
                        <div class="relative">
                            <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-100">
                                @if($provider->hasMedia('profile_photo'))
                                    <img src="{{ $provider->getFirstMediaUrl('profile_photo') }}" 
                                         alt="{{ $provider->company_name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            {{-- Verified Badge --}}
                            <div class="absolute bottom-0 right-0 bg-green-500 text-white p-1.5 rounded-full border-2 border-white" 
                                 title="Onaylı Hizmet Veren">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Name & Title --}}
                    <div class="text-center mb-4">
                        <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $provider->company_name }}</h1>
                        <p class="text-gray-600">
                            {{ !empty($provider->service_category_names) ? implode(', ', array_slice($provider->service_category_names, 0, 2)) : 'Hizmet Veren' }}
                        </p>
                    </div>

                    {{-- Rating --}}
                    <div class="flex items-center justify-center gap-2 mb-6">
                        <div class="flex items-center">
                            @php
                                $rating = $provider->rating > 0 ? $provider->rating : 4.9; // Dummy data
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                            @endphp
                            @for ($i = 0; $i < $fullStars; $i++)
                                <svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                            @if ($hasHalfStar)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <defs><clipPath id="half"><rect x="0" y="0" width="10" height="20"/></clipPath></defs>
                                    <path clip-path="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endif
                        </div>
                        <span class="text-lg font-semibold text-gray-900">{{ number_format($rating, 1) }}</span>
                        <span class="text-gray-500">({{ $provider->reviews_count > 0 ? $provider->reviews_count : 152 }} Yorum)</span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="space-y-3 mb-6">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Teklif Al
                        </button>
                        <button class="w-full bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-xl border-2 border-gray-200 transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            Mesaj Gönder
                        </button>
                    </div>

                    {{-- Info Cards --}}
                    <div class="space-y-4 border-t border-gray-100 pt-6">
                        {{-- Experience --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Deneyim</p>
                                <p class="font-semibold text-gray-900">{{ $provider->experience_years > 0 ? $provider->experience_years : 12 }} Yıl</p>
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Konum</p>
                                <p class="font-semibold text-gray-900 text-sm">
                                    {{ !empty($provider->service_area_names) ? implode(', ', array_slice($provider->service_area_names, 0, 2)) : 'Kadıköy, İstanbul' }}
                                    @if(count($provider->service_area_names) > 2)
                                        <span class="text-gray-500">(+{{ count($provider->service_area_names) - 2 }} bölge)</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Working Hours --}}
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Çalışma Saatleri</p>
                                <p class="font-semibold text-gray-900">{{ $provider->working_hours ?? '09:00 - 19:00 (arası müsait)' }}</p>
                            </div>
                        </div>

                        {{-- Languages --}}
                        @php
                            $languages = $provider->languages ?? ['Türkçe', 'İngilizce'];
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Diller</p>
                                <p class="font-semibold text-gray-900">{{ implode(', ', $languages) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Right Content - Tabs --}}
            <main class="lg:col-span-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6" x-data="{ activeTab: 'about' }">
                    
                    {{-- Tab Navigation --}}
                    <nav class="flex border-b border-gray-100 overflow-x-auto">
                        <button @click="activeTab = 'about'" 
                                :class="activeTab === 'about' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-6 py-4 border-b-2 font-medium whitespace-nowrap transition">
                            Hakkında
                        </button>
                        <button @click="activeTab = 'portfolio'" 
                                :class="activeTab === 'portfolio' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-6 py-4 border-b-2 font-medium whitespace-nowrap transition">
                            Portfolyo
                        </button>
                        @if(!empty($provider->service_area_names))
                        <button @click="activeTab = 'areas'" 
                                :class="activeTab === 'areas' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-6 py-4 border-b-2 font-medium whitespace-nowrap transition">
                            Hizmet Bölgeleri
                        </button>
                        @endif
                    </nav>

                    {{-- Tab Content --}}
                    <div class="p-6">
                        
                        {{-- About Tab --}}
                        <div x-show="activeTab === 'about'" x-transition>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Hakkında</h2>
                            <div class="prose prose-sm max-w-none text-gray-600 mb-6">
                                {{ $provider->bio ?? '12 yıllık deneyime sahip profesyonel bir ustayım. Müşteri memnuniyetini ön planda tutarak, her işi titizlikle ve zamanında teslim ediyorum. Kaliteli işçilik ve güvenilir hizmet anlayışımla, ev ve işyeri tamirat ihtiyaçlarınızda yanınızdayım.' }}
                            </div>

                            {{-- Badges --}}
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Sertifikalar & Belgeler</h3>
                            <div class="flex flex-wrap gap-3">
                                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg border border-green-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium text-sm">Adil Sicil Kaydı Temiz</span>
                                </div>
                                <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg border border-blue-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                    <span class="font-medium text-sm">Meslek Yeterlilik Belgesi</span>
                                </div>
                            </div>
                        </div>



                        {{-- Portfolio Tab --}}
                        <div x-show="activeTab === 'portfolio'" x-transition x-cloak>
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">Tamamlanan İşler</h2>
                                <span class="text-sm text-gray-500">Tümünü Gör</span>
                            </div>
                            
                            @if($provider->completed_jobs > 0 && $provider->hasMedia('portfolio'))
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($provider->getMedia('portfolio') as $media)
                                        <div class="relative group overflow-hidden rounded-xl">
                                            <img src="{{ $media->getUrl() }}" alt="Portfolio" class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                                            <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                                Tamamlandı
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{-- Dummy Portfolio Data --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative group overflow-hidden rounded-xl bg-gray-100">
                                        <img src="https://images.unsplash.com/photo-1581858726788-75bc0f6a952d?w=500" alt="Ev Tamiri" class="w-full h-64 object-cover">
                                        <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                            Tamamlandı
                                        </div>
                                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                            <p class="text-white font-medium">Ofis Tadilat İşleri - Kadıköy</p>
                                        </div>
                                    </div>
                                    <div class="relative group overflow-hidden rounded-xl bg-gray-100">
                                        <img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500" alt="Ev Tadilat" class="w-full h-64 object-cover">
                                        <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                            Tamamlandı
                                        </div>
                                        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                            <p class="text-white font-medium">Ev İçi Boya ve Mobilya Montajı</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Service Areas Tab --}}
                        @if(!empty($provider->service_area_names))
                        <div x-show="activeTab === 'areas'" x-transition x-cloak>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Hizmet Verilen Bölgeler</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($provider->service_area_names as $area)
                                    <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-medium border border-blue-100">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Uzmanlık Alanları - Separate Section --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Uzmanlık Alanları</h2>
                    <div class="flex flex-wrap gap-2">
                        @forelse($provider->service_category_names as $category)
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition">
                                {{ $category }}
                            </span>
                        @empty
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Mobilya Onarımı</span>
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Boya Badana</span>
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Elektrik Tamiri</span>
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Tesisat İşleri</span>
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Kapı/Pencere Tamiri</span>
                            <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">Perde/Stor Montajı</span>
                        @endforelse
                    </div>
                </div>

                {{-- Müşteri Yorumları - Separate Section --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Müşteri Yorumları</h2>
                        <div class="flex items-center gap-2 text-sm">
                            <span class="text-gray-500">Sırala:</span>
                            <select class="border-gray-200 rounded-lg text-sm">
                                <option>En Yeni</option>
                                <option>En Eski</option>
                            </select>
                        </div>
                    </div>

                    @if($provider->reviews_count > 0)
                        {{-- Real reviews would go here --}}
                    @else
                        {{-- Dummy Reviews --}}
                        <div class="space-y-6">
                            {{-- Review 1 --}}
                            <div class="border-b border-gray-100 pb-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-semibold shrink-0">
                                        MK
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">Mehmet K.</h4>
                                                <p class="text-sm text-gray-500">3 gün önce</p>
                                            </div>
                                            <div class="flex">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-gray-600">
                                            Çok titiz ve kaliteli çalışıyor. Ofisin tüm elektrik ve boya işlerini kusursuz halletti. Kesinlikle tavsiye ederim, işini çok iyi biliyor.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Review 2 --}}
                            <div class="border-b border-gray-100 pb-6">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 font-semibold shrink-0">
                                        SA
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">Selma A.</h4>
                                                <p class="text-sm text-gray-500">1 hafta önce</p>
                                            </div>
                                            <div class="flex">
                                                @for ($i = 0; $i < 5; $i++)
                                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-gray-600">
                                            Evimizin genel bakım ve tamirat işlerini yaptırdık. İş kalitesi mükemmel, zamanında teslim etti. Temiz çalışıyor ve çok güvenilir. Teşekkürler!
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center py-4">
                                <button class="text-blue-600 font-medium hover:underline">
                                    Daha Fazla Yorum Yükle
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</main>

<x-landing.footer />
</x-landing-layout>

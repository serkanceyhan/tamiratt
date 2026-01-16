<x-filament-panels::page>
    <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
        
        {{-- SECTION 1: Categories (Single Row, 3 Columns) --}}
        <section class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-4">
            <header class="fi-section-header flex flex-col gap-3 px-6 py-4 border-b border-gray-200 dark:border-white/10">
                <div class="flex items-center gap-3">
                    <div class="grid flex-1 gap-y-1">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            Destek Konusu Seçin
                        </h3>
                        <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                            Size nasıl yardımcı olabiliriz? Lütfen ilgili başlığı seçin.
                        </p>
                    </div>
                </div>
            </header>

            <div class="fi-section-content p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach([
                        'service' => ['title' => 'Hizmet Sorunları', 'desc' => 'Tamirat kalitesi, personel', 'icon' => 'heroicon-o-wrench-screwdriver', 'color' => 'primary'],
                        'payment' => ['title' => 'Ödeme ve Fatura', 'desc' => 'İade, fatura, bakiye', 'icon' => 'heroicon-o-credit-card', 'color' => 'success'],
                        'technical' => ['title' => 'Teknik Destek', 'desc' => 'Hata, giriş sorunları', 'icon' => 'heroicon-o-computer-desktop', 'color' => 'warning'],
                    ] as $key => $category)
                        @php
                            $isSelected = ($data['category'] ?? null) === $key;
                            $color = $category['color'];
                        @endphp
                        <div 
                            wire:click="$set('data.category', '{{ $key }}')"
                            @class([
                                'relative flex items-start gap-4 p-4 rounded-xl cursor-pointer transition-all duration-200',
                                'ring-2 ring-primary-600 bg-primary-50/50 dark:bg-primary-900/10' => $isSelected,
                                'ring-1 ring-gray-200 hover:ring-primary-600/50 hover:bg-gray-50 dark:ring-white/10 dark:hover:ring-primary-500/50 dark:hover:bg-white/5' => !$isSelected,
                            ])
                        >
                            <div @class([
                                'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                                "bg-{$color}-100 text-{$color}-600 dark:bg-{$color}-900/50 dark:text-{$color}-400" => $isSelected,
                                'bg-gray-100 text-gray-500 dark:bg-white/10 dark:text-gray-400' => !$isSelected,
                            ])>
                                <x-filament::icon icon="{{ $category['icon'] }}" class="h-6 w-6" />
                            </div>
                            
                            <div class="flex-1">
                                <h4 @class([
                                    'text-sm font-bold',
                                    'text-primary-700 dark:text-primary-400' => $isSelected,
                                    'text-gray-900 dark:text-white' => !$isSelected,
                                ])>
                                    {{ $category['title'] }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $category['desc'] }}
                                </p>
                            </div>

                            @if($isSelected)
                                <x-filament::icon icon="heroicon-m-check-circle" class="h-5 w-5 text-primary-600 absolute top-4 right-4" />
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- SECTION 2: Bottom Content (Split View: FAQ Left | Form Right) --}}
        @if(filled($data['category'] ?? null))
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                
                {{-- LEFT COLUMN: FAQs (Span 4) - Moved to Top/Left --}}
                <aside class="md:col-span-4 space-y-6">
                    <section class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sticky top-6">
                        <header class="fi-section-header flex items-center gap-2 px-6 py-4 border-b border-gray-200 dark:border-white/10">
                            <x-filament::icon icon="heroicon-o-question-mark-circle" class="h-5 w-5 text-gray-400" />
                            <h3 class="font-semibold text-gray-900 dark:text-white">Sıkça Sorulan Sorular</h3>
                        </header>
                        
                        <div class="fi-section-content p-6">
                             <div class="space-y-4" x-data="{ active: null }">
                                @forelse($this->faqs as $faq)
                                    <div class="rounded-lg border border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/5 overflow-hidden">
                                        <button 
                                            type="button"
                                            @click="active === {{ $faq->id }} ? active = null : active = {{ $faq->id }}" 
                                            class="w-full px-4 py-3 text-left flex items-center justify-between gap-3 text-sm font-medium text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-white/10 transition-colors"
                                        >
                                            <span>{{ $faq->question }}</span>
                                            <x-filament::icon 
                                                icon="heroicon-m-chevron-down" 
                                                class="h-4 w-4 text-gray-400 transition-transform duration-200"
                                                x-bind:class="{ 'rotate-180': active === {{ $faq->id }} }"
                                            />
                                        </button>
                                        <div 
                                            x-show="active === {{ $faq->id }}" 
                                            x-collapse
                                            x-cloak
                                            class="px-4 pb-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed"
                                        >
                                            {{ $faq->answer }}
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 text-center py-2">Henüz SSS eklenmemiş.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-gray-50 dark:bg-white/5 border-t border-gray-200 dark:border-white/10 rounded-b-xl">
                            <p class="text-xs text-gray-500 text-center">
                                Sorunuzun cevabını bulamadınız mı?
                                <br>
                                Bize aşağıdaki formdan kayıt bırakabilirsiniz.
                            </p>
                        </div>
                    </section>
                </aside>

                {{-- RIGHT COLUMN: Forms (Span 8) --}}
                <div class="md:col-span-8 space-y-6">
                    
                    {{-- Service Selection (Only if 'service') --}}
                    @if(($data['category'] ?? null) === 'service')
                        <section class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                            <header class="fi-section-header flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-white/10">
                                <h3 class="font-semibold text-gray-900 dark:text-white">İlgili Hizmeti Seçin</h3>
                                <div class="flex gap-2">
                                     @foreach(['1_month'=>'1 Ay', '3_months'=>'3 Ay', 'all'=>'Tümü'] as $val => $label)
                                        <button 
                                            type="button"
                                            wire:click="$set('timeFilter', '{{ $val }}')"
                                            @class([
                                                'px-2 py-1 text-xs font-medium rounded transition-colors',
                                                'bg-gray-100 text-gray-900 dark:bg-white/20 dark:text-white' => $timeFilter === $val,
                                                'text-gray-500 hover:text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5' => $timeFilter !== $val,
                                            ])
                                        >
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </header>

                            <div class="fi-section-content p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($this->offers as $offer)
                                        @php
                                            $isOfferSelected = ($data['provider_offer_id'] ?? null) == $offer->id;
                                        @endphp
                                        <div 
                                            wire:click="$set('data.provider_offer_id', {{ $offer->id }})"
                                            @class([
                                                'relative flex flex-col p-4 rounded-xl cursor-pointer transition-all border',
                                                'border-primary-600 bg-primary-50/20 dark:border-primary-500 dark:bg-primary-900/10 shadow-sm' => $isOfferSelected,
                                                'border-gray-200 hover:border-gray-300 dark:border-white/10 dark:hover:border-white/20' => !$isOfferSelected,
                                            ])
                                        >
                                            <div class="flex justify-between items-start mb-2">
                                                <div @class(['inline-flex items-center px-2 py-1 rounded-md text-xs font-medium ring-1 ring-inset', $offer->status_color])>
                                                    {{ $offer->status_label }}
                                                </div>
                                                @if($isOfferSelected)
                                                    <x-filament::icon icon="heroicon-m-check-circle" class="h-5 w-5 text-primary-600" />
                                                @endif
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white line-clamp-1">
                                                {{ $offer->serviceRequest->service->name ?? 'Hizmet' }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $offer->provider->company_name }}
                                            </p>
                                            <div class="mt-2 text-xs text-gray-400">
                                                {{ $offer->created_at->format('d.m.Y') }}
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full py-8 text-center text-gray-500 dark:text-gray-400 border border-dashed border-gray-200 dark:border-white/10 rounded-xl">
                                            Bu tarih aralığında hizmet kaydı bulunamadı.
                                        </div>
                                    @endforelse
                                </div>
                                
                                @if($this->hasMoreOffers)
                                    <div class="mt-4 text-center">
                                        <button type="button" wire:click="loadMoreOffers" class="text-sm font-medium text-primary-600 hover:underline">
                                            Daha Fazla Göster
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    {{-- Form Area --}}
                    @if(($data['category'] ?? null) !== 'service' || filled($data['provider_offer_id'] ?? null))
                        <section class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                            <header class="fi-section-header flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-white/10">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Talep Detayları</h3>
                            </header>
                            
                            <div class="fi-section-content p-6">
                                {{ $this->form }}

                                <div class="mt-6 flex items-center gap-3">
                                    <x-filament::button type="submit" size="lg" wire:loading.attr="disabled">
                                        Destek Talebi Oluştur
                                    </x-filament::button>
                                    
                                    <x-filament::button color="gray" tag="a" href="{{ route('filament.customer.pages.dashboard') }}" size="lg">
                                        Vazgeç
                                    </x-filament::button>
                                </div>
                            </div>
                        </section>
                    @endif

                </div>

            </div>
        @endif

    </div>
</x-filament-panels::page>

<div class="w-full">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-3">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-3">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-primary mb-2">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Taleplerime Dön
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Talep #{{ $request->id }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ $request->created_at->format('d.m.Y H:i') }}</p>
        </div>
        
        @php
            $statusColors = [
                'open' => 'bg-blue-100 text-blue-700 border-blue-200',
                'locked' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'completed' => 'bg-green-100 text-green-700 border-green-200',
                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                'expired' => 'bg-gray-100 text-gray-700 border-gray-200',
            ];
            $statusLabels = [
                'open' => 'Teklif Bekleniyor',
                'locked' => 'Devam Ediyor',
                'completed' => 'Tamamlandı',
                'cancelled' => 'İptal Edildi',
                'expired' => 'Süresi Doldu',
            ];
        @endphp
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-700' }}">
            {{ $statusLabels[$request->status] ?? $request->status }}
        </span>
    </div>

    {{-- Layout Grid --}}
    <div class="flex flex-col-reverse lg:grid lg:grid-cols-12 gap-8">
        
        {{-- LEFT COLUMN (Details, Actions, Help) --}}
        <div class="lg:col-span-5 space-y-6">
            {{-- Request Details Card --}}
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.702.127 1.5.876 2.056 2.671 1.725 4.148-.28 1.25-.972 2.227-1.898 2.872M4.646 4.39a3.75 3.75 0 105.303 5.303 3.75 3.75 0 00-5.303-5.303z" />
                        </svg>
                        Talep Detayları
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Service --}}
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">HİZMET</div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $request->service?->name ?? '-' }}</p>
                                @if($request->subServices->count() > 0)
                                    <p class="text-sm text-gray-500">
                                        {{ $request->subServices->pluck('name')->join(', ') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">AÇIKLAMA</div>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-gray-700 dark:text-gray-300">
                            {{ $request->description ?: 'Açıklama girilmedi.' }}
                        </div>
                    </div>

                    {{-- Location & Date --}}
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">KONUM</div>
                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                {{ $request->location?->name }}{{ $request->location?->parent ? ', ' . $request->location->parent->name : '' }}
                            </div>
                            @if($request->address)
                                <p class="text-sm text-gray-500 mt-1 ml-7">{{ $request->address }}</p>
                            @endif
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">TERCİH EDİLEN TARİH</div>
                            <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                {{ $request->preferred_date ? $request->preferred_date->format('d.m.Y') : 'Esnek' }}
                            </div>
                        </div>
                    </div>

                    {{-- Photos --}}
                    @if($this->photos->count() > 0)
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">FOTOĞRAFLAR</div>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                @foreach($this->photos as $photo)
                                    <a href="{{ $photo->getUrl() }}" target="_blank" class="aspect-square rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:opacity-80 transition-opacity">
                                        <img src="{{ $photo->getUrl('thumb') ?? $photo->getUrl() }}" alt="Fotoğraf" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions Card (Moved to Left Column) --}}
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">İşlemler</h3>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @if($request->status === 'locked')
                        <button wire:click="markAsCompleted" class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-xl transition-colors">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tamamlandı İşaretle
                        </button>
                    @endif

                    @if(in_array($request->status, ['open', 'locked']))
                        <button wire:click="openCancelModal" class="flex items-center justify-center gap-2 border border-red-300 text-red-600 hover:bg-red-50 font-semibold py-3 px-4 rounded-xl transition-colors">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Talebi İptal Et
                        </button>
                    @endif

                    @if(in_array($request->status, ['open', 'draft', 'pending_verification']))
                        <button wire:click="openEditModal" class="flex items-center justify-center gap-2 border border-blue-300 text-blue-600 hover:bg-blue-50 font-semibold py-3 px-4 rounded-xl transition-colors">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Talebi Düzenle
                        </button>
                    @endif

                    <a href="{{ route('services') }}" class="flex items-center justify-center gap-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 font-semibold py-3 px-4 rounded-xl transition-colors">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Yeni Talep Oluştur
                    </a>
                </div>
            </div>

            {{-- Contact Info Card --}}
            @if($this->selectedOffer)
                <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-200 dark:border-green-800 p-6">
                    <h3 class="font-bold text-green-800 dark:text-green-400 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Hizmet Verenle İletişim
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-green-600 dark:text-green-500">Firma</p>
                            <p class="font-semibold text-green-800 dark:text-green-400">{{ $this->selectedOffer->provider?->company_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-600 dark:text-green-500">Telefon</p>
                            <a href="tel:+90{{ $this->selectedOffer->provider?->user?->phone }}" class="font-semibold text-green-800 dark:text-green-400 hover:underline">
                                +90 {{ $this->selectedOffer->provider?->user?->phone }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Help Card (Moved to Left Column) --}}
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                    </svg>
                    Yardım
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Sorun mu yaşıyorsunuz? Bize ulaşın.</p>
                <a href="https://wa.me/905xxxxxxxxx" target="_blank" class="flex items-center gap-2 text-green-600 hover:underline text-sm font-medium">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                    WhatsApp ile İletişim
                </a>
            </div>
        </div>

        {{-- RIGHT COLUMN (Offers) --}}
        {{-- In HTML this is last, but visual order on mobile is TOP due to flex-col-reverse --}}
        <div class="lg:col-span-7 space-y-6">
            {{-- Offers Section --}}
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        Gelen Teklifler
                        @if($this->offers->count() > 0)
                            <span class="bg-primary text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $this->offers->count() }}</span>
                        @endif
                    </h2>
                </div>

                @if($this->offers->count() === 0)
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Henüz teklif yok</h3>
                        <p class="text-gray-500 dark:text-gray-400">Hizmet sağlayıcılar talebinizi değerlendiriyor. Teklifler geldiğinde burada göreceksiniz.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($this->offers as $offer)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <div class="flex items-start gap-4">
                                    {{-- Provider Avatar --}}
                                    <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xl font-bold text-primary">{{ substr($offer->provider?->company_name ?? 'H', 0, 1) }}</span>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $offer->provider?->company_name ?? 'Hizmet Veren' }}
                                                </h3>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <div class="flex items-center gap-1 text-yellow-500">
                                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                        <span class="text-sm font-medium">4.8</span>
                                                    </div>
                                                    <span class="text-gray-300">•</span>
                                                    <span class="text-sm text-gray-500">12</span>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-2xl font-bold text-primary">₺{{ number_format($offer->price, 0, ',', '.') }}</p>
                                                <p class="text-xs text-gray-500">{{ $offer->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>

                                        @if($offer->message)
                                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-sm text-gray-600 dark:text-gray-400">
                                                {{ Str::limit($offer->message, 150) }}
                                            </div>
                                        @endif

                                        @if($offer->status === 'accepted')
                                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Kabul Edildi
                                            </div>
                                        @elseif($request->status === 'open')
                                            <div class="mt-4 flex items-center gap-3">
                                                <a href="{{ route('customer.request.offers', $request->id) }}" class="inline-flex items-center gap-2 bg-primary hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors text-sm">
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Detayları Gör
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Edit Modal --}}
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data x-init="$el.focus()" @keydown.escape="$wire.closeEditModal()">
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-xl max-w-lg w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Talebi Düzenle</h3>
                    <button wire:click="closeEditModal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Açıklama</label>
                        <textarea wire:model="editDescription" rows="4" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800" placeholder="İhtiyacınızı detaylı anlatın..."></textarea>
                        @error('editDescription') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tercih Edilen Tarih</label>
                        <input type="date" wire:model="editDate" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800">
                        @error('editDate') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button wire:click="closeEditModal" class="flex-1 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        İptal
                    </button>
                    <button wire:click="updateRequest" class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors">
                        Kaydet
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Cancel Modal --}}
    @if($showCancelModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-data x-init="$el.focus()" @keydown.escape="$wire.closeCancelModal()">
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Talebi İptal Et</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Bu işlem geri alınamaz. Devam etmek istiyor musunuz?</p>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İptal Sebebi (İsteğe Bağlı)</label>
                    <textarea wire:model="cancelReason" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800" placeholder="Neden iptal ediyorsunuz?"></textarea>
                </div>

                <div class="flex gap-3">
                    <button wire:click="closeCancelModal" class="flex-1 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Vazgeç
                    </button>
                    <button wire:click="cancelRequest" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-colors">
                        İptal Et
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

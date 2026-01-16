<div class="bg-white dark:bg-surface-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-primary/5 to-transparent">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Son Talebiniz</h2>
                <p class="text-sm text-gray-500">#{{ $request->id }} • {{ $request->created_at->diffForHumans() }}</p>
            </div>
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
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium border {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-700' }}">
            {{ $statusLabels[$request->status] ?? $request->status }}
        </span>
    </div>

    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-8">
            {{-- Left: Service Details --}}
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.702.127 1.5.876 2.056 2.671 1.725 4.148-.28 1.25-.972 2.227-1.898 2.872M4.646 4.39a3.75 3.75 0 105.303 5.303 3.75 3.75 0 00-5.303-5.303z" />
                    </svg>
                    Hizmet Detayları
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">HİZMET TÜRÜ</div>
                        <div class="font-medium text-gray-900 dark:text-white">{{ $request->service?->name ?? 'Belirtilmedi' }}</div>
                    </div>
                    @if($request->subServices && $request->subServices->count() > 0)
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">SEÇİLEN HİZMETLER</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($request->subServices as $sub)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $sub->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">AÇIKLAMA</div>
                        <div class="text-gray-700 dark:text-gray-300 text-sm bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">
                            {{ $request->description ?: 'Açıklama girilmedi.' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Location & Contact --}}
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Konum & İletişim
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center flex-shrink-0 text-orange-600">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-0.5">Konum</div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $request->location?->name }}{{ $request->location?->parent ? ', ' . $request->location->parent->name : '' }}
                            </div>
                            @if($request->address)
                                <div class="text-sm text-gray-500 mt-1">{{ $request->address }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center flex-shrink-0 text-blue-600">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-0.5">Tercih Edilen Tarih</div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $request->preferred_date ? $request->preferred_date->format('d.m.Y') : 'Esnek' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Offers Section --}}
                @php
                    $offerCount = $request->offers()->count();
                @endphp
                @if($offerCount > 0)
                    <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                            <div>
                                <p class="font-bold text-green-800 dark:text-green-400">{{ $offerCount }} Teklif Aldınız!</p>
                                <p class="text-sm text-green-600 dark:text-green-500">Teklifleri incelemek için tıklayın.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-bold text-blue-800 dark:text-blue-400">Teklifler Bekleniyor</p>
                                <p class="text-sm text-blue-600 dark:text-blue-500">Hizmet sağlayıcılar talebinizi değerlendiriyor.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Card Actions Footer --}}
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-end gap-3">
        @if($request->status === 'locked')
            <button wire:click="markAsCompleted" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tamamlandı İşaretle
            </button>
        @endif

        @if(in_array($request->status, ['open', 'locked']))
            <button wire:click="openCancelModal" class="inline-flex items-center gap-2 border border-red-300 text-red-600 hover:bg-red-50 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Talebi İptal Et
            </button>
        @endif

        @if(in_array($request->status, ['open', 'draft', 'pending_verification']))
            <button wire:click="openEditModal" class="inline-flex items-center gap-2 border border-blue-300 text-blue-600 hover:bg-blue-50 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Talebi Düzenle
            </button>
        @endif

        <a href="{{ route('customer.request.show', $request->id) }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Detayları Gör
        </a>
    </div>

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
</div>

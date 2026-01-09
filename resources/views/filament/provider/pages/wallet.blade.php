<x-filament-panels::page>
    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Left Column: Balance & Payment --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Current Balance Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Mevcut Bakiye</p>
                <div class="flex items-center gap-4">
                    <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ $this->getBalance() }}</span>
                    <span class="text-green-500 text-sm flex items-center gap-1">
                        <x-heroicon-m-arrow-trending-up class="w-4 h-4" />
                        +0%
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-2">Son güncelleme: {{ now()->format('H:i') }}</p>
            </div>

            {{-- Statistics Cards --}}
            <div class="grid md:grid-cols-4 gap-4">
                @php
                    $stats = $this->getStats();
                @endphp
                
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-arrow-trending-up class="w-5 h-5 text-green-600" />
                        <span class="text-sm text-green-700 dark:text-green-400 font-medium">Toplam Yüklenen</span>
                    </div>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($stats['totalCredit'], 2, ',', '.') }} ₺</p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-arrow-trending-down class="w-5 h-5 text-red-600" />
                        <span class="text-sm text-red-700 dark:text-red-400 font-medium">Toplam Harcanan</span>
                    </div>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ number_format($stats['totalDebit'], 2, ',', '.') }} ₺</p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-wallet class="w-5 h-5 text-blue-600" />
                        <span class="text-sm text-blue-700 dark:text-blue-400 font-medium">Güncel Bakiye</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($stats['currentBalance'], 2, ',', '.') }} ₺</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                    <div class="flex items-center gap-2 mb-2">
                        <x-heroicon-o-calendar class="w-5 h-5 text-purple-600" />
                        <span class="text-sm text-purple-700 dark:text-purple-400 font-medium">Bu Ay</span>
                    </div>
                    <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ number_format($stats['thisMonth'], 2, ',', '.') }} ₺</p>
                </div>
            </div>

            {{-- Load Balance Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Bakiye Yükle</h3>

                {{-- Amount Selection --}}
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Yüklenecek Tutar</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($this->getPackages() as $package)
                            <button
                                wire:click="selectPackage({{ $package->id }})"
                                class="px-6 py-3 rounded-lg border-2 font-semibold transition-all
                                    {{ $this->selectedPackageId === $package->id 
                                        ? 'bg-primary-500 text-white border-primary-500' 
                                        : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:border-primary-400' }}"
                            >
                                {{ number_format($package->price, 0, ',', '.') }} ₺
                            </button>
                        @endforeach
                        <button
                            wire:click="setCustomAmount"
                            class="px-6 py-3 rounded-lg border-2 font-semibold transition-all flex items-center gap-2
                                {{ $this->selectedPackageId === null && $this->customAmount 
                                    ? 'bg-primary-500 text-white border-primary-500' 
                                    : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:border-primary-400' }}"
                        >
                            Diğer
                            <x-heroicon-m-currency-dollar class="w-4 h-4" />
                        </button>
                    </div>

                    @if($this->selectedPackageId === null)
                        <div class="mt-4">
                            <input
                                type="number"
                                wire:model.live="customAmount"
                                placeholder="Tutar girin..."
                                min="50"
                                class="w-full max-w-xs px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700"
                            />
                        </div>
                    @endif
                </div>

                {{-- Payment Method --}}
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Ödeme Yöntemi</p>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-3 px-4 py-3 border-2 rounded-lg cursor-pointer transition-all
                            {{ $this->paymentMethod === 'credit_card' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                            <input type="radio" wire:model.live="paymentMethod" value="credit_card" class="hidden" />
                            <x-heroicon-o-credit-card class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                            <span class="font-medium">Kredi Kartı</span>
                        </label>
                        <label class="flex items-center gap-3 px-4 py-3 border-2 rounded-lg cursor-pointer transition-all
                            {{ $this->paymentMethod === 'bank_transfer' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                            <input type="radio" wire:model.live="paymentMethod" value="bank_transfer" class="hidden" />
                            <x-heroicon-o-building-library class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                            <span class="font-medium">Havale / EFT</span>
                        </label>
                    </div>
                </div>

                @if($this->paymentMethod === 'credit_card')
                    {{-- Credit Card Form (Placeholder) --}}
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Kart Üzerindeki İsim</label>
                            <div class="flex items-center gap-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                                <x-heroicon-o-user class="w-5 h-5 text-gray-400" />
                                <input type="text" placeholder="Ad Soyad" class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Kart Numarası</label>
                            <div class="flex items-center gap-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                                <x-heroicon-o-credit-card class="w-5 h-5 text-gray-400" />
                                <input type="text" placeholder="0000 0000 0000 0000" class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Son Kullanma (Ay/Yıl)</label>
                                <div class="flex items-center gap-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <x-heroicon-o-calendar class="w-5 h-5 text-gray-400" />
                                    <input type="text" placeholder="MM/YY" class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">CVV / CVC</label>
                                <div class="flex items-center gap-2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <x-heroicon-o-lock-closed class="w-5 h-5 text-gray-400" />
                                    <input type="text" placeholder="123" class="flex-1 bg-transparent border-none focus:ring-0 text-gray-900 dark:text-white" />
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Bank Transfer Info --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            Havale/EFT ile ödeme yapmak için aşağıdaki hesap bilgilerini kullanın. Açıklama kısmına telefon numaranızı yazmayı unutmayın.
                        </p>
                        <div class="mt-3 text-sm">
                            <p><strong>Banka:</strong> Garanti BBVA</p>
                            <p><strong>IBAN:</strong> TR00 0000 0000 0000 0000 0000 00</p>
                        </div>
                    </div>
                @endif

                {{-- Total & Submit --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm text-gray-500">Toplam Ödenecek Tutar</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($this->getSelectedAmount(), 2, ',', '.') }} ₺</p>
                    </div>
                    <button
                        wire:click="processPayment"
                        wire:loading.attr="disabled"
                        class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg flex items-center gap-2 transition-colors disabled:opacity-50"
                    >
                        <x-heroicon-s-lock-closed class="w-5 h-5" />
                        <span wire:loading.remove wire:target="processPayment">Güvenli Ödeme Yap</span>
                        <span wire:loading wire:target="processPayment">İşleniyor...</span>
                    </button>
                </div>

                <p class="text-xs text-gray-400 mt-4 flex items-center gap-1">
                    <x-heroicon-o-shield-check class="w-4 h-4" />
                    Ödemeleriniz 256-bit SSL sertifikası ile korunmaktadır.
                </p>
            </div>
        </div>

        {{-- Right Column: Transaction History --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">İşlem Geçmişi</h3>
                    <a href="#" class="text-primary-500 text-sm font-medium hover:underline">Tümünü Gör</a>
                </div>
                
                {{ $this->table }}
            </div>

            {{-- Auto Top-up Card --}}
            <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl p-5 text-white">
                <h4 class="font-semibold mb-2">Otomatik Ödeme Talimatı</h4>
                <p class="text-sm text-primary-100 mb-4">
                    Bakiyeniz 50 TL'nin altına düştüğünde otomatik yükleme yapın, iş fırsatlarını kaçırmayın.
                </p>
                <button class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-medium transition-colors">
                    Talimat Oluştur
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>

<div class="w-full max-w-md mx-auto">
    {{-- Debug Message --}}
    @if(session('debug_message'))
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-300 rounded-xl text-yellow-800 text-sm">
            <strong>🔧 Debug:</strong> {{ session('debug_message') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if($error)
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-3">
            <span class="material-symbols-outlined">error</span>
            {{ $error }}
        </div>
    @endif

    {{-- Success Message --}}
    @if($success)
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            {{ $success }}
        </div>
    @endif

    <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-gray-700">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl text-primary">login</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Giriş Yap</h1>
            <p class="text-gray-500 dark:text-gray-400">Telefon numaranız ile hızlıca giriş yapın</p>
        </div>

        @if(!$otpSent)
            {{-- Step 1: Phone Input --}}
            <form wire:submit.prevent="sendOtp" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Telefon Numarası
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">+90</span>
                        <input type="tel" 
                               wire:model="phone" 
                               placeholder="5XX XXX XX XX"
                               maxlength="10"
                               class="w-full pl-14 pr-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all text-lg tracking-wider @error('phone') border-red-500 @enderror">
                    </div>
                    @error('phone')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="w-full py-4 bg-primary hover:bg-blue-700 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove>Doğrulama Kodu Gönder</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Gönderiliyor...
                    </span>
                </button>
            </form>
        @else
            {{-- Step 2: OTP Verification --}}
            <form wire:submit.prevent="verifyOtp" class="space-y-6">
                {{-- Phone Display --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">phone_android</span>
                        <span class="font-medium text-gray-700 dark:text-gray-300">+90 {{ $phone }}</span>
                    </div>
                    <button type="button" wire:click="changePhone" class="text-sm text-primary hover:underline">
                        Değiştir
                    </button>
                </div>

                {{-- Name Input (for new users) --}}
                @if($isNewUser)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ad Soyad
                        </label>
                        <input type="text" 
                               wire:model="name" 
                               placeholder="Ahmet Yılmaz"
                               class="w-full px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('name') border-red-500 @enderror">
                        @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-2 text-xs text-gray-500">İlk kez giriş yapıyorsunuz, lütfen adınızı girin.</p>
                    </div>
                @endif

                {{-- OTP Input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Doğrulama Kodu
                    </label>
                    <input type="text" 
                           wire:model="otpCode" 
                           placeholder="000000"
                           maxlength="6"
                           inputmode="numeric"
                           pattern="[0-9]*"
                           class="w-full px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all text-center text-2xl tracking-[0.5em] font-mono @error('otpCode') border-red-500 @enderror">
                    @error('otpCode')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="w-full py-4 bg-primary hover:bg-blue-700 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-3">
                    <span wire:loading.remove>Giriş Yap</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Doğrulanıyor...
                    </span>
                </button>

                {{-- Resend --}}
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        Kod gelmedi mi? 
                        @if($resendCountdown > 0)
                            <span class="text-gray-400">{{ $resendCountdown }} saniye bekleyin</span>
                        @else
                            <button type="button" wire:click="sendOtp" class="text-primary hover:underline font-medium">
                                Tekrar Gönder
                            </button>
                        @endif
                    </p>
                </div>
            </form>
        @endif

        {{-- Divider --}}
        <div class="my-8 flex items-center gap-4">
            <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
            <span class="text-sm text-gray-400">veya</span>
            <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
        </div>

        {{-- Alternative Actions --}}
        <div class="space-y-3 text-center">
            <a href="{{ route('provider.apply') }}" class="block w-full py-3 border-2 border-primary text-primary font-bold rounded-xl hover:bg-primary hover:text-white transition-all">
                Hizmet Veren Olarak Kayıt Ol
            </a>
            <p class="text-sm text-gray-500">
                Kurumsal giriş için: 
                <a href="/panel/login" class="text-primary hover:underline">Hizmet Veren Paneli</a>
            </p>
        </div>
    </div>
</div>

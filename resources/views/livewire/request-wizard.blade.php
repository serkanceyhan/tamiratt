<div class="w-full">
    {{-- Header & Progress --}}
    <div class="max-w-3xl mx-auto mb-8 px-4">
        {{-- Navigation Header (Mobile Friendly) --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                @if($currentStep > 1)
                    <button wire:click="previousStep" class="p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors" title="Geri">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </button>
                @endif
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Adım {{ $currentStep }}/5
                </span>
            </div>

            <span class="text-sm font-bold text-primary">
                @if($currentStep === 5 && !$phoneVerified)
                    Neredeyse Tamamlandı
                @else
                    %{{ round(($currentStep / 5) * 100) }} Tamamlandı
                @endif
            </span>
        </div>

        {{-- Progress Bar (Only visible line) --}}
        <div class="w-full h-1.5 bg-gray-100 dark:bg-gray-800 rounded-full mb-8 relative overflow-hidden">
             <div class="absolute top-0 left-0 h-full bg-blue-500 transition-all duration-500 rounded-full" 
                  style="width: {{ ($currentStep === 5 && !$phoneVerified) ? '90%' : (($currentStep / 5) * 100) . '%' }}">
             </div>
        </div>
        
        {{-- Stepper Dots (Connected) --}}
        <div class="relative flex justify-between w-full px-2 mb-8 hidden"> {{-- Hidden for now as user asked for 'stepper bar half full' --}}
             {{-- Wait, user asked for 'noktalar kendinden büyük bir dair içerisie alınsın ve aralarında çizgili bağlantı olsun' --}}
             {{-- So I should keep the stepper but style it differently, OR replace the segmented bar with this new design? --}}
             {{-- User said: "telefon doğrulama adımında çubuğun yarısına kadar gelsin... noktalar kendinden büyük bir dair içerisie alınsın" --}}
             {{-- I will implement the 'Dots with connecting line' design here instead of the segmented bar --}}
        </div>

        {{-- Green Check / Blue Active / Gray Pending Stepper --}}
        <div class="w-full px-4 mb-10">
            <div class="flex items-center justify-between relative">
                @foreach(range(1, 5) as $step)
                    @php 
                        $isCompleted = $step < $currentStep;
                        $isCurrent = $step === $currentStep;
                        $isPending = $step > $currentStep;
                        $isLast = $step === 5;
                        
                        // Circle Styles
                        if ($isCompleted) {
                            // Tamamlanan: Yeşil Daire + TikTok
                            $circleClass = "w-10 h-10 rounded-full bg-green-500 border-2 border-green-500 flex items-center justify-center text-white z-10 transition-colors duration-300";
                            $content = '<span class="material-symbols-outlined text-xl font-bold">check</span>';
                            $lineColor = "bg-green-500"; // Sonraki çizgi yeşil
                        } elseif ($isCurrent) {
                            // Aktif: Mavi Daire + Numara
                            $circleClass = "w-10 h-10 rounded-full bg-primary border-2 border-primary flex items-center justify-center text-white font-bold text-lg z-10 shadow-lg shadow-blue-500/30 scale-110 transition-transform duration-300";
                            $content = $step;
                            $lineColor = "bg-gray-200 dark:bg-gray-700"; // Sonraki çizgi gri (henüz tamamlanmadı)
                        } else {
                            // Bekleyen: Gri Daire + Numara
                            $circleClass = "w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 font-semibold text-lg z-10";
                            $content = $step;
                            $lineColor = "bg-gray-200 dark:bg-gray-700";
                        }
                    @endphp
                    
                    {{-- Step Item --}}
                    <div class="relative flex items-center {{ !$isLast ? 'flex-1' : '' }}">
                        
                        {{-- The Circle --}}
                        <div 
                            @if($isCompleted) wire:click="$set('currentStep', {{ $step }})" @endif
                            class="{{ $circleClass }} {{ $isCompleted ? 'cursor-pointer hover:bg-green-600 hover:border-green-600' : '' }}"
                        >
                            {!! $content !!}
                        </div>

                        {{-- The Line (Connects to next step) --}}
                        @if(!$isLast)
                            {{-- Çizgi yüksekliği ve pozisyonu --}}
                            <div class="flex-1 h-1 {{ $lineColor }} transition-colors duration-500 -ml-1 -mr-1"></div>
                        @endif
                        
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('debug_message'))
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-700 text-sm">
            🔧 {{ session('debug_message') }}
        </div>
    @endif

    {{-- Step Content --}}
    <div class="px-2 pb-28">
        
        {{-- Step 1: Sub-Service Selection & Questions --}}
        @if($currentStep === 1)
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">İhtiyacın detayları neler?</h3>

                {{-- Sub-Service Selection (Visual Cards) --}}
                {{-- Grouped Sub-Service Selection (Screenshot View) --}}
                {{-- Grouped Sub-Service Selection Removed per user request --}}
                {{-- Standard Sub-Service Selection (Fallback for non-grouped) --}}
                @if($this->subServices->count() > 0)
                <div class="mb-6" x-data="{ 
                    selected: @entangle('subServiceIds').live,
                    toggle(id) {
                        const idx = this.selected.indexOf(id);
                        if (idx > -1) { this.selected.splice(idx, 1); } 
                        else { this.selected.push(id); }
                    },
                    isSelected(id) { return this.selected.includes(id); }
                }">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Alt Hizmet Seçin <span class="text-red-500">*</span> <span class="text-xs font-normal text-gray-500 ml-1">(Birden fazla seçebilirsiniz)</span></label>
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($this->subServices as $subService)
                            <div 
                                @click="toggle({{ $subService->id }})"
                                class="relative flex flex-col items-center gap-2 p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-blue-400 group active:scale-95"
                                :class="isSelected({{ $subService->id }}) ? 'border-primary bg-primary/5 shadow-md shadow-primary/10' : 'border-gray-100 hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800'"
                            >
                                <div 
                                    class="absolute top-3 right-3 w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors shadow-sm z-20"
                                    :class="isSelected({{ $subService->id }}) ? 'bg-primary border-primary' : 'border-gray-300 bg-white dark:bg-gray-700'"
                                >
                                    <span x-show="isSelected({{ $subService->id }})" class="material-symbols-outlined text-sm text-white font-bold">check</span>
                                </div>
                                
                                <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                                    @if($subService->icon)
                                    <span class="material-symbols-outlined text-2xl" :class="isSelected({{ $subService->id }}) ? 'text-primary' : 'text-blue-600 dark:text-blue-400'">{{ $subService->icon }}</span>
                                    @else
                                    <span class="material-symbols-outlined text-2xl" :class="isSelected({{ $subService->id }}) ? 'text-primary' : 'text-blue-600 dark:text-blue-400'">handyman</span>
                                    @endif
                                </div>
                                <span class="font-semibold text-sm text-center leading-tight" :class="isSelected({{ $subService->id }}) ? 'text-primary' : 'text-gray-700 dark:text-gray-300'">{{ $subService->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @error('subServiceIds') <div class="mb-4 bg-red-50 text-red-600 p-3 rounded-lg text-sm flex items-center gap-2 border border-red-200"><span class="material-symbols-outlined text-lg">error</span> {{ $message }}</div> @enderror

                {{-- Dynamic Questions Removed per user request --}}

                {{-- Description Field (Required, min 20 chars) --}}
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-base font-medium text-gray-800 dark:text-white mb-3">
                        İhtiyacınızı detaylı açıklayın <span class="text-red-500">*</span>
                    </h4>
                    <div class="relative" x-data="{ charCount: {{ strlen($description) }}, minChars: 20 }">
                        <textarea 
                            wire:model="description"
                            x-on:input="charCount = $event.target.value.length"
                            rows="4"
                            class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800 resize-none border-gray-300 dark:border-gray-600"
                            :class="{ 'border-red-500': charCount > 0 && charCount < minChars }"
                            placeholder="{{ $this->selectedService?->description_placeholder ?? 'İhtiyacınızı detaylı bir şekilde açıklayın. Ne yapılmasını istediğinizi, özel isteklerinizi belirtin...' }}"
                        ></textarea>
                        <div class="absolute bottom-3 right-3 text-xs" :class="charCount >= minChars ? 'text-green-600' : 'text-gray-400'">
                            <span x-text="charCount"></span>/20
                        </div>
                    </div>
                    <template x-if="charCount > 0 && charCount < 20">
                        <span class="text-red-500 text-sm mt-1 block">En az 20 karakter girmelisiniz.</span>
                    </template>
                    @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-400 mt-2">Min. 20 karakter</p>
                </div>


            </div>
        @endif

        {{-- Step 2: Photo Upload Only --}}
        @if($currentStep === 2)
            <div class="animate-fade-in-up">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Fotoğraf Ekle (İsteğe Bağlı)</h3>

                {{-- Photo Upload --}}
                <div class="mb-6">
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 text-center hover:border-primary transition-colors">
                        <input 
                            type="file" 
                            wire:model="photos" 
                            multiple 
                            accept="image/*"
                            class="hidden" 
                            id="photo-upload-step2"
                        >
                        <label for="photo-upload-step2" class="cursor-pointer">
                            <span class="material-symbols-outlined text-4xl text-gray-400 mb-2">add_photo_alternate</span>
                            <p class="text-sm text-gray-500">Fotoğraf eklemek için tıklayın</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG - Max 5 fotoğraf</p>
                        </label>
                    </div>
                    @if(count($photos) > 0)
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($photos as $index => $photo)
                        <div class="relative">
                            <img src="{{ $photo->temporaryUrl() }}" class="w-20 h-20 object-cover rounded-lg">
                            <button type="button" wire:click="removePhoto({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">&times;</button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Step 3: Time --}}
        @if($currentStep === 3)
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Ne zaman?</h3>

                {{-- Time Options (Armut-style radio list) --}}
                <div class="space-y-3 mb-6">
                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all
                        {{ $preferredTime === 'specific' ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700 hover:border-gray-400' }}">
                        <input type="radio" name="preferredTime" wire:model.live="preferredTime" value="specific" class="w-5 h-5 text-primary">
                        <span class="font-medium">Belli bir zaman (üç hafta içinde)</span>
                    </label>

                    {{-- Date/Time picker when specific is selected --}}
                    @if($preferredTime === 'specific')
                    <div class="ml-8 grid grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Tarih *</label>
                            <input 
                                type="date" 
                                wire:model="preferredDate"
                                min="{{ now()->format('Y-m-d') }}"
                                max="{{ now()->addWeeks(3)->format('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 @error('preferredDate') border-red-500 @enderror"
                            >
                            @error('preferredDate') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Saat</label>
                            <select 
                                wire:model="preferredHour"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700"
                            >
                                @foreach(['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'] as $hour)
                                    <option value="{{ $hour }}">{{ $hour }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all
                        {{ $preferredTime === 'two_months' ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700 hover:border-gray-400' }}">
                        <input type="radio" name="preferredTime" wire:model.live="preferredTime" value="two_months" class="w-5 h-5 text-primary">
                        <span class="font-medium">İki ay içinde</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all
                        {{ $preferredTime === 'six_months' ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700 hover:border-gray-400' }}">
                        <input type="radio" name="preferredTime" wire:model.live="preferredTime" value="six_months" class="w-5 h-5 text-primary">
                        <span class="font-medium">Altı ay içinde</span>
                    </label>

                    <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all
                        {{ $preferredTime === 'just_looking' ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700 hover:border-gray-400' }}">
                        <input type="radio" name="preferredTime" wire:model.live="preferredTime" value="just_looking" class="w-5 h-5 text-primary">
                        <span class="font-medium">Sadece fiyat bakıyorum</span>
                    </label>
                </div>
            </div>
        @endif

        {{-- Step 4: Contact Info (Name & Email) --}}
        @if($currentStep === 4)
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Bilgileriniz</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Hizmet alacak kişiye ait bilgiler</p>

                <div class="space-y-4">
                    {{-- Location Info (Clean Layout - No Card) --}}
                    <div class="mb-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İl *</label>
                                <select wire:model.live="cityId" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800">
                                    <option value="">Seçiniz</option>
                                    @foreach($this->cities as $city) <option value="{{ $city->id }}">{{ $city->name }}</option> @endforeach
                                </select>
                                @error('cityId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İlçe</label>
                                <select wire:model.live="districtId" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800" {{ $this->districts->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">Seçiniz</option>
                                    @foreach($this->districts as $district) <option value="{{ $district->id }}">{{ $district->name }}</option> @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adres Detayı</label>
                                <textarea wire:model="address" rows="2" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800" placeholder="Açık adres..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İsim Soyisim *</label>
                        <input 
                            type="text" 
                            wire:model="contactName"
                            placeholder="Ad Soyad"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800"
                        >
                        @error('contactName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta</label>
                        <input 
                            type="email" 
                            wire:model="email"
                            placeholder="ornek@email.com"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800"
                        >
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-400 mt-1">Teklifleri e-posta ile de almak için girin.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Step 5: Phone Verification --}}
        @if($currentStep === 5)
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Cep telefonun?</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Numaranı doğruladıktan sonra talebin yayınlanacak.</p>

                @if(!$phoneVerified)
                    {{-- Phone Input --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefon Numarası *</label>
                        <div class="flex gap-2">
                            <span class="px-4 py-3 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                <span>🇹🇷</span> +90
                            </span>
                            <input 
                                type="tel" 
                                wire:model="phone"
                                placeholder="501 234 56 78"
                                maxlength="10"
                                class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800"
                                {{ $otpSent ? 'disabled' : '' }}
                            >
                        </div>
                        @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    @if(!$otpSent)
                        {{-- OTP Error (show before button) --}}
                        @if($otpError)
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                                {{ $otpError }}
                            </div>
                        @endif

                        <button 
                            wire:click="sendOtp"
                            wire:loading.attr="disabled"
                            class="w-full py-3 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-colors disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="sendOtp">Doğrulama Kodu Gönder</span>
                            <span wire:loading wire:target="sendOtp">Gönderiliyor...</span>
                        </button>

                        {{-- Info Box --}}
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl flex items-start gap-3">
                            <span class="material-symbols-outlined text-gray-400">info</span>
                            <p class="text-xs text-gray-500">Numaranı rezervasyonu kabul eden hizmet verenle paylaşacağız.</p>
                        </div>
                    @else
                        {{-- OTP Input --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doğrulama Kodu</label>
                            <input 
                                type="text" 
                                wire:model="otpCode"
                                placeholder="6 haneli kod"
                                maxlength="6"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary text-center text-2xl tracking-widest bg-white dark:bg-gray-800"
                            >
                            @error('otpCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        @if($otpError)
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                                {{ $otpError }}
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <button 
                                wire:click="verifyOtp"
                                wire:loading.attr="disabled"
                                class="flex-1 py-3 bg-primary text-white font-semibold rounded-xl hover:bg-primary/90 transition-colors"
                            >
                                <span wire:loading.remove wire:target="verifyOtp">Doğrula</span>
                                <span wire:loading wire:target="verifyOtp">Doğrulanıyor...</span>
                            </button>
                            <button 
                                wire:click="changePhone"
                                class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                Değiştir
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 mt-3 text-center">
                            Kod gelmedi mi? 
                            @if($resendCountdown > 0)
                                <span class="text-gray-400">{{ $resendCountdown }}s bekleyin</span>
                            @else
                                <button wire:click="sendOtp" class="text-primary font-medium">Tekrar Gönder</button>
                            @endif
                        </p>
                    @endif
                @else
                    <div class="space-y-6">
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-green-600">verified</span>
                                <div>
                                    <p class="font-semibold text-green-800 dark:text-green-400">Telefon Doğrulandı</p>
                                    <p class="text-sm text-green-600 dark:text-green-500">+90 {{ $phone }}</p>
                                </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 mt-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Talep Özeti</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Son kez kontrol edip onaylayın.</p>

                        <div class="grid md:grid-cols-2 gap-8">
                            {{-- Left Column: Service Details --}}
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-bold text-gray-900 dark:text-white">Hizmet Detayları</h3>
                                    <button wire:click="goToStep(1)" class="text-primary hover:text-primary-600 text-sm font-medium flex items-center gap-1">
                                        <span class="material-symbols-outlined text-lg">edit</span> Düzenle
                                    </button>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">HİZMET TÜRÜ</div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $this->selectedService->name }}</div>
                                    </div>

                                    @if($this->subServices->count() > 0)
                                    <div>
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">SEÇİLEN HİZMETLER</div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            @foreach($this->subServices as $sub)
                                                {{ $sub->name }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <div>
                                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">İHTİYAÇ DETAYI</div>
                                        <div class="font-medium text-gray-900 dark:text-white text-sm bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                            {{ $description ?: 'Açıklama girilmedi.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Contact & Location --}}
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-bold text-gray-900 dark:text-white">İletişim & Konum</h3>
                                    <button wire:click="goToStep(4)" class="text-primary hover:text-primary-600 text-sm font-medium flex items-center gap-1">
                                        <span class="material-symbols-outlined text-lg">edit</span> Düzenle
                                    </button>
                                </div>

                                <div class="space-y-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center flex-shrink-0 text-blue-600 dark:text-blue-400">
                                            <span class="material-symbols-outlined">person</span>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-0.5">Yetkili Kişi</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $contactName }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center flex-shrink-0 text-indigo-600 dark:text-indigo-400">
                                            <span class="material-symbols-outlined">call</span>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-0.5">Telefon</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">+90 {{ $phone }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center flex-shrink-0 text-orange-600 dark:text-orange-400">
                                            <span class="material-symbols-outlined">location_on</span>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-0.5">Konum</div>
                                            <div class="font-semibold text-gray-900 dark:text-white">
                                                @php
                                                    $city = \App\Models\Location::find($cityId);
                                                    $district = \App\Models\Location::find($districtId);
                                                @endphp
                                                {{ $district ? $district->name . ', ' : '' }}{{ $city ? $city->name : '' }}
                                            </div>
                                            @if($address)
                                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($address, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                     <div class="flex items-start gap-4 pl-[3.5rem]">
                                        <div class="flex items-center gap-2 text-primary font-medium">
                                            <span class="material-symbols-outlined text-xl">verified_user</span>
                                            Güvenli Talep
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 pl-[3.5rem]">
                                        <p class="text-[10px] leading-tight text-gray-400 dark:text-gray-500">
                                            Talebi gönder tuşuna basarak <a href="/kullanici-sozlesmesi" class="hover:text-primary underline">Kullanıcı Sözleşmesi</a>'ni kabul ediyorum ve <a href="/gizlilik-politikasi" class="hover:text-primary underline">Gizlilik Politikası</a>'nı okudum, anladım.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>


                                
                                {{-- Photos Section (Full Width Bottom) --}}
                                @if(count($photos) > 0)
                                <div class="md:col-span-2 border-t border-gray-200 dark:border-gray-700 pt-8 mb-16">
                                    <div class="flex justify-between items-end mb-6">
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white text-lg">Yüklenen Fotoğraflar</h4>
                                            <p class="text-sm text-gray-500 mt-1">Hizmet verene iletilecek görseller.</p>
                                        </div>
                                        <button type="button" wire:click="$set('currentStep', 1)" class="text-primary text-sm font-semibold hover:underline flex items-center gap-1 bg-primary/5 px-3 py-1.5 rounded-lg hover:bg-primary/10 transition-colors">
                                            <span class="material-symbols-outlined text-sm">edit</span> Düzenle
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                        @foreach($photos as $photo)
                                        <div class="relative group aspect-square">
                                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover rounded-2xl border-2 border-gray-100 dark:border-gray-700 shadow-sm transition-transform duration-300 group-hover:scale-[1.02]">
                                            <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-black/5 group-hover:ring-black/10"></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="md:col-span-2 mb-24"></div> {{-- Spacer if no photos --}}
                                @endif



                    </div>
                @endif
            </div>
        @endif

    {{-- Navigation Button (Fixed at Bottom) --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 p-4 z-50 shadow-[0_-4px_16px_-4px_rgba(0,0,0,0.1)]">
        <div class="max-w-3xl mx-auto flex flex-col sm:flex-row gap-3 sm:justify-end">
            {{-- Back Button --}}
            @if($currentStep > 1)
                <button 
                    wire:click="prevStep"
                    class="w-full sm:w-auto px-6 py-3.5 text-gray-700 dark:text-gray-300 font-semibold bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition-colors order-2 sm:order-1 flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Geri
                </button>
            @endif

            {{-- Next / Submit Button --}}
            @if($currentStep < $totalSteps)
                <button 
                    wire:click="nextStep"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-8 py-3.5 bg-primary text-white rounded-xl font-bold text-lg hover:bg-primary/90 transition-all shadow-lg hover:shadow-primary/30 disabled:opacity-50 flex items-center justify-center gap-2 order-1 sm:order-2"
                >
                    <span wire:loading.remove wire:target="nextStep">Devam Et</span>
                    <span wire:loading.remove wire:target="nextStep" class="material-symbols-outlined font-bold">arrow_forward</span>
                    <span wire:loading wire:target="nextStep">Yükleniyor...</span>
                </button>
            @else
                @if($phoneVerified)
                <button 
                    type="button"
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto min-w-[200px] px-10 py-3.5 bg-blue-600 text-white rounded-xl font-bold text-lg hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 order-1 sm:order-2"
                >
                    <span wire:loading.remove wire:target="submit">Talebi Gönder</span>
                    <span wire:loading.remove wire:target="submit" class="material-symbols-outlined font-bold">send</span>
                    <span wire:loading wire:target="submit">Gönderiliyor...</span>
                </button>
                @else
                <button 
                    type="button"
                    disabled
                    title="Önce telefon numaranızı doğrulayın"
                    class="w-full sm:w-auto min-w-[200px] px-10 py-3.5 bg-gray-300 text-gray-500 rounded-xl font-bold text-lg cursor-not-allowed flex items-center justify-center gap-2 order-1 sm:order-2"
                >
                    <span>Telefonu Doğrula</span>
                    <span class="material-symbols-outlined font-bold">lock</span>
                </button>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 md:p-8">
    {{-- Progress Steps --}}
    <div class="flex items-center justify-center mb-8">
        @for($i = 1; $i <= $totalSteps; $i++)
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm
                    {{ $i < $currentStep ? 'bg-green-500 text-white' : 
                       ($i === $currentStep ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500') }}">
                    @if($i < $currentStep)
                        <span class="material-symbols-outlined text-lg">check</span>
                    @else
                        {{ $i }}
                    @endif
                </div>
                @if($i < $totalSteps)
                    <div class="w-12 h-1 mx-1 {{ $i < $currentStep ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
                @endif
            </div>
        @endfor
    </div>

    {{-- Step 1: Select Service --}}
    @if($currentStep === 1)
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 text-center">Hangi hizmete ihtiyacınız var?</h3>
            <p class="text-gray-500 text-sm text-center mb-6">Hizmet kategorisini seçin</p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($this->services as $service)
                    <label class="flex flex-col items-center gap-2 p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-primary
                        {{ $serviceId == $service->id ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700' }}">
                        <input 
                            type="radio" 
                            name="serviceId"
                            wire:model.live="serviceId" 
                            value="{{ $service->id }}"
                            class="hidden"
                        >
                        @if($service->icon)
                        <span class="material-symbols-outlined text-3xl {{ $serviceId == $service->id ? 'text-primary' : 'text-gray-400' }}">{{ $service->icon }}</span>
                        @endif
                        <span class="font-medium text-sm text-center {{ $serviceId == $service->id ? 'text-primary' : 'text-gray-700 dark:text-gray-300' }}">{{ $service->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('serviceId') <span class="text-red-500 text-sm mt-2 block text-center">{{ $message }}</span> @enderror
        </div>
    @endif

    {{-- Step 2: Select Sub-Service --}}
    @if($currentStep === 2)
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 text-center">Alt hizmet seçin</h3>
            <p class="text-gray-500 text-sm text-center mb-6">Daha spesifik ihtiyacınızı belirtin</p>

            @if($this->subServices->count() > 0)
                <div class="space-y-2 max-h-80 overflow-y-auto">
                    @foreach($this->subServices as $subService)
                        <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all hover:border-primary
                            {{ $subServiceId == $subService->id ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700' }}">
                            <input 
                                type="radio" 
                                name="subServiceId"
                                wire:model.live="subServiceId" 
                                value="{{ $subService->id }}"
                                class="hidden"
                            >
                            <span class="font-medium {{ $subServiceId == $subService->id ? 'text-primary' : 'text-gray-700 dark:text-gray-300' }}">{{ $subService->name }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <span class="material-symbols-outlined text-4xl mb-2">check_circle</span>
                    <p>Alt hizmet seçimi gerekmemektedir.</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Step 3: Select City & District --}}
    @if($currentStep === 3)
        <div>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2 text-center">Neredesiniz?</h3>
            <p class="text-gray-500 text-sm text-center mb-6">Hizmetin yapılacağı konumu seçin</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İl *</label>
                    <select 
                        wire:model.live="cityId"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800 @error('cityId') border-red-500 @enderror"
                    >
                        <option value="">İl seçin...</option>
                        @foreach($this->cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('cityId') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if($cityId && $this->districts->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İlçe</label>
                    <select 
                        wire:model.live="districtId"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary bg-white dark:bg-gray-800"
                    >
                        <option value="">İlçe seçin (opsiyonel)...</option>
                        @foreach($this->districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Navigation Buttons --}}
    <div class="mt-8">
        @if($currentStep < $totalSteps)
            <button 
                wire:click="nextStep"
                wire:loading.attr="disabled"
                class="w-full py-4 bg-primary text-white rounded-xl font-semibold text-lg hover:bg-primary/90 transition-colors disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="nextStep">Devam</span>
                <span wire:loading wire:target="nextStep">Yükleniyor...</span>
            </button>
        @else
            <button 
                wire:click="submit"
                wire:loading.attr="disabled"
                class="w-full py-4 bg-primary text-white rounded-xl font-semibold text-lg hover:bg-primary/90 transition-colors disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="submit">Teklif Al</span>
                <span wire:loading wire:target="submit">Yönlendiriliyor...</span>
            </button>
        @endif

        @if($currentStep > 1)
            <button 
                wire:click="prevStep"
                class="w-full mt-2 py-3 text-gray-600 dark:text-gray-400 font-medium hover:text-gray-800 transition-colors"
            >
                ← Geri
            </button>
        @endif
    </div>
</div>

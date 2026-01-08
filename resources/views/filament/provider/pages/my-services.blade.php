<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Hizmet profilleri</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Proflin üzerinden istediğin hizmeti ekleyebilir, düzenleyebilir veya silebilirsin.
                    </p>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-bold text-primary-600">{{ $this->selectedServicesCount }}</span>
                    <span class="text-gray-500 text-sm block">Seçili Hizmet</span>
                </div>
            </div>
        </div>

        {{-- Service List --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($this->availableServices as $service)
                <label 
                    class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors"
                    wire:key="service-{{ $service->id }}"
                >
                    <div class="flex items-center gap-4">
                        <input 
                            type="checkbox" 
                            wire:model.live="selectedServices" 
                            value="{{ $service->id }}"
                            class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                        />
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $service->name }}</span>
                            @if($service->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($service->description, 60) }}</p>
                            @endif
                        </div>
                    </div>
                    <x-heroicon-m-chevron-right class="w-5 h-5 text-gray-400" />
                </label>
            @endforeach
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end">
            <button 
                wire:click="save"
                wire:loading.attr="disabled"
                class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors disabled:opacity-50 flex items-center gap-2"
            >
                <x-heroicon-s-check class="w-5 h-5" />
                <span wire:loading.remove wire:target="save">Değişiklikleri Kaydet</span>
                <span wire:loading wire:target="save">Kaydediliyor...</span>
            </button>
        </div>

        {{-- Info Box --}}
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="flex gap-3">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <p class="font-medium">Hizmet seçimi neden önemli?</p>
                    <p class="mt-1">Seçtiğiniz hizmetlere uygun iş fırsatları size gösterilecek. Ne kadar fazla hizmet seçerseniz, o kadar fazla iş fırsatı görürsünüz.</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

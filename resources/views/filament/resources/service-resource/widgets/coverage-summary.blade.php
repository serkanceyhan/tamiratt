<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                Kapsama Alan Özeti
            </div>
        </x-slot>
        
        @php
            $cities = $this->getCities();
        @endphp
        
        @if($cities->isEmpty())
            <div class="rounded-lg bg-gray-50 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                <p class="mt-2 text-sm text-gray-600">Henüz kapsama alanı tanımlanmamış</p>
                <p class="mt-1 text-xs text-gray-500">Yukarıdaki "Kapsama Alanını Yönet" butonuna tıklayarak başlayın</p>
            </div>
        @else
            <div class="space-y-2">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        <strong>{{ $cities->count() }}</strong> il, 
                        <strong>{{ $cities->sum('count') }}</strong> ilçede aktif
                    </p>
                </div>
                
                @foreach($cities as $cityData)
                    <details class="group rounded-lg border border-gray-200 bg-white">
                        <summary class="flex cursor-pointer items-center justify-between p-4 hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-gray-400 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                <span class="font-medium text-gray-900">{{ $cityData['city']->name }}</span>
                            </div>
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                {{ $cityData['count'] }} ilçe
                            </span>
                        </summary>
                        
                        <div class="border-t border-gray-200 bg-gray-50 p-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2">
                                @foreach($cityData['districts'] as $district)
                                    <div class="rounded bg-white px-3 py-2 text-sm text-gray-700">
                                        {{ $district->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

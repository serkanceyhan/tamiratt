<x-wizard-layout title="Talep Oluşturuldu - Ta'miratt">
<div class="min-h-screen bg-gradient-to-b from-green-50 to-white py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            {{-- Success Icon --}}
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-green-600 text-5xl">check_circle</span>
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mb-3">Talebiniz Alındı!</h1>
            <p class="text-gray-600 mb-8">
                Talebiniz başarıyla oluşturuldu. Bölgenizdeki hizmet sağlayıcılar tarafından değerlendirilecek.
            </p>

            {{-- Request Summary --}}
            @if(isset($serviceRequest))
            <div class="bg-gray-50 rounded-xl p-6 text-left mb-8">
                <h3 class="font-semibold text-gray-800 mb-4">Talep Özeti</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Talep No:</dt>
                        <dd class="font-medium">#{{ $serviceRequest->id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Hizmet:</dt>
                        <dd class="font-medium">{{ $serviceRequest->service?->name ?? 'Belirtilmedi' }}</dd>
                    </div>
                    @if($serviceRequest->subService)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Alt Hizmet:</dt>
                        <dd class="font-medium">{{ $serviceRequest->subService?->name ?? 'Belirtilmedi' }}</dd>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Konum:</dt>
                        <dd class="font-medium">{{ $serviceRequest->location?->name ?? 'Belirtilmedi' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Tercih Edilen Tarih:</dt>
                        <dd class="font-medium">
                            {{ $serviceRequest->preferred_date ? $serviceRequest->preferred_date->format('d.m.Y') : 'Esnek' }}
                        </dd>
                    </div>
                </dl>
            </div>
            @endif

            {{-- What's Next --}}
            <div class="text-left mb-8">
                <h3 class="font-semibold text-gray-800 mb-4">Bundan Sonra Ne Olacak?</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary">search</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Talebiniz İnceleniyor</p>
                            <p class="text-sm text-gray-500">Bölgenizdeki hizmet sağlayıcılar talebinizi görüyor.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary">local_offer</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Teklifler Alacaksınız</p>
                            <p class="text-sm text-gray-500">Hizmet sağlayıcılar size fiyat teklifi gönderecek.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary">handshake</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Seçiminizi Yapın</p>
                            <p class="text-sm text-gray-500">Size en uygun teklifi seçip hizmet alın.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800 mb-8">
                <span class="material-symbols-outlined text-sm align-middle mr-1">info</span>
                Teklifler ve güncellemeler için telefon numaranıza SMS gönderilecektir.
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="/" class="px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </div>
</div>
</x-wizard-layout>

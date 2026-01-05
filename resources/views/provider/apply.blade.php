<x-landing-layout>
    <x-slot:head>
        <title>Hizmet Veren Başvurusu | ta'miratt</title>
        <meta name="description" content="Tamiratt pazaryerine hizmet veren olarak katılın. Başvuru formunu doldurun, onay sürecini tamamlayın ve iş fırsatlarından yararlanın.">
    </x-slot:head>

    <x-landing.header />

    <main class="flex-grow py-12 bg-gray-50 dark:bg-background-dark">
        <div class="max-w-3xl mx-auto px-6">
            {{-- Header --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800 mb-6">
                    <span class="material-symbols-outlined text-primary text-lg">handyman</span>
                    <span class="text-sm font-semibold text-primary">Hizmet Veren Programı</span>
                </div>
                <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Pazaryerimize Katılın
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                    Ofis mobilyası tamir alanında uzmanıysanız, binlerce iş fırsatına erişin. Başvurunuz 24 saat içinde değerlendirilir.
                </p>
            </div>

            {{-- Form --}}
            <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-xl p-8">
                @if($errors->has('error'))
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-300">
                        {{ $errors->first('error') }}
                    </div>
                @endif

                <form action="{{ route('provider.apply.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    {{-- Section 1: Personal Info --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                            Kişisel Bilgiler
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ad Soyad *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary @error('name') border-red-500 @enderror">
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefon *</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="05XX XXX XX XX"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary @error('phone') border-red-500 @enderror">
                                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta *</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror">
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Company Info --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                            Firma Bilgileri
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Firma Adı *</label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary @error('company_name') border-red-500 @enderror">
                                @error('company_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vergi Numarası</label>
                                <input type="text" name="tax_number" value="{{ old('tax_number') }}"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Service Categories --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                            Uzmanlık Alanları *
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Hangi hizmetlerde uzmanlaştığınızı seçin. Bu kategorilere uygun iş fırsatları size gösterilecektir.</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($services as $service)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary cursor-pointer transition-colors @if(in_array($service->id, old('service_categories', []))) border-primary bg-blue-50 dark:bg-blue-900/20 @endif">
                                    <input type="checkbox" name="service_categories[]" value="{{ $service->id }}"
                                        {{ in_array($service->id, old('service_categories', [])) ? 'checked' : '' }}
                                        class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                                    <div>
                                        <span class="material-symbols-outlined text-primary text-lg">{{ $service->icon ?? 'build' }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('service_categories')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Section 4: Service Areas --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold">4</span>
                            Hizmet Bölgeleri *
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Hangi bölgelerde hizmet verebileceğinizi seçin.</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($cities as $city)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-primary cursor-pointer transition-colors @if(in_array($city->id, old('service_areas', []))) border-primary bg-blue-50 dark:bg-blue-900/20 @endif">
                                    <input type="checkbox" name="service_areas[]" value="{{ $city->id }}"
                                        {{ in_array($city->id, old('service_areas', [])) ? 'checked' : '' }}
                                        class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $city->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('service_areas')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Section 5: Documents --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-bold">5</span>
                            Belgeler *
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Vergi levhası, kimlik veya ustalık belgenizi yükleyin. (PDF, JPG, PNG - Max 5MB)</p>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-primary transition-colors">
                            <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" required
                                class="hidden" id="documents-input">
                            <label for="documents-input" class="cursor-pointer">
                                <span class="material-symbols-outlined text-4xl text-gray-400 mb-2">cloud_upload</span>
                                <p class="text-gray-600 dark:text-gray-400">Dosyaları sürükleyin veya <span class="text-primary font-medium">buraya tıklayın</span></p>
                            </label>
                        </div>
                        @error('documents')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        @error('documents.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Terms --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="terms_accepted" value="1" required
                                class="mt-1 w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                <a href="#" class="text-primary hover:underline">Kullanım Koşulları</a> ve 
                                <a href="#" class="text-primary hover:underline">Gizlilik Politikası</a>'nı okudum, kabul ediyorum.
                            </span>
                        </label>
                        @error('terms_accepted')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="w-full py-4 px-6 bg-primary hover:bg-blue-700 text-white font-bold text-lg rounded-lg shadow-lg hover:shadow-primary/50 transition-all flex items-center justify-center gap-2">
                        <span>Başvuruyu Gönder</span>
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>
            </div>

            {{-- Info Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <div class="bg-white dark:bg-surface-dark rounded-xl p-6 text-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-green-600">verified</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Hızlı Onay</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Başvurular 24 saat içinde değerlendirilir.</p>
                </div>
                <div class="bg-white dark:bg-surface-dark rounded-xl p-6 text-center">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-blue-600">trending_up</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Yüksek Potansiyel</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Aylık binlerce iş talebi.</p>
                </div>
                <div class="bg-white dark:bg-surface-dark rounded-xl p-6 text-center">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-purple-600">support_agent</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">Destek</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">7/24 teknik destek.</p>
                </div>
            </div>
        </div>
    </main>

    <x-landing.footer />
</x-landing-layout>

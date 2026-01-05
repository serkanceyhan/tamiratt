<x-landing-layout>
    <x-slot:head>
        <title>{{ T('provider.apply_title') }} | ta'miratt</title>
        <meta name="description" content="{{ T('provider.apply_subtitle') }}">
    </x-slot:head>

    <x-landing.header />

    <main class="flex-grow">
        {{-- Hero Section --}}
        <section class="bg-gradient-to-br from-blue-50 to-white dark:from-gray-900 dark:to-background-dark py-16 lg:py-24">
            <div class="max-w-[1280px] mx-auto px-6">
                <div class="max-w-3xl mx-auto text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-6">
                        <span class="material-symbols-outlined text-primary text-lg">handyman</span>
                        <span class="text-sm font-semibold text-primary">{{ T('provider.badge_text') }}</span>
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-black text-gray-900 dark:text-white mb-6 leading-tight">
                        {{ T('provider.apply_title') }}
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        {{ T('provider.apply_subtitle') }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Form Section --}}
        <section class="py-16 bg-gray-50 dark:bg-background-dark">
            <div class="max-w-[1280px] mx-auto px-6">
                <div class="max-w-3xl mx-auto">
                    @if($errors->has('error'))
                        <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 flex items-center gap-3">
                            <span class="material-symbols-outlined">error</span>
                            {{ $errors->first('error') }}
                        </div>
                    @endif

                    <form action="{{ route('provider.apply.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        {{-- Section 1: Personal Info --}}
                        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-lg p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">1</div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ T('provider.section_personal') }}</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ T('provider.field_name') }} *</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        class="w-full px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('name') border-red-500 @enderror"
                                        placeholder="Ahmet Yılmaz">
                                    @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ T('provider.field_phone') }} *</label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" required 
                                        class="w-full px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('phone') border-red-500 @enderror"
                                        placeholder="05XX XXX XX XX">
                                    @error('phone')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ T('provider.field_email') }} *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        class="w-full px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('email') border-red-500 @enderror"
                                        placeholder="ornek@firma.com">
                                    @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Company Info --}}
                        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-lg p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">2</div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ T('provider.section_company') }}</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ T('provider.field_company') }} *</label>
                                    <input type="text" name="company_name" value="{{ old('company_name') }}" required
                                        class="w-full px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('company_name') border-red-500 @enderror"
                                        placeholder="ABC Mobilya Tamir Ltd.">
                                    @error('company_name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ T('provider.field_tax') }}</label>
                                    <input type="text" name="tax_number" value="{{ old('tax_number') }}"
                                        class="w-full px-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                        placeholder="Opsiyonel">
                                </div>
                            </div>
                        </div>

                        {{-- Section 3: Service Categories --}}
                        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-lg p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">3</div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ T('provider.section_expertise') }} *</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ T('provider.section_expertise_desc') }}</p>
                                </div>
                            </div>
                            @if($services->count() > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($services as $service)
                                        <label class="relative flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-primary cursor-pointer transition-all group has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                            <input type="checkbox" name="service_categories[]" value="{{ $service->id }}"
                                                {{ in_array($service->id, old('service_categories', [])) ? 'checked' : '' }}
                                                class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                                            <span class="material-symbols-outlined text-primary text-xl">{{ $service->icon ?? 'build' }}</span>
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $service->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <span class="material-symbols-outlined text-4xl mb-2">category</span>
                                    <p>{{ T('provider.no_categories') }}</p>
                                </div>
                            @endif
                            @error('service_categories')<p class="mt-4 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Section 4: Service Areas --}}
                        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-lg p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">4</div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ T('provider.section_areas') }} *</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ T('provider.section_areas_desc') }}</p>
                                </div>
                            </div>
                            @if($cities->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach($cities as $city)
                                        <label class="relative flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-primary cursor-pointer transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                            <input type="checkbox" name="service_areas[]" value="{{ $city->id }}"
                                                {{ in_array($city->id, old('service_areas', [])) ? 'checked' : '' }}
                                                class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $city->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <span class="material-symbols-outlined text-4xl mb-2">location_on</span>
                                    <p>{{ T('provider.no_areas') }}</p>
                                </div>
                            @endif
                            @error('service_areas')<p class="mt-4 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Section 5: Documents --}}
                        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-lg p-8">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">5</div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ T('provider.section_documents') }} *</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ T('provider.section_documents_desc') }}</p>
                                </div>
                            </div>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-primary transition-colors bg-gray-50 dark:bg-gray-800/50">
                                <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" required
                                    class="hidden" id="documents-input"
                                    onchange="updateFileList(this)">
                                <label for="documents-input" class="cursor-pointer block">
                                    <span class="material-symbols-outlined text-5xl text-gray-400 mb-3">cloud_upload</span>
                                    <p class="text-gray-600 dark:text-gray-400 mb-1">{{ T('provider.upload_text') }}</p>
                                    <span class="text-primary font-semibold hover:underline">{{ T('provider.upload_click') }}</span>
                                </label>
                                <div id="file-list" class="mt-4 text-sm text-gray-600"></div>
                            </div>
                            @error('documents')<p class="mt-4 text-sm text-red-600">{{ $message }}</p>@enderror
                            @error('documents.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        {{-- Terms & Submit --}}
                        <div class="bg-white dark:bg-surface-dark rounded-2xl shadow-lg p-8">
                            <label class="flex items-start gap-4 cursor-pointer mb-8">
                                <input type="checkbox" name="terms_accepted" value="1" required
                                    class="mt-1 w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary">
                                <span class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                    <a href="/sozlesme/kullanim-kosullari" class="text-primary hover:underline font-medium">Kullanım Koşulları</a> ve 
                                    <a href="/sozlesme/gizlilik-politikasi" class="text-primary hover:underline font-medium">Gizlilik Politikası</a>'nı okudum, kabul ediyorum.
                                </span>
                            </label>
                            @error('terms_accepted')<p class="mb-4 text-sm text-red-600">{{ $message }}</p>@enderror

                            <button type="submit" class="w-full py-4 px-6 bg-primary hover:bg-blue-700 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-primary/30 transition-all flex items-center justify-center gap-3">
                                <span>{{ T('provider.submit_button') }}</span>
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        {{-- Benefits Section --}}
        <section class="py-20 bg-white dark:bg-surface-dark">
            <div class="max-w-[1280px] mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-green-600 text-3xl">verified</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3">{{ T('provider.benefit_fast') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ T('provider.benefit_fast_desc') }}</p>
                    </div>
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-blue-600 text-3xl">trending_up</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3">{{ T('provider.benefit_high') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ T('provider.benefit_high_desc') }}</p>
                    </div>
                    <div class="text-center p-8">
                        <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <span class="material-symbols-outlined text-purple-600 text-3xl">support_agent</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-3">{{ T('provider.benefit_support') }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ T('provider.benefit_support_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-landing.footer />

    <script>
        function updateFileList(input) {
            const fileList = document.getElementById('file-list');
            if (input.files.length > 0) {
                const names = Array.from(input.files).map(f => f.name).join(', ');
                fileList.innerHTML = '<span class="text-green-600 font-medium">✓ ' + input.files.length + ' dosya seçildi:</span> ' + names;
            } else {
                fileList.innerHTML = '';
            }
        }
    </script>
</x-landing-layout>

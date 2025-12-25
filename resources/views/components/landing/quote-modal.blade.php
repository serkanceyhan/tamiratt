<div 
    x-show="quoteModalOpen" 
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Background backdrop -->
    <div 
        x-show="quoteModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm" 
        @click="quoteModalOpen = false"
    ></div>

    <!-- Modal panel -->
    <div 
        x-show="quoteModalOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-lg bg-white dark:bg-surface-dark rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto"
    >
        <div class="p-6 sm:p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white" id="modal-title">Ücretsiz Teklif Al</h2>
                <button 
                    @click="quoteModalOpen = false"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form 
                x-data="{ 
                    loading: false, 
                    message: '', 
                    isSuccess: false,
                    isDragging: false,
                    file: null,
                    formData: {
                        company_name: '',
                        name: '',
                        email: '',
                        service_type: '{{ \App\Models\Service::where("is_active", true)->orderBy("name")->first()->name ?? "Hizmet Seçiniz" }}',
                        message: ''
                    },
                    submitForm() {
                        this.loading = true;
                        this.message = '';
                        this.isSuccess = false;

                        const data = new FormData();
                        data.append('company_name', this.formData.company_name);
                        data.append('name', this.formData.name);
                        data.append('email', this.formData.email);
                        data.append('service_type', this.formData.service_type);
                        data.append('message', this.formData.message);
                        if (this.file) {
                            data.append('file', this.file);
                        }

                        axios.post('/quote', data, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(response => {
                            this.isSuccess = true;
                            this.message = response.data.message;
                            this.formData = { company_name: '', name: '', email: '', service_type: '{{ \App\Models\Service::where("is_active", true)->orderBy("name")->first()->name ?? "" }}', message: '' };
                            this.file = null;
                            setTimeout(() => {
                                quoteModalOpen = false;
                                this.message = ''; // Reset message so it doesn't show next time immediately
                            }, 3000);
                        })
                        .catch(error => {
                            this.isSuccess = false;
                            this.message = error.response?.data?.message || 'Bir hata oluştu. Lütfen tekrar deneyin.';
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                    }
                }"
                @submit.prevent="submitForm()" 
                class="space-y-4"
            >
                <div x-show="message" x-text="message" :class="isSuccess ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" class="p-3 rounded-lg text-sm mb-4"></div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Şirket Adı</label>
                    <input 
                        x-model="formData.company_name"
                        required
                        type="text" 
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder-gray-400" 
                        placeholder="Örn: ABC A.Ş."
                    />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Ad Soyad</label>
                        <input 
                            x-model="formData.name"
                            required
                            type="text" 
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder-gray-400" 
                            placeholder="Can Demir"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">E-posta</label>
                        <input 
                            x-model="formData.email"
                            required
                            type="email" 
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder-gray-400" 
                            placeholder="can@sirket.com"
                        />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Hizmet Türü</label>
                    <select x-model="formData.service_type" class="w-full px-4 py-3 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all">
                        @foreach(\App\Models\Service::where('is_active', true)->orderBy('name')->get() as $service)
                            <option value="{{ $service->name }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Mesajınız / Mobilya Sayısı</label>
                    <textarea 
                        x-model="formData.message"
                        rows="3"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all placeholder-gray-400" 
                        placeholder="Yenilenmesini istediğiniz mobilyalar hakkında bilgi verin..."
                    ></textarea>
                </div>

                <!-- Drag & Drop File Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Fotoğraf Yükle (İsteğe Bağlı)</label>
                    <div 
                        class="relative w-full h-32 border-2 border-dashed rounded-lg flex flex-col items-center justify-center transition-colors cursor-pointer"
                        :class="{ 'border-primary bg-blue-50 dark:bg-blue-900/20': isDragging, 'border-gray-300 dark:border-gray-600 hover:border-primary': !isDragging }"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="isDragging = false; file = $event.dataTransfer.files[0]"
                        @click="$refs.fileInput.click()"
                    >
                        <input 
                            x-ref="fileInput"
                            type="file" 
                            class="hidden" 
                            accept="image/*"
                            @change="file = $event.target.files[0]"
                        >
                        
                        <template x-if="!file">
                            <div class="flex flex-col items-center text-center p-4">
                                <span class="material-symbols-outlined text-3xl text-gray-400 mb-2">cloud_upload</span>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-bold text-primary">Tıkla</span> veya sürükle bırak
                                </p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG (max. 5MB)</p>
                            </div>
                        </template>

                        <template x-if="file">
                            <div class="flex items-center gap-3 p-4 w-full">
                                <span class="material-symbols-outlined text-green-500 text-2xl">check_circle</span>
                                <div class="flex-1 min-w-0 text-left">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="file.name"></p>
                                    <p class="text-xs text-gray-500" x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                </div>
                                <button type="button" @click.stop="file = null" class="text-gray-400 hover:text-red-500">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
                
                <button 
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-lg transition-all shadow-lg hover:shadow-primary/30 mt-4 flex items-center justify-center gap-2"
                >
                    <span x-show="loading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                    <span x-text="loading ? 'Gönderiliyor...' : 'Teklifi Gönder'"></span>
                </button>
                <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Bilgileriniz güvenle saklanır. Ücretsiz keşif ve teklif sürecidir.
                </p>
            </form>
        </div>
    </div>
</div>

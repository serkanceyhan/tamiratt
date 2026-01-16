<div x-data="{ 
    lightboxOpen: false, 
    currentImage: '', 
    currentIndex: 0,
    images: {{ json_encode($photos->map(fn($p) => $p->getFullUrl())->values()) }},
    openLightbox(index) {
        this.currentIndex = index;
        this.currentImage = this.images[index];
        this.lightboxOpen = true;
    },
    nextImage() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.currentImage = this.images[this.currentIndex];
    },
    prevImage() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.currentImage = this.images[this.currentIndex];
    }
}">
    @if($photos && $photos->count() > 0)
        <div class="grid grid-cols-4 gap-4">
            @foreach($photos as $index => $photo)
                <button
                    type="button"
                    @click="openLightbox({{ $index }})"
                    class="block rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow group relative cursor-pointer w-full"
                    style="max-width: 200px; height: 200px;">
                    <img 
                        src="{{ $photo->hasGeneratedConversion('thumb') ? $photo->getUrl('thumb') : $photo->getUrl() }}" 
                        alt="Request Photo"
                        class="w-full h-full object-cover bg-gray-50 dark:bg-gray-900 group-hover:opacity-90 transition-opacity"
                        loading="lazy"
                    />
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </button>
            @endforeach
        </div>

        {{-- Lightbox Modal --}}
        <div 
            x-show="lightboxOpen" 
            x-cloak
            @keydown.escape.window="lightboxOpen = false"
            @keydown.arrow-left.window="prevImage()"
            @keydown.arrow-right.window="nextImage()"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4"
            style="display: none;">
            
            {{-- Close Button --}}
            <button 
                @click="lightboxOpen = false"
                class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            {{-- Previous Button --}}
            <button 
                @click="prevImage()"
                x-show="images.length > 1"
                class="absolute left-4 text-white hover:text-gray-300 transition-colors z-10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            {{-- Image --}}
            <img 
                :src="currentImage" 
                @click.stop
                class="max-h-full max-w-full object-contain"
                alt="Full size image"
            />

            {{-- Next Button --}}
            <button 
                @click="nextImage()"
                x-show="images.length > 1"
                class="absolute right-4 text-white hover:text-gray-300 transition-colors z-10">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            {{-- Image Counter --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white bg-black/50 px-4 py-2 rounded-full text-sm">
                <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
            </div>
        </div>
    @else
        <p class="text-sm text-gray-500 dark:text-gray-400">Fotoğraf eklenmemiş</p>
    @endif
</div>

@php
    $mediaItems = $record->getMedia('verification_documents');
@endphp

@if($mediaItems->isEmpty())
    <div class="text-sm text-gray-500 dark:text-gray-400">
        Belge yüklenmemiş.
    </div>
@else
    <style>
        .doc-preview-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            width: 100%;
        }
        @media (min-width: 768px) {
            .doc-preview-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>

    {{-- Main Lightbox Container --}}
    <div x-data="{ 
            activeImage: null,
            openLightbox(url) {
                this.activeImage = url;
                document.body.style.overflow = 'hidden'; 
            },
            closeLightbox() {
                this.activeImage = null;
                document.body.style.overflow = 'auto';
            }
        }"
        @keydown.escape.window="closeLightbox()"
        class="w-full"
    >
        <div class="doc-preview-grid">
            @foreach($mediaItems as $media)
                <div class="relative group border border-gray-200 dark:border-gray-700 rounded-lg p-2 bg-white dark:bg-gray-800 hover:shadow-md transition-all">
                    @if(Str::startsWith($media->mime_type, 'image/'))
                        {{-- Image Preview --}}
                        <div class="relative overflow-hidden rounded-md">
                            <img 
                                src="{{ $media->getUrl() }}" 
                                alt="{{ $media->file_name }}" 
                                class="w-full h-28 object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300 ease-in-out"
                                @click="openLightbox('{{ $media->getUrl() }}')"
                            >
                            <div class="absolute inset-0 pointer-events-none bg-black/0 group-hover:bg-black/5 transition-colors"></div>
                        </div>
                    @else
                        {{-- File Preview (PDF, etc.) --}}
                        <a href="{{ $media->getUrl() }}" target="_blank" class="block h-28 flex flex-col items-center justify-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition-colors text-center text-decoration-none group-hover:scale-105 transition-transform duration-300">
                            @if($media->mime_type === 'application/pdf')
                                <span class="material-symbols-outlined text-red-500 text-5xl mb-3">picture_as_pdf</span>
                            @else
                                <span class="material-symbols-outlined text-gray-500 text-5xl mb-3">description</span>
                            @endif
                            <span class="text-xs text-gray-600 dark:text-gray-300 truncate w-full px-1 font-medium">{{ $media->file_name }}</span>
                        </a>
                    @endif
                    
                    {{-- Download Button --}}
                    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                        <a href="{{ $media->getUrl() }}" download class="p-1.5 bg-white dark:bg-gray-700 rounded-full shadow-md text-gray-500 hover:text-primary block transform hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-sm">download</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Lightbox Overlay --}}
        <template x-teleport="body">
            <div 
                x-show="activeImage" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm p-4 md:p-10"
                @click="closeLightbox()"
            >
                {{-- Close Button --}}
                <button class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors p-2 z-[101]">
                    <span class="material-symbols-outlined text-4xl">close</span>
                </button>

                {{-- Image --}}
                <img 
                    :src="activeImage" 
                    @click.stop 
                    class="max-w-[90vw] max-h-[90vh] rounded-lg shadow-2xl object-contain animate-in fade-in zoom-in duration-300"
                >
            </div>
        </template>
    </div>
@endif

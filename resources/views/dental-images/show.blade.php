<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-image text-blue-600 mr-3"></i>
                    {{ $image->title ?? 'Dental Image' }}
                </h2>
                <p class="text-gray-600 mt-2">{{ $patient->full_name }} - {{ $image->captured_date->format('F d, Y') }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('patients.images.index', $patient) }}" class="btn-modern btn-secondary inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Gallery
                </a>
                <a href="{{ route('patients.images.download', [$patient, $image]) }}" download class="btn-modern btn-primary inline-flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Download
                </a>
            </div>
        </div>
    </x-slot>

    <!-- PhotoSwipe CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.css">

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Image Display -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-sm p-4 lg:p-6">
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl overflow-hidden relative" style="height: 75vh;">
                    <!-- Full Screen Button -->
                    <div class="absolute top-4 right-4 z-10">
                        <button onclick="openPhotoSwipe()" class="bg-blue-600 text-white rounded-lg p-3 shadow-lg hover:bg-blue-700 transition-all">
                            <i class="fas fa-expand mr-2"></i>
                            Full Screen
                        </button>
                    </div>

                    <!-- Image Container -->
                    <div class="w-full h-full flex items-center justify-center p-4">
                        <a href="{{ route('patients.images.download', [$patient, $image]) }}"
                           data-pswp-width="{{ $image->width }}"
                           data-pswp-height="{{ $image->height }}"
                           target="_blank"
                           rel="noopener noreferrer">
                            <img src="data:image/{{ pathinfo($image->file_name, PATHINFO_EXTENSION) }};base64,{{ base64_encode(Storage::disk('dental_images')->get($image->file_path)) }}"
                                 alt="{{ $image->title ?? 'Dental Image' }}"
                                 class="max-w-full max-h-full object-contain shadow-2xl cursor-pointer hover:opacity-90 transition-opacity"
                                 onclick="event.preventDefault(); openPhotoSwipe();">
                        </a>
                    </div>
                </div>

                <!-- Image Info Below -->
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-3">
                        <i class="fas fa-expand-arrows-alt text-blue-600"></i>
                        <div>
                            <div class="text-xs text-gray-500">Dimensions</div>
                            <div class="font-semibold text-gray-900">{{ $image->width }} × {{ $image->height }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-3">
                        <i class="fas fa-file text-green-600"></i>
                        <div>
                            <div class="text-xs text-gray-500">File Size</div>
                            <div class="font-semibold text-gray-900">{{ number_format($image->file_size / 1024, 2) }} KB</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-3">
                        <i class="fas fa-image text-purple-600"></i>
                        <div>
                            <div class="text-xs text-gray-500">Format</div>
                            <div class="font-semibold text-gray-900">{{ strtoupper(pathinfo($image->file_name, PATHINFO_EXTENSION)) }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-3">
                        <i class="far fa-calendar text-orange-600"></i>
                        <div>
                            <div class="text-xs text-gray-500">Uploaded</div>
                            <div class="font-semibold text-gray-900">{{ $image->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Details Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Details
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Image Type</label>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-x-ray text-xs mr-2"></i>
                            {{ ucwords(str_replace('_', ' ', $image->image_type)) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Captured Date</label>
                        <p class="text-sm text-gray-900">
                            <i class="far fa-calendar mr-1 text-blue-600"></i>
                            {{ $image->captured_date->format('F d, Y') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Patient</label>
                        <p class="text-sm text-gray-900">
                            <i class="fas fa-user mr-1 text-blue-600"></i>
                            {{ $patient->full_name }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cog text-blue-600 mr-2"></i>
                    Actions
                </h3>

                <div class="space-y-3">
                    <button onclick="openPhotoSwipe()" class="w-full btn-modern btn-primary text-sm flex items-center justify-center">
                        <i class="fas fa-search-plus mr-2"></i>
                        View Full Screen
                    </button>

                    <a href="{{ route('patients.images.download', [$patient, $image]) }}" download class="w-full btn-modern btn-secondary text-sm flex items-center justify-center">
                        <i class="fas fa-download mr-2"></i>
                        Download Original
                    </a>

                    <a href="{{ route('patients.dental-chart', $patient) }}" class="w-full btn-modern btn-secondary text-sm flex items-center justify-center">
                        <i class="fas fa-tooth mr-2"></i>
                        View Dental Chart
                    </a>

                    <form action="{{ route('patients.images.destroy', [$patient, $image]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-50 text-red-600 hover:bg-red-100 rounded-lg px-4 py-2 text-sm font-medium transition-colors flex items-center justify-center">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Image
                        </button>
                    </form>
                </div>
            </div>

            <!-- File Information -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-file text-blue-600 mr-2"></i>
                    File Info
                </h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">File Name:</span>
                        <span class="font-medium text-gray-900 text-right truncate ml-2" title="{{ $image->file_name }}">{{ Str::limit($image->file_name, 20) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">MIME Type:</span>
                        <span class="font-medium text-gray-900">{{ $image->mime_type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Size:</span>
                        <span class="font-medium text-gray-900">{{ number_format($image->file_size / 1024, 2) }} KB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dimensions:</span>
                        <span class="font-medium text-gray-900">{{ $image->width }} × {{ $image->height }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PhotoSwipe Markup -->
    <div id="photoswipe-gallery" style="display: none;">
        <a href="data:image/{{ pathinfo($image->file_name, PATHINFO_EXTENSION) }};base64,{{ base64_encode(Storage::disk('dental_images')->get($image->file_path)) }}"
           data-pswp-width="{{ $image->width }}"
           data-pswp-height="{{ $image->height }}">
        </a>
    </div>

    <!-- PhotoSwipe JS -->
    <script type="module">
        import PhotoSwipeLightbox from 'https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe-lightbox.esm.min.js';
        import PhotoSwipe from 'https://cdn.jsdelivr.net/npm/photoswipe@5.3.7/dist/photoswipe.esm.min.js';

        // Initialize PhotoSwipe
        const lightbox = new PhotoSwipeLightbox({
            gallery: '#photoswipe-gallery',
            children: 'a',
            pswpModule: PhotoSwipe,
            zoom: true,
            maxZoomLevel: 4,
            initialZoomLevel: 'fit',
            secondaryZoomLevel: 2,
            bgOpacity: 0.95,
            spacing: 0.1,
            allowPanToNext: false,
            closeOnVerticalDrag: false,
            pinchToClose: false,
            wheelToZoom: true,
            padding: { top: 20, bottom: 40, left: 20, right: 20 }
        });

        lightbox.init();

        // Global function to open PhotoSwipe
        window.openPhotoSwipe = function() {
            lightbox.loadAndOpen(0);
        };
    </script>
</x-app-sidebar-layout>

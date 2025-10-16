<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-images text-blue-600 mr-3"></i>
                    Image Gallery - {{ $patient->full_name }}
                </h2>
                <p class="text-gray-600 mt-2">View and manage patient X-rays, photos, and scans</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('patients.show', $patient) }}" class="btn-modern btn-secondary inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Patient
                </a>
                <a href="{{ route('patients.images.create', $patient) }}" class="btn-modern btn-primary inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Upload Images
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-6" x-data="imageGallery()">
        <!-- Filters -->
        <form method="GET" class="mb-6">
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <button type="button" @click="showFilters = !showFilters"
                        class="btn-modern btn-secondary text-sm">
                    <i class="fas fa-filter mr-2"></i>
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                </button>

                @if(request()->hasAny(['image_type', 'tooth_number', 'date_from', 'date_to', 'search']))
                    <a href="{{ route('patients.images.index', $patient) }}"
                       class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-times-circle mr-1"></i>
                        Clear Filters
                    </a>
                @endif

                <div class="ml-auto flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        Total: <span class="font-semibold">{{ $images->total() }}</span> images
                    </span>

                    <!-- View Toggle -->
                    <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                        <button type="button" @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-white shadow-sm' : ''"
                                class="px-3 py-1 rounded text-sm transition-all">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" @click="viewMode = 'list'"
                                :class="viewMode === 'list' ? 'bg-white shadow-sm' : ''"
                                class="px-3 py-1 rounded text-sm transition-all">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="showFilters" x-collapse class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search title, notes..."
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <!-- Image Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image Type</label>
                    <select name="image_type"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Types</option>
                        <option value="xray_intraoral" {{ request('image_type') == 'xray_intraoral' ? 'selected' : '' }}>Intraoral X-Ray</option>
                        <option value="xray_panoramic" {{ request('image_type') == 'xray_panoramic' ? 'selected' : '' }}>Panoramic X-Ray</option>
                        <option value="xray_bitewing" {{ request('image_type') == 'xray_bitewing' ? 'selected' : '' }}>Bitewing X-Ray</option>
                        <option value="xray_periapical" {{ request('image_type') == 'xray_periapical' ? 'selected' : '' }}>Periapical X-Ray</option>
                        <option value="cbct" {{ request('image_type') == 'cbct' ? 'selected' : '' }}>CBCT Scan</option>
                        <option value="photo_intraoral" {{ request('image_type') == 'photo_intraoral' ? 'selected' : '' }}>Intraoral Photo</option>
                        <option value="photo_extraoral" {{ request('image_type') == 'photo_extraoral' ? 'selected' : '' }}>Extraoral Photo</option>
                        <option value="other" {{ request('image_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>

                <!-- Apply Button -->
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="btn-modern btn-primary text-sm">
                        <i class="fas fa-search mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </div>
        </form>

        <!-- Image Grid/List -->
        @if($images->count() > 0)
            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($images as $image)
                    <div class="group relative bg-white rounded-xl border-2 border-gray-200 overflow-hidden hover:border-blue-400 hover:shadow-xl transition-all cursor-pointer"
                         data-image-id="{{ $image->id }}"
                         data-full-image="data:image/{{ pathinfo($image->file_name, PATHINFO_EXTENSION) }};base64,{{ base64_encode(Storage::disk('dental_images')->get($image->file_path)) }}"
                         data-title="{{ $image->title ?? $image->image_type_formatted }}"
                         data-type="{{ $image->image_type_formatted }}"
                         data-tooth="{{ $image->tooth_number }}"
                         data-date="{{ $image->captured_date->format('M d, Y') }}"
                         data-dentist="{{ $image->dentist ? $image->dentist->full_name : '' }}"
                         data-filesize="{{ $image->file_size_formatted }}"
                         data-dimensions="{{ $image->width }}x{{ $image->height }}"
                         @click="openLightboxFromElement($el)">

                        <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                            <img src="data:image/{{ pathinfo($image->file_name, PATHINFO_EXTENSION) }};base64,{{ base64_encode(Storage::disk('dental_images')->get($image->thumbnail_path ?? $image->file_path)) }}"
                                 alt="{{ $image->title ?? $image->image_type_formatted }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                 loading="lazy">

                            <!-- Image Type Badge -->
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-blue-600 text-white shadow-lg backdrop-blur-sm bg-opacity-90">
                                    <i class="fas fa-x-ray text-xs mr-1"></i>
                                    {{ Str::limit($image->image_type_formatted, 8) }}
                                </span>
                            </div>

                            <!-- Tooth Number Badge -->
                            @if($image->tooth_number)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-purple-600 text-white shadow-lg backdrop-blur-sm bg-opacity-90">
                                        <i class="fas fa-tooth text-xs mr-1"></i>
                                        #{{ $image->tooth_number }}
                                    </span>
                                </div>
                            @endif

                            <!-- Overlay on Hover -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-search-plus text-white text-4xl mb-2"></i>
                                    <p class="text-white text-sm font-semibold">Click to View</p>
                                </div>
                            </div>
                        </div>

                        <!-- Image Info -->
                        <div class="p-3 bg-gradient-to-br from-white to-gray-50">
                            <h3 class="font-semibold text-sm text-gray-900 truncate mb-1">
                                {{ $image->title ?? 'Untitled' }}
                            </h3>
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span>
                                    <i class="far fa-calendar mr-1"></i>
                                    {{ $image->captured_date->format('M d, Y') }}
                                </span>
                                <span class="font-medium">{{ $image->file_size_formatted }}</span>
                            </div>

                            @if($image->dentist)
                                <div class="text-xs text-gray-600 mb-2 truncate">
                                    <i class="fas fa-user-md mr-1 text-blue-600"></i>
                                    {{ $image->dentist->full_name }}
                                </div>
                            @endif

                            <!-- Quick Actions -->
                            <div class="flex items-center gap-2 mt-3">
                                <a href="{{ route('patients.images.show', [$patient, $image]) }}"
                                   onclick="event.stopPropagation()"
                                   class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-3 py-2 text-xs font-medium transition-colors shadow-sm">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Details
                                </a>
                                <form action="{{ route('patients.images.destroy', [$patient, $image]) }}"
                                      method="POST"
                                      onclick="event.stopPropagation()"
                                      onsubmit="return confirm('Are you sure you want to delete this image?');"
                                      class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg px-3 py-2 text-xs font-medium transition-colors shadow-sm">
                                        <i class="fas fa-trash mr-1"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- List View -->
            <div x-show="viewMode === 'list'" class="space-y-3">
                @foreach($images as $image)
                    <div class="group bg-white rounded-xl border-2 border-gray-200 overflow-hidden hover:border-blue-400 hover:shadow-lg transition-all p-4 flex gap-4 cursor-pointer"
                         data-image-id="{{ $image->id }}"
                         data-full-image="data:image/{{ pathinfo($image->file_name, PATHINFO_EXTENSION) }};base64,{{ base64_encode(Storage::disk('dental_images')->get($image->file_path)) }}"
                         data-title="{{ $image->title ?? $image->image_type_formatted }}"
                         data-type="{{ $image->image_type_formatted }}"
                         data-tooth="{{ $image->tooth_number }}"
                         data-date="{{ $image->captured_date->format('M d, Y') }}"
                         data-dentist="{{ $image->dentist ? $image->dentist->full_name : '' }}"
                         data-filesize="{{ $image->file_size_formatted }}"
                         data-dimensions="{{ $image->width }}x{{ $image->height }}"
                         @click="openLightboxFromElement($el)">

                        <!-- Thumbnail -->
                        <div class="w-32 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 relative">
                            <img src="data:image/{{ pathinfo($image->file_name, PATHINFO_EXTENSION) }};base64,{{ base64_encode(Storage::disk('dental_images')->get($image->thumbnail_path ?? $image->file_path)) }}"
                                 alt="{{ $image->title ?? $image->image_type_formatted }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                                <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="font-semibold text-lg text-gray-900">
                                        {{ $image->title ?? 'Untitled' }}
                                    </h3>
                                    <div class="flex gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $image->image_type_formatted }}
                                        </span>
                                        @if($image->tooth_number)
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-800">
                                                <i class="fas fa-tooth text-xs mr-1"></i>
                                                Tooth #{{ $image->tooth_number }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm text-gray-600">
                                    <div>
                                        <i class="far fa-calendar mr-1 text-blue-600"></i>
                                        {{ $image->captured_date->format('M d, Y') }}
                                    </div>
                                    <div>
                                        <i class="fas fa-file mr-1 text-green-600"></i>
                                        {{ $image->file_size_formatted }}
                                    </div>
                                    <div>
                                        <i class="fas fa-expand-arrows-alt mr-1 text-purple-600"></i>
                                        {{ $image->width }} × {{ $image->height }}
                                    </div>
                                    @if($image->dentist)
                                        <div>
                                            <i class="fas fa-user-md mr-1 text-orange-600"></i>
                                            {{ $image->dentist->full_name }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 mt-3">
                                <button type="button" @click.stop="openLightboxFromElement($el.closest('[data-image-id]'))"
                                        class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Full Image
                                </button>
                                <a href="{{ route('patients.images.show', [$patient, $image]) }}"
                                   onclick="event.stopPropagation()"
                                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    View Details
                                </a>
                                <form action="{{ route('patients.images.destroy', [$patient, $image]) }}"
                                      method="POST"
                                      onclick="event.stopPropagation()"
                                      onsubmit="return confirm('Are you sure you want to delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-50 hover:bg-red-100 text-red-600 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $images->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full mb-4">
                    <i class="fas fa-images text-4xl text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No images found</h3>
                <p class="text-gray-600 mb-6">
                    @if(request()->hasAny(['image_type', 'tooth_number', 'date_from', 'date_to', 'search']))
                        No images match your filters. Try adjusting your search criteria.
                    @else
                        Start by uploading X-rays, photos, or scans for this patient.
                    @endif
                </p>
                @if(!request()->hasAny(['image_type', 'tooth_number', 'date_from', 'date_to', 'search']))
                    <a href="{{ route('patients.images.create', $patient) }}" class="btn-modern btn-primary inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Upload First Image
                    </a>
                @endif
            </div>
        @endif

        <!-- Lightbox Modal -->
        <div x-show="lightboxOpen"
             x-cloak
             @keydown.escape.window="closeLightbox()"
             @keydown.left.window="previousImage()"
             @keydown.right.window="nextImage()"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-95"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <!-- Close Button -->
            <button @click="closeLightbox()"
                    class="absolute top-4 right-4 z-10 bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur-sm text-white rounded-full p-3 transition-all hover:scale-110">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Download Button -->
            <a :href="currentImage.url" download
               class="absolute top-4 right-20 z-10 bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur-sm text-white rounded-full p-3 transition-all hover:scale-110">
                <i class="fas fa-download text-xl"></i>
            </a>

            <!-- Zoom Controls -->
            <div class="absolute top-4 left-4 z-10 flex flex-col gap-2">
                <button @click="zoomIn()"
                        class="bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur-sm text-white rounded-lg p-3 transition-all">
                    <i class="fas fa-plus"></i>
                </button>
                <button @click="resetZoom()"
                        class="bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur-sm text-white rounded-lg p-3 transition-all text-xs font-bold">
                    <span x-text="Math.round(scale * 100) + '%'"></span>
                </button>
                <button @click="zoomOut()"
                        class="bg-white bg-opacity-10 hover:bg-opacity-20 backdrop-blur-sm text-white rounded-lg p-3 transition-all">
                    <i class="fas fa-minus"></i>
                </button>
            </div>

            <!-- Image Container -->
            <div class="relative w-full h-screen flex items-center justify-center p-4 md:p-12 pb-32">
                <img :src="currentImage.url"
                     :alt="currentImage.title"
                     class="max-w-full max-h-full object-contain transition-transform duration-200 select-none shadow-2xl"
                     :style="'transform: scale(' + scale + ')'"
                     draggable="false">
            </div>

            <!-- Info Bar -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/80 to-transparent pt-12 pb-4 px-6">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-white">
                        <div class="flex-1">
                            <h3 class="text-xl md:text-2xl font-bold mb-3" x-text="currentImage.title"></h3>
                            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-x-ray text-blue-400"></i>
                                    <span class="text-gray-400">Type:</span>
                                    <span class="font-medium" x-text="currentImage.type"></span>
                                </div>
                                <div x-show="currentImage.tooth" class="flex items-center gap-2">
                                    <i class="fas fa-tooth text-purple-400"></i>
                                    <span class="text-gray-400">Tooth:</span>
                                    <span class="font-medium">#<span x-text="currentImage.tooth"></span></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="far fa-calendar text-green-400"></i>
                                    <span class="text-gray-400">Date:</span>
                                    <span class="font-medium" x-text="currentImage.date"></span>
                                </div>
                                <div x-show="currentImage.dentist" class="flex items-center gap-2">
                                    <i class="fas fa-user-md text-orange-400"></i>
                                    <span class="text-gray-400">Dentist:</span>
                                    <span class="font-medium" x-text="currentImage.dentist"></span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-6 text-sm">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file text-yellow-400"></i>
                                <div>
                                    <div class="text-gray-400 text-xs">Size</div>
                                    <div class="font-medium" x-text="currentImage.fileSize"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-expand-arrows-alt text-pink-400"></i>
                                <div>
                                    <div class="text-gray-400 text-xs">Dimensions</div>
                                    <div class="font-medium" x-text="currentImage.dimensions"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function imageGallery() {
            return {
                viewMode: 'grid',
                showFilters: false,
                lightboxOpen: false,
                currentImage: {},
                scale: 1,
                minScale: 0.5,
                maxScale: 3,

                openLightboxFromElement(element) {
                    this.currentImage = {
                        id: element.dataset.imageId,
                        url: element.dataset.fullImage,
                        title: element.dataset.title,
                        type: element.dataset.type,
                        tooth: element.dataset.tooth,
                        date: element.dataset.date,
                        dentist: element.dataset.dentist,
                        fileSize: element.dataset.filesize,
                        dimensions: element.dataset.dimensions
                    };
                    this.lightboxOpen = true;
                    this.scale = 1;
                    document.body.style.overflow = 'hidden';
                },

                openLightbox(id, url, title, type, tooth, date, dentist, fileSize, dimensions) {
                    this.currentImage = {
                        id: id,
                        url: url,
                        title: title,
                        type: type,
                        tooth: tooth,
                        date: date,
                        dentist: dentist,
                        fileSize: fileSize,
                        dimensions: dimensions
                    };
                    this.lightboxOpen = true;
                    this.scale = 1;
                    document.body.style.overflow = 'hidden';
                },

                closeLightbox() {
                    this.lightboxOpen = false;
                    this.scale = 1;
                    document.body.style.overflow = 'auto';
                },

                zoomIn() {
                    if (this.scale < this.maxScale) {
                        this.scale += 0.25;
                    }
                },

                zoomOut() {
                    if (this.scale > this.minScale) {
                        this.scale -= 0.25;
                    }
                },

                resetZoom() {
                    this.scale = 1;
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-sidebar-layout>

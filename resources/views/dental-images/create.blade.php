<x-app-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-3xl text-gray-900 leading-tight">
                    <i class="fas fa-upload text-blue-600 mr-3"></i>
                    Upload Images - {{ $patient->full_name }}
                </h2>
                <p class="text-gray-600 mt-2">Upload X-rays, photos, and scans for this patient</p>
            </div>
            <a href="{{ route('patients.images.index', $patient) }}" class="btn-modern btn-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Gallery
            </a>
        </div>
    </x-slot>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('patients.images.store', $patient) }}" method="POST" enctype="multipart/form-data"
              x-data="{
                  isDragging: false,
                  previewImages: [],
                  handleFiles(fileList) {
                      this.previewImages = [];
                      Array.from(fileList).forEach((file, index) => {
                          const reader = new FileReader();
                          reader.onload = (e) => {
                              this.previewImages.push({
                                  url: e.target.result,
                                  name: file.name,
                                  size: this.formatFileSize(file.size),
                                  index: index
                              });
                          };
                          reader.readAsDataURL(file);
                      });
                  },
                  handleDrop(e) {
                      const dt = new DataTransfer();
                      Array.from(e.dataTransfer.files).forEach(file => {
                          dt.items.add(file);
                      });
                      this.$refs.fileInput.files = dt.files;
                      this.handleFiles(dt.files);
                  },
                  formatFileSize(bytes) {
                      if (bytes === 0) return '0 Bytes';
                      const k = 1024;
                      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                      const i = Math.floor(Math.log(bytes) / Math.log(k));
                      return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                  },
                  clearFiles() {
                      this.previewImages = [];
                      this.$refs.fileInput.value = '';
                  }
              }">
            @csrf

            <!-- Image Type Selection -->
            <div class="mb-6">
                <label for="image_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Image Type <span class="text-red-500">*</span>
                </label>
                <select name="image_type" id="image_type" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select image type...</option>
                    <option value="xray_intraoral">Intraoral X-Ray</option>
                    <option value="xray_panoramic">Panoramic X-Ray</option>
                    <option value="xray_bitewing">Bitewing X-Ray</option>
                    <option value="xray_periapical">Periapical X-Ray</option>
                    <option value="xray_cephalometric">Cephalometric X-Ray</option>
                    <option value="cbct">CBCT Scan</option>
                    <option value="photo_intraoral">Intraoral Photo</option>
                    <option value="photo_extraoral">Extraoral Photo</option>
                    <option value="scan_3d">3D Scan</option>
                    <option value="other">Other</option>
                </select>
                @error('image_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload Area -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Images <span class="text-red-500">*</span>
                    <span class="text-gray-500 text-xs">(Max 10 files, 10MB each)</span>
                </label>

                <div @dragover.prevent="isDragging = true"
                     @dragleave.prevent="isDragging = false"
                     @drop.prevent="isDragging = false; handleDrop($event)"
                     :class="{'border-blue-500 bg-blue-50': isDragging}"
                     class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-colors">

                    <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput"
                           @change="handleFiles($event.target.files)"
                           class="hidden" id="fileInput" required>

                    <div class="flex flex-col items-center">
                        <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">
                            Drag and drop images here
                        </p>
                        <p class="text-sm text-gray-500 mb-4">or</p>
                        <label for="fileInput" class="btn-modern btn-primary cursor-pointer">
                            <i class="fas fa-folder-open mr-2"></i>
                            Browse Files
                        </label>
                    </div>
                </div>

                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Previews -->
            <div x-show="previewImages.length > 0" class="mb-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">
                    Selected Images (<span x-text="previewImages.length"></span>)
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <template x-for="(preview, index) in previewImages" :key="index">
                        <div class="relative group">
                            <img :src="preview.url" :alt="preview.name"
                                 class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                            <div class="mt-2">
                                <p class="text-xs text-gray-600 truncate" x-text="preview.name"></p>
                                <p class="text-xs text-gray-400" x-text="preview.size"></p>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" @click="clearFiles()"
                            class="text-sm text-red-600 hover:text-red-800">
                        <i class="fas fa-times-circle mr-1"></i>
                        Clear All
                    </button>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tooth Number -->
                <div>
                    <label for="tooth_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Tooth Number (Optional)
                    </label>
                    <select name="tooth_number" id="tooth_number"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Not associated with specific tooth</option>
                        <optgroup label="Upper Right">
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </optgroup>
                        <optgroup label="Upper Left">
                            @for($i = 9; $i <= 16; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </optgroup>
                        <optgroup label="Lower Left">
                            @for($i = 17; $i <= 24; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </optgroup>
                        <optgroup label="Lower Right">
                            @for($i = 25; $i <= 32; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </optgroup>
                    </select>
                </div>

                <!-- View Angle -->
                <div>
                    <label for="view_angle" class="block text-sm font-medium text-gray-700 mb-2">
                        View Angle (Optional)
                    </label>
                    <select name="view_angle" id="view_angle"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select view angle...</option>
                        <option value="frontal">Frontal</option>
                        <option value="lateral">Lateral</option>
                        <option value="occlusal">Occlusal</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Dentist -->
                <div>
                    <label for="dentist_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Dentist (Optional)
                    </label>
                    <select name="dentist_id" id="dentist_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select dentist...</option>
                        @foreach($dentists as $dentist)
                            <option value="{{ $dentist->id }}">{{ $dentist->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Captured Date -->
                <div>
                    <label for="captured_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Captured Date
                    </label>
                    <input type="date" name="captured_date" id="captured_date"
                           value="{{ date('Y-m-d') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Title and Description -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Title (Optional)
                </label>
                <input type="text" name="title" id="title"
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                       placeholder="e.g., Pre-treatment X-ray">
            </div>

            <div class="mb-6">
                <label for="clinical_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Clinical Notes (Optional)
                </label>
                <textarea name="clinical_notes" id="clinical_notes" rows="4"
                          class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Add any relevant clinical observations or notes..."></textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('patients.images.index', $patient) }}"
                   class="btn-modern btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-modern btn-primary">
                    <i class="fas fa-upload mr-2"></i>
                    Upload Images
                </button>
            </div>
        </form>
    </div>
</x-app-sidebar-layout>

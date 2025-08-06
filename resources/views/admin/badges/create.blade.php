@extends('admin.layouts.app')

@section('content')
    <div class="p-4 mx-auto max-w-7xl">
        @include('admin.components.notification')
        <div class="mb-4">
            <h1 class="mb-2 text-3xl font-bold text-gray-900">Buat Badge Baru</h1>
            <p class="text-gray-600">Tambahkan badge untuk sistem reward.</p>
        </div>
        <div class="overflow-hidden bg-white rounded-lg shadow-lg">
            <!-- Form -->
            <form action="{{ route('admin.badges.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <!-- Title Field -->
                <div class="mb-6">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-700">
                        Judul Badge <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('title') border-red-500 @enderror"
                        placeholder="Masukkan judul badge" required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div class="mb-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">
                        Deskripsi Badge
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                        placeholder="Masukkan deskripsi badge (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload Field -->
                <div class="mb-6">
                    <label for="icon_url" class="block mb-2 text-sm font-medium text-gray-700">
                        Icon Badge
                    </label>
                    <div class="flex items-center justify-center w-full">
                        <label for="icon_url"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors @error('icon_url') border-red-500 @enderror">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF, SVG (MAX. 2MB)</p>
                            </div>
                            <div class="hidden" id="image-preview">
                                <img id="preview-img" class="max-w-full rounded-lg max-h-48" alt="Preview">
                                <p class="mt-2 text-sm text-center text-gray-600" id="file-name"></p>
                            </div>
                            <input id="icon_url" name="icon_url" type="file" class="hidden" accept="image/*"
                                onchange="previewImage(this)" />
                        </label>
                    </div>
                    @error('icon_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end pt-6 space-x-4 border-t border-gray-200">
                    <a href="{{ route('admin.badges.index') }}"
                        class="px-6 py-3 text-white transition-colors bg-gray-500 rounded-lg hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 text-white transition-colors bg-blue-500 rounded-lg hover:bg-blue-600">
                        Simpan Badge
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            const file = input.files[0];
            const placeholder = document.getElementById('upload-placeholder');
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const fileName = document.getElementById('file-name');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    fileName.textContent = file.name;
                    placeholder.classList.add('hidden');
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                placeholder.classList.remove('hidden');
                preview.classList.add('hidden');
            }
        }

        // Drag and drop functionality
        const uploadArea = document.querySelector('label[for="icon_url"]');
        const fileInput = document.getElementById('icon_url');

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                previewImage(fileInput);
            }
        });
    </script>
@endpush

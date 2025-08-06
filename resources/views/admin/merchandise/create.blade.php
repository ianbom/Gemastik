@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen py-8 rounded-lg shadow-lg bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Tambah Merchandise</h1>
                        <p class="mt-2 text-sm text-gray-600">Buat merchandise baru untuk sistem reward</p>
                    </div>
                    <a href="{{ route('admin.merchandise.index') }}"
                        class="inline-flex items-center px-4 py-2 font-medium text-white transition-colors duration-200 bg-gray-600 rounded-lg hover:bg-gray-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            @include('admin.components.notification')

            @if (session('error'))
                <div class="flex items-center px-4 py-3 mb-6 text-red-700 border border-red-200 rounded-lg bg-red-50">
                    <svg class="flex-shrink-0 w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white border border-gray-200 shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Informasi Merchandise</h2>
                    <p class="mt-1 text-sm text-gray-500">Lengkapi semua informasi merchandise yang diperlukan</p>
                </div>

                <form action="{{ route('admin.merchandise.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf

                    <!-- Nama Merchandise -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nama Merchandise <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Masukkan nama merchandise"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('name')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4" placeholder="Masukkan deskripsi merchandise"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Gambar Merchandise -->
                    <div class="space-y-2">
                        <label for="image_url" class="block text-sm font-medium text-gray-700">
                            Gambar Merchandise <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200 @error('image_url') border-red-300 @enderror">
                            <div class="space-y-1 text-center">
                                <div id="image-preview" class="hidden mb-4">
                                    <img id="preview-img" src="#" alt="Preview"
                                        class="object-cover w-32 h-32 mx-auto border border-gray-200 rounded-lg">
                                </div>
                                <svg class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image_url"
                                        class="relative font-medium text-blue-600 bg-white rounded-md cursor-pointer hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload gambar</span>
                                        <input id="image_url" name="image_url" type="file" accept="image/*"
                                            class="sr-only">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                            </div>
                        </div>
                        @error('image_url')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Points Cost -->
                    <div class="space-y-2">
                        <label for="points_cost" class="block text-sm font-medium text-gray-700">
                            Biaya Poin <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="points_cost" name="points_cost" value="{{ old('points_cost') }}"
                                min="1" placeholder="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('points_cost') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            <span class="absolute text-sm text-gray-500 right-3 top-3">poin</span>
                        </div>
                        @error('points_cost')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="flex items-center">
                            <label class="inline-flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="text-blue-600 border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Merchandise aktif</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Merchandise yang tidak aktif tidak akan ditampilkan kepada
                            pengguna</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end pt-6 space-x-4 border-t border-gray-200">
                        <a href="{{ route('admin.merchandise.index') }}"
                            class="px-6 py-3 font-medium text-gray-700 transition-colors duration-200 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Batal
                        </a>
                        <button type="submit"
                            class="flex items-center px-6 py-3 font-medium text-white transition-colors duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Merchandise
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image_url');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                }
            });

            // Format number input for points
            const pointsInput = document.getElementById('points_cost');
            pointsInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\d]/g, '');
                if (value) {
                    value = parseInt(value).toLocaleString('id-ID');
                }
                // Store raw number for form submission
                this.dataset.rawValue = value.replace(/\./g, '');
            });

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const description = document.getElementById('description').value.trim();
                const imageFile = document.getElementById('image_url').files[0];
                const pointsCost = document.getElementById('points_cost').value;

                if (!name || !description || !imageFile || !pointsCost) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi');
                    return false;
                }

                if (pointsCost <= 0) {
                    e.preventDefault();
                    alert('Biaya poin harus lebih dari 0');
                    return false;
                }
            });
        });
    </script>
@endpush

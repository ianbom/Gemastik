@extends('admin.layouts.app')

@section('content')
    <style>
        /* Mengatur tinggi minimal editor */
        .ck-editor__editable_inline {
            min-height: 300px;
        }

        /* Mengembalikan style list default di dalam konten editor, karena Tailwind me-resetnya */
        .ck-content ul,
        .ck-content ol {
            list-style: revert;
            margin: revert;
            padding: revert;
        }
    </style>

    <div class="container px-4 py-4 mx-auto">
        <div class="mx-auto max-w-7xl">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Buat Konten Baru</h1>
                <p class="text-gray-600">Buat konten baru dengan editor modern dan media pendukung.</p>
            </div>

            @include('admin.components.notification')

            <!-- Form Card -->
            <div class="overflow-hidden bg-white rounded-lg shadow-md">
                <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data"
                    id="contentForm">
                    @csrf

                    <div class="p-6 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-700">
                                Judul <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                placeholder="Masukkan judul konten" maxlength="255">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content Type -->
                        <div>
                            <label for="content_type" class="block mb-2 text-sm font-medium text-gray-700">
                                Tipe Konten
                            </label>
                            <select id="content_type" name="content_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content_type') border-red-500 @enderror">
                                <option value="">Pilih tipe konten</option>
                                <option value="artikel" {{ old('content_type') == 'artikel' ? 'selected' : '' }}>Artikel
                                </option>
                                <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="modul" {{ old('content_type') == 'modul' ? 'selected' : '' }}>Modul</option>
                            </select>
                            @error('content_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Body with CKEditor -->
                        <div>
                            <label for="body" class="block mb-2 text-sm font-medium text-gray-700">
                                Konten <span class="text-red-500">*</span>
                            </label>
                            {{-- Textarea ini akan diubah menjadi CKEditor oleh JavaScript --}}
                            <textarea id="body" name="body" rows="10"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('body') border-red-500 @enderror"
                                placeholder="Tulis konten di sini...">{{ old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Media Upload -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700">
                                Media Pendukung
                            </label>
                            <div class="p-6 text-center transition-colors border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400"
                                id="dropZone">
                                <input type="file" id="mediaInput" name="media[]" multiple
                                    accept="image/*,video/*,application/pdf,.pdf" class="hidden"
                                    onchange="handleFileSelect(this)">
                                <div class="cursor-pointer" onclick="document.getElementById('mediaInput').click()">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <p class="mb-2 text-gray-600">Klik untuk upload atau drag & drop file</p>
                                    <p class="text-sm text-gray-500">Gambar: JPG, PNG, GIF, SVG, WebP (max 10MB)</p>
                                    <p class="text-sm text-gray-500">Video: MP4, MOV, AVI, MKV, WMV (max 10MB)</p>
                                    <p class="text-sm text-gray-500">Dokumen: PDF (max 10MB)</p>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div id="filePreview" class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2 lg:grid-cols-3"></div>

                            <!-- Upload Progress -->
                            <div id="uploadProgress" class="hidden mt-4">
                                <div class="h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 transition-all duration-300 bg-blue-600 rounded-full" style="width: 0%"
                                        id="progressBar"></div>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">Memproses file... <span id="progressText">0%</span>
                                </p>
                            </div>

                            @error('media')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('media.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- Form Footer -->
                    <div class="flex justify-end px-6 py-4 space-x-3 bg-gray-50">
                        <a href="{{ route('admin.contents.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Batal</a>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span>Simpan Konten</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ClassicEditor
                .create(document.querySelector('#body'), {
                    // Konfigurasi toolbar bisa ditambahkan di sini jika perlu
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', '|',
                            'link', 'bulletedList', 'numberedList', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    },
                    language: 'id' // Menggunakan bahasa Indonesia jika tersedia
                })
                .then(editor => {
                    console.log('CKEditor berhasil diinisialisasi.', editor);
                })
                .catch(error => {
                    console.error('Terjadi error saat inisialisasi CKEditor:', error);
                });
        });


        let selectedFiles = [];
        const maxFileSize = 10 * 1024 * 1024; // 10MB
        const allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml', 'image/webp'];
        const allowedVideoTypes = ['video/mp4', 'video/mov', 'video/avi', 'video/mkv', 'video/wmv'];
        const allowedPdfTypes = ['application/pdf'];

        // Character counter for title
        document.getElementById('title').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('titleCount').textContent = count;

            if (count > 255) {
                this.classList.add('border-red-500');
                document.getElementById('titleCount').classList.add('text-red-600');
            } else {
                this.classList.remove('border-red-500');
                document.getElementById('titleCount').classList.remove('text-red-600');
            }
        });

        // Character counter for body
        document.getElementById('body').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('bodyCount').textContent = count.toLocaleString();
        });

        // Initialize character counters
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const bodyInput = document.getElementById('body');

            if (titleInput.value) {
                document.getElementById('titleCount').textContent = titleInput.value.length;
            }

            if (bodyInput.value) {
                document.getElementById('bodyCount').textContent = bodyInput.value.length.toLocaleString();
            }
        });

        function handleFileSelect(input) {
            const files = Array.from(input.files);
            const filePreview = document.getElementById('filePreview');
            const uploadProgress = document.getElementById('uploadProgress');

            // Show progress
            uploadProgress.classList.remove('hidden');

            // Clear previous previews
            filePreview.innerHTML = '';
            selectedFiles = [];

            let processedFiles = 0;
            const totalFiles = files.length;

            files.forEach((file, index) => {
                // Validate file
                if (!validateFile(file)) {
                    processedFiles++;
                    updateProgress(processedFiles, totalFiles);
                    return;
                }

                selectedFiles.push(file);

                const fileItem = document.createElement('div');
                fileItem.className = 'relative bg-gray-100 rounded-lg p-4 border hover:shadow-md transition-shadow';

                const fileType = getFileType(file);

                if (fileType === 'image') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fileItem.innerHTML = `
                            <div class="relative">
                                <img src="${e.target.result}" class="object-cover w-full h-24 mb-2 rounded">
                                <div class="absolute px-2 py-1 text-xs text-white bg-black bg-opacity-50 rounded top-1 left-1">
                                    IMG
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 truncate">${file.name}</p>
                            <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                            <button type="button" onclick="removeFile(${index})" class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition-colors bg-red-500 rounded-full top-2 right-2 hover:bg-red-600">
                                ×
                            </button>
                        `;
                        processedFiles++;
                        updateProgress(processedFiles, totalFiles);
                    };
                    reader.readAsDataURL(file);
                } else if (fileType === 'video') {
                    fileItem.innerHTML = `
                        <div class="relative">
                            <div class="flex items-center justify-center w-full h-24 mb-2 bg-gray-200 rounded">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="absolute px-2 py-1 text-xs text-white bg-black bg-opacity-50 rounded top-1 left-1">
                                VID
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                        <button type="button" onclick="removeFile(${index})" class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition-colors bg-red-500 rounded-full top-2 right-2 hover:bg-red-600">
                            ×
                        </button>
                    `;
                    processedFiles++;
                    updateProgress(processedFiles, totalFiles);
                } else if (fileType === 'pdf') {
                    fileItem.innerHTML = `
                        <div class="relative">
                            <div class="flex items-center justify-center w-full h-24 mb-2 bg-red-100 rounded">
                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="absolute px-2 py-1 text-xs text-white bg-red-600 rounded top-1 left-1">
                                PDF
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 truncate" title="${file.name}">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                        <button type="button" onclick="removeFile(${index})" class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition-colors bg-red-500 rounded-full top-2 right-2 hover:bg-red-600">
                            ×
                        </button>
                    `;
                    processedFiles++;
                    updateProgress(processedFiles, totalFiles);
                }

                filePreview.appendChild(fileItem);
            });

            updateFileInput();
        }

        function getFileType(file) {
            if (allowedImageTypes.includes(file.type)) {
                return 'image';
            } else if (allowedVideoTypes.includes(file.type)) {
                return 'video';
            } else if (allowedPdfTypes.includes(file.type)) {
                return 'pdf';
            }
            return 'unknown';
        }

        function validateFile(file) {
            // Check file size
            if (file.size > maxFileSize) {
                showError(`File "${file.name}" terlalu besar. Maksimal 10MB.`);
                return false;
            }

            // Check file type
            const isValidImage = allowedImageTypes.includes(file.type);
            const isValidVideo = allowedVideoTypes.includes(file.type);
            const isValidPdf = allowedPdfTypes.includes(file.type);

            if (!isValidImage && !isValidVideo && !isValidPdf) {
                showError(`File "${file.name}" format tidak didukung. Hanya mendukung gambar, video, dan PDF.`);
                return false;
            }

            return true;
        }

        function showError(message) {
            // Create error notification
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
            errorDiv.textContent = message;

            document.body.appendChild(errorDiv);

            // Remove after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }

        function updateProgress(processed, total) {
            const percentage = (processed / total) * 100;
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = Math.round(percentage) + '%';

            if (processed === total) {
                setTimeout(() => {
                    document.getElementById('uploadProgress').classList.add('hidden');
                }, 1000);
            }
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            refreshPreview();
        }

        function refreshPreview() {
            const filePreview = document.getElementById('filePreview');
            filePreview.innerHTML = '';

            selectedFiles.forEach((file, newIndex) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'relative bg-gray-100 rounded-lg p-4 border hover:shadow-md transition-shadow';

                const fileType = getFileType(file);

                if (fileType === 'image') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fileItem.innerHTML = `
                            <div class="relative">
                                <img src="${e.target.result}" class="object-cover w-full h-24 mb-2 rounded">
                                <div class="absolute px-2 py-1 text-xs text-white bg-black bg-opacity-50 rounded top-1 left-1">
                                    IMG
                                </div>
                            </div>
                            <p class="text-sm font-medium text-gray-600 truncate">${file.name}</p>
                            <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                            <button type="button" onclick="removeFile(${newIndex})" class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition-colors bg-red-500 rounded-full top-2 right-2 hover:bg-red-600">
                                ×
                            </button>
                        `;
                    };
                    reader.readAsDataURL(file);
                } else if (fileType === 'video') {
                    fileItem.innerHTML = `
                        <div class="relative">
                            <div class="flex items-center justify-center w-full h-24 mb-2 bg-gray-200 rounded">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="absolute px-2 py-1 text-xs text-white bg-black bg-opacity-50 rounded top-1 left-1">
                                VID
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                        <button type="button" onclick="removeFile(${newIndex})" class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition-colors bg-red-500 rounded-full top-2 right-2 hover:bg-red-600">
                            ×
                        </button>
                    `;
                } else if (fileType === 'pdf') {
                    fileItem.innerHTML = `
                        <div class="relative">
                            <div class="flex items-center justify-center w-full h-24 mb-2 bg-red-100 rounded">
                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="absolute px-2 py-1 text-xs text-white bg-red-600 rounded top-1 left-1">
                                PDF
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 truncate" title="${file.name}">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                        <button type="button" onclick="removeFile(${newIndex})" class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition-colors bg-red-500 rounded-full top-2 right-2 hover:bg-red-600">
                            ×
                        </button>
                    `;
                }

                filePreview.appendChild(fileItem);
            });
        }

        function updateFileInput() {
            const input = document.getElementById('mediaInput');
            const dt = new DataTransfer();

            selectedFiles.forEach(file => {
                dt.items.add(file);
            });

            input.files = dt.files;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Drag and drop functionality
        const dropZone = document.getElementById('dropZone');

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50');

            const files = Array.from(e.dataTransfer.files);
            const input = document.getElementById('mediaInput');

            const dt = new DataTransfer();
            files.forEach(file => {
                dt.items.add(file);
            });

            input.files = dt.files;
            handleFileSelect(input);
        });

        // Form submission handling
        document.getElementById('contentForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoader = document.getElementById('submitLoader');

            // Disable button and show loading
            submitBtn.disabled = true;
            submitText.textContent = 'Menyimpan...';
            submitLoader.classList.remove('hidden');

            // Validate required fields
            const title = document.getElementById('title').value.trim();
            const body = document.getElementById('body').value.trim();

            if (!title || !body) {
                e.preventDefault();
                submitBtn.disabled = false;
                submitText.textContent = 'Simpan Konten';
                submitLoader.classList.add('hidden');
                showError('Judul dan konten wajib diisi!');
                return;
            }

            // If validation passes, let the form submit normally
        });

        // Auto-save functionality (optional)
        let autoSaveTimeout;

        function autoSave() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                const formData = new FormData(document.getElementById('contentForm'));
                localStorage.setItem('content_draft', JSON.stringify({
                    title: formData.get('title'),
                    body: formData.get('body'),
                    content_type: formData.get('content_type'),
                    timestamp: new Date().toISOString()
                }));
                console.log('Draft saved to localStorage');
            }, 2000);
        }

        // Add auto-save listeners
        document.getElementById('title').addEventListener('input', autoSave);
        document.getElementById('body').addEventListener('input', autoSave);
        document.getElementById('content_type').addEventListener('change', autoSave);

        // Load draft on page load
        document.addEventListener('DOMContentLoaded', function() {
            const draft = localStorage.getItem('content_draft');
            if (draft) {
                try {
                    const draftData = JSON.parse(draft);
                    const draftTime = new Date(draftData.timestamp);
                    const now = new Date();
                    const hoursDiff = (now - draftTime) / (1000 * 60 * 60);

                    // Only restore if draft is less than 24 hours old
                    if (hoursDiff < 24) {
                        if (confirm('Ditemukan draft yang tersimpan. Apakah Anda ingin memulihkannya?')) {
                            document.getElementById('title').value = draftData.title || '';
                            document.getElementById('body').value = draftData.body || '';
                            document.getElementById('content_type').value = draftData.content_type || '';

                            // Update character counters
                            document.getElementById('titleCount').textContent = (draftData.title || '').length;
                            document.getElementById('bodyCount').textContent = (draftData.body || '').length
                                .toLocaleString();
                        }
                    }
                } catch (e) {
                    console.error('Error loading draft:', e);
                }
            }
        });
    </script>
@endpush

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
            <h1 class="mb-2 text-3xl font-bold text-gray-900">Edit Konten</h1>
            <p class="text-gray-600">Edit konten dengan media pendukung</p>
        </div>

        @include('admin.components.notification')

        <!-- Form Card -->
        <div class="overflow-hidden bg-white rounded-lg shadow-md">
            <form action="{{ route('admin.contents.update', $content->id) }}" method="POST" enctype="multipart/form-data" id="contentForm">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-700">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title', $content->title) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="Masukkan judul konten"
                               maxlength="255">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <span id="titleCount">{{ strlen(old('title', $content->title)) }}</span>/255 karakter
                        </p>
                    </div>

                    <!-- Content Type -->
                    <div>
                        <label for="content_type" class="block mb-2 text-sm font-medium text-gray-700">
                            Tipe Konten
                        </label>
                        <select id="content_type"
                                name="content_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content_type') border-red-500 @enderror">
                            <option value="">Pilih tipe konten</option>
                            <option value="artikel" {{ old('content_type', $content->content_type) == 'article' ? 'selected' : '' }}>Artikel</option>
                            <option value="modul" {{ old('content_type', $content->content_type) == 'modul' ? 'selected' : '' }}>Modul</option>
                            <option value="video" {{ old('content_type', $content->content_type) == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                        @error('content_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Body -->
                    <div>
                        <label for="body" class="block mb-2 text-sm font-medium text-gray-700">
                            Konten <span class="text-red-500">*</span>
                        </label>
                        <textarea id="body"
                                  name="body"
                                  rows="10"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('body') border-red-500 @enderror"
                                  placeholder="Tulis konten di sini...">{{ old('body', $content->body) }}</textarea>
                        @error('body')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <span id="bodyCount">{{ strlen(old('body', $content->body)) }}</span> karakter
                        </p>
                    </div>


                    <!-- Media Upload -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Tambah Media Baru
                        </label>
                        <div class="p-6 text-center transition-colors border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400" id="dropZone">
                            <input type="file"
                                   id="mediaInput"
                                   name="media[]"
                                   multiple
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/webp,video/mp4,video/mov,video/avi,video/mkv,video/wmv,application/pdf"
                                   class="hidden"
                                   onchange="handleFileSelect(this)">
                            <div class="cursor-pointer" onclick="document.getElementById('mediaInput').click()">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <p class="mb-2 text-gray-600">Klik untuk upload atau drag & drop file baru</p>
                                <p class="text-sm text-gray-500">Gambar: JPG, PNG, GIF, SVG, WebP (max 10MB)</p>
                                <p class="text-sm text-gray-500">Video: MP4, MOV, AVI, MKV, WMV (max 10MB)</p>
                                <p class="text-sm text-gray-500">Dokumen: PDF (max 10MB)</p>
                            </div>
                        </div>

                        <!-- New File Preview -->
                        <div id="filePreview" class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2 lg:grid-cols-3"></div>

                        <!-- Upload Progress -->
                        <div id="uploadProgress" class="hidden mt-4">
                            <div class="h-2 bg-gray-200 rounded-full">
                                <div class="h-2 transition-all duration-300 bg-blue-600 rounded-full" style="width: 0%" id="progressBar"></div>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">Memproses file... <span id="progressText">0%</span></p>
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
                <div class="flex justify-between px-6 py-4 bg-gray-50">
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.contents.index') }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Kembali
                        </a>

                        <a href="{{ route('content.show', $content->id) }}"
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">

                            Lihat Detail
                        </a>
                    </div>
                    <div class="flex space-x-3">



                        <button type="submit"
                                id="submitBtn"
                                class="px-4 py-2 text-sm font-medium text-white transition-colors bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">Update Konten</span>
                            <svg id="submitLoader" class="hidden w-4 h-4 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            @if($content->media && $content->media->count() > 0)
            <div class="p-6 space-y-6">
                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Media Saat Ini
                </label>
                <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2 lg:grid-cols-3" id="existingMedia">
                    @foreach($content->media as $media)
                    <form action="{{ route('admin.delete.contentMedia', $media->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="relative p-4 transition-shadow bg-gray-100 border rounded-lg hover:shadow-md" data-media-id="{{ $media->id }}">
                            @if($media->media_type === 'image')
                            <div class="relative">
                                <img src="{{ Storage::url($media->media_url) }}" class="object-cover w-full h-24 mb-2 rounded" alt="Media">
                                <div class="absolute px-2 py-1 text-xs text-white bg-black bg-opacity-50 rounded top-1 left-1">
                                    IMG
                                </div>
                            </div>
                            @elseif($media->media_type === 'video')
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
                            @elseif($media->media_type === 'document')
                            <div class="relative">
                                <div class="flex items-center justify-center w-full h-24 mb-2 bg-red-100 rounded">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="absolute px-2 py-1 text-xs text-white bg-red-500 rounded top-1 left-1">
                                    PDF
                                </div>
                            </div>
                            @endif
                            <p class="text-sm font-medium text-gray-600 truncate">{{ basename($media->media_url) }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($media->media_type) }}</p>
                            <button type="submit"
                                    class="absolute flex items-center justify-center w-6 h-6 text-xs text-white transition-colors bg-red-500 rounded-full top-2 right-2 hover:bg-red-600"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ini?');">
                                ×
                            </button>
                        </div>
                    </form>
                    @endforeach
                </div>
                <!-- Hidden input untuk media yang akan dihapus -->
                <input type="hidden" name="delete_media_ids" id="deleteMediaIds" value="">
            </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
 <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi CKEditor
    ClassicEditor
        .create(document.querySelector('#body'), {
            toolbar: {
                items: [
                    'heading', '|', 'bold', 'italic', 'underline', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'insertTable', '|', 'undo', 'redo'
                ]
            },
            language: 'id'
        })
        .catch(error => {
            console.error('Error inisialisasi CKEditor:', error);

        });

    // --- LOGIKA UPLOAD DAN PREVIEW FILE BARU ---

    const mediaInput = document.getElementById('mediaInput');
    const filePreview = document.getElementById('filePreview');
    const dropZone = document.getElementById('dropZone');

    // Gunakan DataTransfer.files untuk menyimpan file yang akan diupload
    let fileStore = new DataTransfer();

    // Fungsi untuk merender preview file
    const renderPreviews = () => {
        filePreview.innerHTML = ''; // Kosongkan preview
        if (fileStore.files.length === 0) {
            filePreview.classList.add('hidden');
            return;
        }

        filePreview.classList.remove('hidden');

        Array.from(fileStore.files).forEach((file, index) => {
            const previewCard = document.createElement('div');
            previewCard.className = 'relative bg-gray-100 rounded-lg p-3 border hover:shadow-md transition-shadow flex items-start space-x-3';

            let thumbnailHTML = '';
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = previewCard.querySelector('.preview-thumbnail');
                    if(img) img.src = e.target.result;
                }
                reader.readAsDataURL(file);
                thumbnailHTML = `<img src="" class="w-16 h-16 object-cover rounded preview-thumbnail" alt="Preview">`;
            } else if (file.type.startsWith('video/')) {
                thumbnailHTML = `<div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></div>`;
            } else { // Document (PDF, etc.)
                thumbnailHTML = `<div class="w-16 h-16 bg-red-100 rounded flex items-center justify-center"><svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>`;
            }


            previewCard.innerHTML = `
                ${thumbnailHTML}
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800 font-medium truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
                <button type="button" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors" data-index="${index}">
                    ×
                </button>
            `;


            filePreview.appendChild(previewCard);
        });
    };


    // Fungsi untuk menambah file ke store
    const addFiles = (files) => {
        const existingFileNames = Array.from(fileStore.files).map(f => f.name);
        for (const file of files) {
            // Cek duplikasi file
            if (!existingFileNames.includes(file.name)) {
                fileStore.items.add(file);
            }

        }
        mediaInput.files = fileStore.files; // Update input file dengan file baru
        renderPreviews();
    };

    // Event listener untuk tombol hapus pada preview
    filePreview.addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            const indexToRemove = parseInt(e.target.getAttribute('data-index'));
            const newFiles = new DataTransfer();
            Array.from(fileStore.files).forEach((file, index) => {
                if (index !== indexToRemove) {
                    newFiles.items.add(file);
                }
            });
            fileStore = newFiles;
            mediaInput.files = fileStore.files; // Update input file
            renderPreviews();
        }
    });

    // Event listener untuk input file
    mediaInput.addEventListener('change', function() {
        addFiles(this.files);
    });

    // Fungsionalitas Drag & Drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        if (e.dataTransfer.files.length) {
            addFiles(e.dataTransfer.files);
        }
    });

    // Fungsi untuk reset form
    window.resetForm = function() {
        document.getElementById('contentForm').reset();
        // Reset CKEditor
        const editor = document.querySelector('.ck-editor__editable').ckeditor;
        editor.setData(`{{ addslashes(old('body', $content->body)) }}`);
        // Reset preview file
        fileStore = new DataTransfer();
        mediaInput.files = fileStore.files;
        renderPreviews();
    };
});
</script>
@endpush
@endpush

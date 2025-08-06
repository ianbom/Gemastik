@extends('admin.layouts.app')

@section('content')
<div class="px-4 py-8 mx-auto max-w">
    <div class="p-6 bg-white rounded-lg shadow-lg">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Buat Misi Baru</h1>
            <p class="mt-2 text-gray-600">Lengkapi form di bawah untuk membuat misi baru</p>
        </div>

        <form action="{{ route('admin.missions.store') }}" method="POST" id="missionForm" enctype="multipart/form-data">
            @csrf

            <!-- Report Selection -->
            <div class="mb-6">
                <label for="report_id" class="block mb-2 text-sm font-medium text-gray-700">
                    Pilih Report (Opsional)
                </label>
                <select name="report_id" id="report_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Pilih Report --</option>
                    @foreach($reports as $report)
                        <option value="{{ $report->id }}"
                                data-province-id="{{ $report->province_id }}"
                                data-city-id="{{ $report->city_id }}"
                                data-district-id="{{ $report->district_id }}"
                                data-latitude="{{ $report->latitude }}"
                                data-longitude="{{ $report->longitude }}"
                                data-address="{{ $report->address }}"
                                {{ old('report_id') == $report->id ? 'selected' : '' }}>
                            {{ $report->title }} - {{ $report->city->name ?? '' }}, {{ $report->district->name ?? '' }}, Pelapor : {{ $report->reporter->name }}
                        </option>
                    @endforeach
                </select>
                @error('report_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mission Details -->
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-700">
                        Judul Misi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan judul misi">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">
                        Deskripsi Misi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Masukkan deskripsi misi">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                 <!-- Image Upload Field -->
            <div class="mb-6">
                <label for="thumbnail_url" class="block mb-2 text-sm font-medium text-gray-700">
                   Thumbnail
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="thumbnail_url" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors @error('thumbnail_url') border-red-500 @enderror">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                            <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
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
                        <input
                            id="thumbnail_url"
                            name="thumbnail_url"
                            type="file"
                            class="hidden"
                            accept="image/*"
                            onchange="previewImage(this)"
                        />
                    </label>
                </div>
                @error('thumbnail_url')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

                <!-- Scheduled Date -->
                <div>
                    <label for="scheduled_date" class="block mb-2 text-sm font-medium text-gray-700">
                        Tanggal Terjadwal
                    </label>
                    <input type="datetime-local" name="scheduled_date" id="scheduled_date"
                           value="{{ old('scheduled_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('scheduled_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status (Hidden, default to open) -->
                <input type="hidden" name="status" value="open">
            </div>

            <!-- Location Information (from Report) -->
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Informasi Lokasi</h3>
                <div class="p-4 rounded-md bg-gray-50">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Province -->
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Provinsi</label>
                            <input type="text" id="province_display" readonly
                                   class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-md"
                                   placeholder="Pilih report untuk mengisi lokasi">
                            <input type="hidden" name="province_id" id="province_id" value="{{ old('province_id') }}">
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Kota</label>
                            <input type="text" id="city_display" readonly
                                   class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-md"
                                   placeholder="Pilih report untuk mengisi lokasi">
                            <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id') }}">
                        </div>

                        <!-- District -->
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Kecamatan</label>
                            <input type="text" id="district_display" readonly
                                   class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-md"
                                   placeholder="Pilih report untuk mengisi lokasi">
                            <input type="hidden" name="district_id" id="district_id" value="{{ old('district_id') }}">
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Alamat</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                   class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-md"
                                   placeholder="Alamat akan diisi otomatis">
                        </div>

                        <!-- Coordinates -->
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}"
                                   class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-md"
                                   placeholder="Koordinat latitude">
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}"
                                   class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-md"
                                   placeholder="Koordinat longitude">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.missions.index') }}"
                   class="px-6 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Buat Misi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportSelect = document.getElementById('report_id');
    const provinceDisplay = document.getElementById('province_display');
    const cityDisplay = document.getElementById('city_display');
    const districtDisplay = document.getElementById('district_display');
    const addressInput = document.getElementById('address');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const provinceIdInput = document.getElementById('province_id');
    const cityIdInput = document.getElementById('city_id');
    const districtIdInput = document.getElementById('district_id');

    reportSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (selectedOption.value) {
            // Get data from selected option
            const provinceId = selectedOption.dataset.provinceId;
            const cityId = selectedOption.dataset.cityId;
            const districtId = selectedOption.dataset.districtId;
            const latitude = selectedOption.dataset.latitude;
            const longitude = selectedOption.dataset.longitude;
            const address = selectedOption.dataset.address;

            // Set hidden inputs
            provinceIdInput.value = provinceId || '';
            cityIdInput.value = cityId || '';
            districtIdInput.value = districtId || '';
            latitudeInput.value = latitude || '';
            longitudeInput.value = longitude || '';
            addressInput.value = address || '';

            // Update display fields - you might want to fetch actual names via AJAX
            // For now, we'll use placeholders
            provinceDisplay.value = provinceId ? `Province ID: ${provinceId}` : '';
            cityDisplay.value = cityId ? `City ID: ${cityId}` : '';
            districtDisplay.value = districtId ? `District ID: ${districtId}` : '';
        } else {
            // Clear all fields
            provinceIdInput.value = '';
            cityIdInput.value = '';
            districtIdInput.value = '';
            latitudeInput.value = '';
            longitudeInput.value = '';
            addressInput.value = '';
            provinceDisplay.value = '';
            cityDisplay.value = '';
            districtDisplay.value = '';
        }
    });

    // If there's an old value, trigger change event
    if (reportSelect.value) {
        reportSelect.dispatchEvent(new Event('change'));
    }
});


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
const uploadArea = document.querySelector('label[for="thumbnail_url"]');
const fileInput = document.getElementById('thumbnail_url');

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
@endsection

@extends('admin.layouts.app')

@section('content')
    <div class="container px-4 py-4 mx-auto">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Edit Mission</h1>
        @include('admin.components.notification')

        <form action="{{ route('admin.missions.update', $mission->id) }}" method="POST" enctype="multipart/form-data"
            class="p-6 mb-8 bg-white rounded-lg shadow-lg">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
                <!-- Bagian Kiri: Informasi Utama -->
                <div>
                    <h2 class="mb-4 text-xl font-semibold text-gray-700">Mission Details</h2>

                    <div class="mb-4">
                        <label for="title" class="block mb-2 font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $mission->title) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block mb-2 font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $mission->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="report_id" class="block mb-2 font-medium text-gray-700">Related Report</label>
                        <select name="report_id" id="report_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select Report --</option>
                            <option value="{{ $mission->report_id }}"
                                {{ old('report_id', $mission->report_id) == $mission->report_id ? 'selected' : '' }}>
                                {{ $mission->report->title }}
                            </option>
                            @foreach ($reports as $report)
                                <option value="{{ $report->id }}"
                                    {{ old('report_id', $mission->report_id) == $report->id ? 'selected' : '' }}>
                                    {{ $report->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('report_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="status" class="block mb-2 font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach (['open', 'on-progress', 'completed', 'cancelled'] as $option)
                                    <option value="{{ $option }}"
                                        {{ old('status', $mission->status) === $option ? 'selected' : '' }}>
                                        {{ ucfirst($option) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="scheduled_date" class="block mb-2 font-medium text-gray-700">Scheduled Date</label>
                            <input type="datetime-local" name="scheduled_date" id="scheduled_date"
                                value="{{ old('scheduled_date', $mission->scheduled_date ? $mission->scheduled_date->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('scheduled_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="thumbnail_url" class="block mb-2 text-sm font-medium text-gray-700">
                            Thumbnail
                        </label>

                        <!-- Current Image Display -->
                        @if ($mission->thumbnail_url)
                            <div class="mb-4">
                                <p class="mb-2 text-sm text-gray-600">Icon saat ini:</p>
                                <div class="flex items-center space-x-4">
                                    <img src="{{ asset('storage/' . $mission->thumbnail_url) }}" alt="Current mission Icon"
                                        class="object-cover w-16 h-16 border rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium">{{ basename($mission->thumbnail_url) }}</p>
                                        <p class="text-xs text-gray-500">Upload gambar baru untuk mengganti</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-center w-full">
                            <label for="thumbnail_url"
                                class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors @error('thumbnail_url') border-red-500 @enderror">
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
                                    @if ($mission->thumbnail_url)
                                        <p class="mt-1 text-xs text-blue-600">Biarkan kosong jika tidak ingin mengganti</p>
                                    @endif
                                </div>
                                <div class="hidden" id="image-preview">
                                    <img id="preview-img" class="max-w-full rounded-lg max-h-48" alt="Preview">
                                    <p class="mt-2 text-sm text-center text-gray-600" id="file-name"></p>
                                </div>
                                <input id="thumbnail_url" name="thumbnail_url" type="file" class="hidden"
                                    accept="image/*" onchange="previewImage(this)" />
                            </label>
                        </div>
                        @error('thumbnail_url')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Bagian Kanan: Lokasi & Penugasan -->
                <div>
                    <h2 class="mb-4 text-xl font-semibold text-gray-700">Location & Assignment</h2>

                    <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                        <div>
                            <label for="province_id" class="block mb-2 font-medium text-gray-700">Province</label>
                            <select name="province_id" id="province_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Select province --</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}"
                                        {{ old('province_id', $mission->province_id) == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city_id" class="block mb-2 font-medium text-gray-700">City</label>
                            <select name="city_id" id="city_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Select City --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ old('city_id', $mission->city_id) == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="district_id" class="block mb-2 font-medium text-gray-700">District</label>
                            <select name="district_id" id="district_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Select District --</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('district_id', $mission->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('district_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block mb-2 font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $mission->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="latitude" class="block mb-2 font-medium text-gray-700">Latitude</label>
                            <input type="text" name="latitude" id="latitude"
                                value="{{ old('latitude', $mission->latitude) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="longitude" class="block mb-2 font-medium text-gray-700">Longitude</label>
                            <input type="text" name="longitude" id="longitude"
                                value="{{ old('longitude', $mission->longitude) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="mb-4">
                    <label for="assigned_to_type" class="block mb-2 font-medium text-gray-700">Assign To</label>
                    <select name="assigned_to_type" id="assigned_to_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Type --</option>
                        <option value="community" {{ old('assigned_to_type', $mission->assigned_to_type) === 'community' ? 'selected' : '' }}>Community</option>
                        <option value="volunteer" {{ old('assigned_to_type', $mission->assigned_to_type) === 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                    </select>
                    @error('assigned_to_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div> --}}

                    {{-- <div class="mb-4">
                    <label for="assigned_volunteer_id" class="block mb-2 font-medium text-gray-700">Assign Volunteer</label>
                    <select name="assigned_volunteer_id" id="assigned_volunteer_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Volunteer --</option>
                        <!-- Daftar volunteer akan ditampilkan di sini -->
                    </select>
                    @error('assigned_volunteer_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div> --}}
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <a href="{{ route('admin.missions.index') }}"
                    class="px-4 py-2 text-gray-800 transition-colors bg-gray-300 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">
                    Update Mission
                </button>
            </div>
        </form>

        <!-- Bagian Tambahan: Volunteers, Communities, Documentation -->
        <div class="space-y-6">

            @if ($mission->status === 'completed')
                <form action="{{ route('admin.reports.update', $mission->report_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h2 class="flex items-center mb-4 text-xl font-semibold text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Detail Penyelesaian Misi
                        </h2>

                        <div class="space-y-2">
                            <label for="completion_details" class="block text-sm font-medium text-gray-700">
                                Catatan Penyelesaian (Hasil, Kendala, dll.)
                            </label>
                            <textarea name="completion_details" id="completion_details" rows="6"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Contoh: Misi berjalan lancar, berhasil mengumpulkan 50kg sampah plastik. Kendala utama adalah akses ke lokasi yang sulit dijangkau.">{{ old('completion_details', $mission->report->completion_details) }}</textarea>

                            @error('completion_details')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror

                            <p class="text-xs text-gray-500">
                                Informasi ini akan ditampilkan kepada publik di halaman detail misi.
                            </p>
                        </div>

                        <input type="number" name="completed_by_user_id" value="{{ auth()->user()->id }}" hidden>
                        <input type="text" name="status" value="completed" hidden>
                        <input type="number" name="is_donation" value="0" hidden>

                        <div class="mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition duration-200 bg-blue-600 rounded-md hover:bg-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Detail Penyelesaian
                            </button>
                        </div>
                    </div>
                </form>
            @endif






            <!-- Volunteers Section -->
            <div class="p-6 bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Volunteers</h2>

                    @if (!$mission->is_point_shared)
                        <form action="{{ route('admin.missions.sharePoint', $mission->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white transition rounded-md bg-emerald-600 hover:bg-emerald-700">
                                Bagikan Point
                            </button>
                        </form>
                    @endif
                </div>

                @if ($volunteers->count() > 0)
                    <div class="overflow-x-auto">
                        <table id="volunteerTable"
                            class="min-w-full overflow-hidden border border-gray-200 rounded-md table-auto">
                            <thead class="text-sm font-semibold text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left">No</th>
                                    <th class="px-4 py-3 text-left">Name</th>
                                    <th class="px-4 py-3 text-left">Role</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Leader</th>
                                    <th class="px-4 py-3 text-left">Sertifikat</th>
                                    <th class="px-4 py-3 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm bg-white divide-y divide-gray-200">
                                @foreach ($volunteers as $volunteer)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $volunteer->id }}</td>

                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                @if ($volunteer->is_leader)
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 mr-2 text-yellow-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 00-2-2H5z" />
                                                    </svg>
                                                @endif
                                                <span
                                                    class="font-medium text-gray-900">{{ $volunteer->user->name }}</span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-3">{{ $volunteer->user->role }}</td>

                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium capitalize
                                            @if ($volunteer->participation_status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($volunteer->participation_status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($volunteer->participation_status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                                {{ $volunteer->participation_status }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3">
                                            @if ($volunteer->is_leader)
                                                <span
                                                    class="px-2 py-1 text-xs font-bold text-yellow-800 bg-yellow-100 rounded-full">LEADER</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">MEMBER</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3">
                                            @if ($volunteer->certificate_url)
                                                <a href="{{ asset('storage/' . $volunteer->certificate_url) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 transition bg-green-100 rounded-full hover:bg-green-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat
                                                </a>
                                            @elseif($volunteer->participation_status == 'attended')
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                                                    Belum Dibuat
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-3">
                                            <button
                                                onclick="openEditModal({{ $volunteer->id }}, '{{ $volunteer->user->name }}', '{{ $volunteer->participation_status }}', {{ $volunteer->is_leader ? 'true' : 'false' }})"
                                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-4 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <p class="italic text-gray-500">No volunteers assigned to this mission.</p>
                    </div>
                @endif
            </div>


            <!-- Section: Generate Certificate -->
            <div class="overflow-hidden bg-white rounded-lg shadow-md">

                {{-- Cek apakah ada volunteer yang hadir. Jika tidak, tampilkan pesan. --}}
                @if ($volunteers->where('participation_status', 'attended')->count() > 0)
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Generate Sertifikat Massal</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Gunakan form ini untuk membuat sertifikat bagi
                            <span
                                class="font-bold text-blue-600">{{ $volunteers->where('participation_status', 'attended')->count() }}
                                volunteer</span>
                            yang statusnya 'Attended'.
                        </p>
                    </div>

                    <form id="certificateForm" action="{{ route('admin.missions.certificates.generate') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="mission_id" value="{{ $mission->id }}">

                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Certificate Signer Title -->
                                {{-- <div> --}}
                                    {{-- <label for="certificate_title"
                                        class="block mb-2 text-sm font-medium text-gray-700">Title Misi <span
                                            class="text-red-500">*</span></label> --}}
                                    <input type="text" name="title" id="certificate_title"
                                        placeholder="Misi Surabaya"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required value="misi" hidden>

                                    {{-- @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror --}}
                                {{-- </div> --}}

                                <!-- Certificate Date -->
                                <div>
                                    <label for="certificate_date"
                                        class="block mb-2 text-sm font-medium text-gray-700">Tanggal Sertifikat <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="date" id="certificate_date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required value="{{ date('Y-m-d') }}">
                                    @error('date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Footer -->
                        <div class="flex justify-end px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <button id="generateBtn" type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:bg-blue-300 disabled:cursor-not-allowed">
                                <svg id="btnIcon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <svg id="btnSpinner" class="hidden w-5 h-5 mr-3 -ml-1 text-white animate-spin"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span id="btnText">Generate Sertifikat</span>
                            </button>
                        </div>
                    </form>
                @else
                    {{-- Pesan yang ditampilkan jika tidak ada volunteer yang hadir --}}
                    <div class="p-6 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700">Sertifikat Belum Dapat Dibuat</h3>
                            <p class="mt-1 text-sm">Tidak ada volunteer yang tercatat hadir (Attended) pada misi ini.</p>
                        </div>
                    </div>
                @endif
            </div>



        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden w-full h-full overflow-y-auto bg-gray-600 bg-opacity-50">
        <div class="relative p-5 mx-auto bg-white border rounded-md shadow-lg top-20 w-96">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">
                        Edit Status Volunteer
                    </h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="editForm" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Volunteer Name
                        </label>
                        <input type="text" id="volunteerName" readonly
                            class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-md bg-gray-50">
                    </div>

                    <div class="mb-4">
                        <label for="participation_status" class="block mb-2 text-sm font-medium text-gray-700">
                            Participation Status
                        </label>
                        <select name="participation_status" id="participation_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="attended">Attended</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-700">
                            Leadership Status
                        </label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="radio" name="is_leader" value="1"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 form-radio focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Leader</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_leader" value="0"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 form-radio focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Member</span>
                            </label>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex items-center justify-end pt-4 space-x-3 border-t">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Status
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
            const certForm = document.getElementById('certificateForm');

            if (certForm) {
                certForm.addEventListener('submit', function() {
                    const generateBtn = document.getElementById('generateBtn');
                    const btnIcon = document.getElementById('btnIcon');
                    const btnSpinner = document.getElementById('btnSpinner');
                    const btnText = document.getElementById('btnText');

                    // Disable button to prevent double-clicking
                    generateBtn.disabled = true;

                    // Show spinner and change text
                    btnIcon.classList.add('hidden');
                    btnSpinner.classList.remove('hidden');
                    btnText.textContent = 'Generating...';
                });
            }
        });

        function openEditModal(missionVolunteer, volunteerName, participationStatus, isLeader) {
            // Set form action URL
            document.getElementById('editForm').action = `/admin/missions/update/volunteer/${missionVolunteer}`;

            // Fill form data
            document.getElementById('volunteerName').value = volunteerName;
            document.getElementById('participation_status').value = participationStatus;

            // Set leadership status radio button
            const leaderRadio = document.querySelector('input[name="is_leader"][value="1"]');
            const memberRadio = document.querySelector('input[name="is_leader"][value="0"]');

            if (isLeader) {
                leaderRadio.checked = true;
            } else {
                memberRadio.checked = true;
            }

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const volunteerSelect = document.getElementById('assigned_volunteer_id');

            assignTypeSelect.addEventListener('change', function() {
                if (this.value === 'volunteer') {
                    volunteerSelect.disabled = false;

                } else {
                    volunteerSelect.disabled = true;
                    volunteerSelect.value = '';
                }
            });


            if (assignTypeSelect.value !== 'volunteer') {
                volunteerSelect.disabled = true;
            }
        });

        $(document).ready(function() {

            $('#volunteerTable').DataTable({

            });
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
    </script>
@endpush

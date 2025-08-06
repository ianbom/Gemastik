@extends('admin.layouts.app')

@section('content')
    <div class="container px-4 py-8 mx-auto">
        <div class="mx-auto max-w-7xl">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="mb-2 text-3xl font-bold text-gray-900">Buat Sertifikat Massal</h1>
                <p class="text-gray-600">Pilih misi untuk menghasilkan sertifikat bagi semua volunteer yang hadir.</p>
            </div>

            @include('admin.components.notification')

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md">
                <form action="{{ route('admin.missions.certificates.generate') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-6">
                        <!-- Mission Selection -->
                        <div>
                            <label for="mission_id" class="block mb-2 text-sm font-medium text-gray-700">Pilih Misi yang
                                Selesai <span class="text-red-500">*</span></label>
                            <select name="mission_id" id="mission_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                                <option value="" disabled selected>-- Pilih Misi --</option>
                                @foreach ($missions as $mission)
                                    <option value="{{ $mission->id }}">{{ $mission->title }}</option>
                                @endforeach
                            </select>
                            @error('mission_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certificate Title -->
                        <div>
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Judul Penanda Tangan
                                (cth: Ketua Pelaksana) <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Certificate Date -->
                        <div>
                            <label for="date" class="block mb-2 text-sm font-medium text-gray-700">Tanggal Sertifikat
                                <span class="text-red-500">*</span></label>
                            <input type="date" name="date" id="date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Footer -->
                    <div class="flex justify-end px-6 py-4 border-t bg-gray-50">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Generate & Download ZIP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')

@section('content')
    @include('admin.components.notification')

    <div class="min-h-screen rounded-lg shadow-lg bg-gray-50">
        <div class="container px-4 py-8 mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="mb-2 text-3xl font-bold text-gray-900">Manajemen Merchandise</h1>
                        <p class="text-lg text-gray-600">Kelola dan pantau semua merchandise reward yang tersedia</p>
                    </div>
                    <div class="items-center hidden space-x-4 md:flex">
                        <div class="px-4 py-2 bg-white rounded-lg shadow-sm">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">System Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-4">
                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Merchandise</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $merchandise->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $merchandise->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Tidak Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $merchandise->where('is_active', false)->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-xl">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rata-rata Poin</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ number_format($merchandise->avg('points_cost'), 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">Filter Merchandise</h2>
                        <button type="button" id="toggleFilters" class="font-medium text-blue-600 hover:text-blue-800">
                            <span class="hidden md:inline">Sembunyikan Filter</span>
                            <svg class="w-5 h-5 md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="filterForm" class="p-6">
                    <form method="GET" action="{{ route('admin.merchandise.index') }}" class="space-y-6">
                        <!-- Row 1 -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Status Filter -->
                            <div class="space-y-2">
                                <label for="is_active" class="block text-sm font-semibold text-gray-700">Status</label>
                                <select name="is_active" id="is_active"
                                    class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif
                                    </option>
                                </select>
                            </div>

                            <!-- Search Filter -->
                            <div class="space-y-2">
                                <label for="search" class="block text-sm font-semibold text-gray-700">Cari
                                    Merchandise</label>
                                <div class="relative">
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        placeholder="Cari berdasarkan nama..."
                                        class="w-full px-4 py-3 pl-10 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Points Range Filter -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Range Poin</label>
                                <div class="flex space-x-3">
                                    <input type="number" name="points_min" value="{{ request('points_min') }}"
                                        placeholder="Min"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <input type="number" name="points_max" value="{{ request('points_max') }}"
                                        placeholder="Max"
                                        class="w-full px-4 py-3 transition-all duration-200 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col justify-end pt-6 space-y-3 border-t border-gray-100 sm:flex-row sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('admin.merchandise.index') }}"
                                class="px-6 py-3 font-medium text-center text-gray-700 transition-all duration-200 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset Filter
                            </a>
                            <button type="submit"
                                class="px-6 py-3 font-medium text-white transition-all duration-200 bg-blue-600 rounded-lg hover:bg-blue-700">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Terapkan Filter
                            </button>

                            <a href="{{ route('admin.merchandise.create') }}"
                                class="inline-flex items-center px-6 py-3 font-medium text-white transition-all duration-200 bg-green-600 rounded-lg hover:bg-green-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Merchandise
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Merchandise Grid/Table Toggle -->
            <div class="flex justify-end mb-6">
                <div class="p-1 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <button id="gridView"
                        class="px-4 py-2 text-sm font-medium text-gray-700 text-blue-700 bg-blue-100 rounded-md">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Grid
                    </button>
                    <button id="tableView"
                        class="px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 6h18m-9 8h9"></path>
                        </svg>
                        Tabel
                    </button>
                </div>
            </div>

            <!-- Merchandise Grid View -->
            <div id="merchandiseGrid" class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($merchandise as $item)
                    <div
                        class="overflow-hidden transition-all duration-300 bg-white border border-gray-100 shadow-sm rounded-xl hover:shadow-lg">
                        <div class="relative">
                            <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->name }}"
                                class="object-cover w-full h-48">
                            <div class="absolute top-3 right-3">
                                @if ($item->is_active)
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 border border-green-200 rounded-full">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 border border-red-200 rounded-full">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="mb-2 text-lg font-semibold text-gray-900 truncate" title="{{ $item->name }}">
                                {{ $item->name }}
                            </h3>
                            <p class="mb-4 text-sm text-gray-600 line-clamp-2" title="{{ $item->description }}">
                                {{ Str::limit($item->description, 80) }}
                            </p>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span
                                        class="text-lg font-bold text-gray-900">{{ number_format($item->points_cost) }}</span>
                                    <span class="ml-1 text-sm text-gray-500">poin</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">
                                    {{ $item->created_at->format('d M Y') }}
                                </span>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.merchandise.show', $item->id) }}"
                                        class="text-blue-600 transition-colors duration-150 hover:text-blue-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.merchandise.edit', $item->id) }}"
                                        class="text-green-600 transition-colors duration-150 hover:text-green-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.merchandise.destroy', $item->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 transition-colors duration-150 hover:text-red-800"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus merchandise ini?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Merchandise Table View -->
            <div id="merchandiseTable"
                class="hidden overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
                <div class="overflow-x-auto">
                    <table id="merchandiseDataTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    ID
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Gambar
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Nama Merchandise
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Deskripsi
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Biaya Poin
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Tanggal Dibuat
                                </th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($merchandise as $item)
                                <tr class="transition-colors duration-150 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        <span class="px-2 py-1 bg-gray-100 rounded-lg">#{{ $item->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-12 h-12 overflow-hidden rounded-lg">
                                            <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->name }}"
                                                class="object-cover w-full h-full">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="max-w-xs">
                                            <p class="font-medium truncate" title="{{ $item->name }}">
                                                {{ $item->name }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div class="max-w-xs">
                                            <p class="truncate" title="{{ $item->description }}">
                                                {{ Str::limit($item->description, 50) }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            {{ number_format($item->points_cost) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->is_active)
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 border border-green-200 rounded-full">
                                                Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 border border-red-200 rounded-full">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            {{-- <a href="{{ route('admin.merchandise.show', $item->id) }}"
                                                class="text-blue-600 transition-colors duration-150 hover:text-blue-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a> --}}
                                            <a href="{{ route('admin.merchandise.edit', $item->id) }}"
                                                class="text-green-600 transition-colors duration-150 hover:text-green-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                            {{-- <form action="{{ route('admin.merchandise.destroy', $item->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 transition-colors duration-150 hover:text-red-800"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus merchandise ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($merchandise instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="flex justify-center mt-8">
                    {{ $merchandise->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable for table view
            let merchandiseTable = $('#merchandiseDataTable').DataTable({
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                        orderable: false,
                        targets: -1
                    }, // Disable ordering on the last column (actions)
                    {
                        orderable: false,
                        targets: 1
                    } // Disable ordering on image column
                ],
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",

                    emptyTable: "Tidak ada data merchandise yang tersedia"
                }
            });

            // View toggle functionality
            $('#gridView').click(function() {
                $(this).addClass('bg-blue-100 text-blue-700').removeClass('hover:bg-gray-100');
                $('#tableView').removeClass('bg-blue-100 text-blue-700').addClass('hover:bg-gray-100');
                $('#merchandiseGrid').removeClass('hidden');
                $('#merchandiseTable').addClass('hidden');
                localStorage.setItem('merchandiseView', 'grid');
            });

            $('#tableView').click(function() {
                $(this).addClass('bg-blue-100 text-blue-700').removeClass('hover:bg-gray-100');
                $('#gridView').removeClass('bg-blue-100 text-blue-700').addClass('hover:bg-gray-100');
                $('#merchandiseGrid').addClass('hidden');
                $('#merchandiseTable').removeClass('hidden');
                localStorage.setItem('merchandiseView', 'table');

                // Redraw DataTable when switching to table view
                merchandiseTable.columns.adjust().draw();
            });

            // Remember user's view preference
            const savedView = localStorage.getItem('merchandiseView');
            if (savedView === 'table') {
                $('#tableView').click();
            }

            // Toggle filter functionality
            $('#toggleFilters').click(function() {
                const filterForm = $('#filterForm');
                const toggleText = $(this).find('span');

                if (filterForm.is(':visible')) {
                    filterForm.slideUp(300);
                    toggleText.text('Tampilkan Filter');
                } else {
                    filterForm.slideDown(300);
                    toggleText.text('Sembunyikan Filter');
                }
            });

            // Auto-hide filters on mobile if no active filters
            if (window.innerWidth < 768) {
                const hasActiveFilters =
                    {{ request()->hasAny(['is_active', 'search', 'points_min', 'points_max']) ? 'true' : 'false' }};

                if (!hasActiveFilters) {
                    $('#filterForm').hide();
                    $('#toggleFilters span').text('Tampilkan Filter');
                }
            }

            // Enhanced form interactions
            $('select, input').focus(function() {
                $(this).addClass('ring-2 ring-blue-500');
            }).blur(function() {
                $(this).removeClass('ring-2 ring-blue-500');
            });

            // Add loading state to filter button
            $('form').submit(function() {
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html(
                    '<svg class="inline w-5 h-5 mr-3 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...'
                    );
                submitBtn.prop('disabled', true);

                // Re-enable button after 3 seconds as fallback
                setTimeout(function() {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }, 3000);
            });

            // Smooth hover animations for grid cards
            $('.bg-white.rounded-xl.shadow-sm').hover(
                function() {
                    $(this).addClass('transform scale-[1.02] transition-transform duration-200');
                },
                function() {
                    $(this).removeClass('transform scale-[1.02] transition-transform duration-200');
                }
            );

            // Image error handling
            $('img').on('error', function() {
                $(this).attr('src',
                    'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik03NSA3NUgxMjVWMTI1SDc1Vjc1WiIgZmlsbD0iI0Q1RDlERCIvPgo8L3N2Zz4K'
                    );
                $(this).addClass('bg-gray-100');
            });

            // Points formatting
            $('input[name="points_min"], input[name="points_max"]').on('input', function() {
                let value = $(this).val().replace(/[^\d]/g, '');
                if (value) {
                    $(this).val(parseInt(value).toLocaleString('id-ID'));
                }
            });

            // Tooltip functionality for truncated text
            $('[title]').hover(
                function() {
                    if (this.offsetWidth < this.scrollWidth) {
                        $(this).addClass('cursor-help');
                    }
                }
            );
        });
    </script>
@endpush

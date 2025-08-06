<form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-6">
                    <!-- Row 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Status Filter -->
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="on-progress" {{ request('status') == 'on-progress' ? 'selected' : '' }}>Diproses</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                 <option value="under-authority" {{ request('status') == 'under-authority' ? 'selected' : '' }}>Ditangani Pihak Berwenang</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <!-- City Filter -->
                        <div class="space-y-2">
                            <label for="city_id" class="block text-sm font-semibold text-gray-700">Kota</label>
                            <select name="city_id" id="city_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Semua Kota</option>
                                @if(isset($cities))
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- District Filter -->
                        <div class="space-y-2">
                            <label for="district_id" class="block text-sm font-semibold text-gray-700">Kecamatan</label>
                            <select name="district_id" id="district_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Semua Kecamatan</option>
                                @if(isset($districts))
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Category Filter -->
                       <div class="space-y-2">
                            <label for="category" class="block text-sm font-semibold text-gray-700">Kategori</label>
                            <select name="category" id="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Semua Kategori</option>
                                <option value="sampah-plastik" {{ request('category') == 'sampah-plastik' ? 'selected' : '' }}>Sampah Plastik</option>
                                <option value="pencemaran-air" {{ request('category') == 'pencemaran-air' ? 'selected' : '' }}>Pencemaran Air</option>
                                <option value="pencemaran-udara" {{ request('category') == 'pencemaran-udara' ? 'selected' : '' }}>Pencemaran Udara</option>
                                <option value="pencemaran-tanah" {{ request('category') == 'pencemaran-tanah' ? 'selected' : '' }}>Pencemaran Tanah</option>
                                <option value="limbah-industri" {{ request('category') == 'limbah-industri' ? 'selected' : '' }}>Limbah Industri</option>
                                <option value="emisi-gas-rumah-kaca" {{ request('category') == 'emisi-gas-rumah-kaca' ? 'selected' : '' }}>Emisi Gas Rumah Kaca</option>
                                <option value="penggundulan-kebakaran-hutan" {{ request('category') == 'penggundulan-kebakaran-hutan' ? 'selected' : '' }}>Penggundulan / Kebakaran Hutan</option>
                                <option value="naiknya-permukaan-air-laut" {{ request('category') == 'naiknya-permukaan-air-laut' ? 'selected' : '' }}>Naiknya Permukaan Air Laut</option>
                                <option value="limbah-pertanian-peternakan" {{ request('category') == 'limbah-pertanian-peternakan' ? 'selected' : '' }}>Limbah Pertanian / Peternakan</option>
                                <option value="lainnya" {{ request('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                    </div>

                    <!-- Row 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Search Filter -->
                        <div class="space-y-2">
                            <label for="search" class="block text-sm font-semibold text-gray-700">Cari Judul</label>
                            <div class="relative">
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                       placeholder="Cari berdasarkan judul..."
                                       class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Created Date Range -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Dibuat</label>
                            <div class="flex space-x-3">
                                <input type="date" name="created_from" value="{{ request('created_from') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <input type="date" name="created_to" value="{{ request('created_to') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>

                        <!-- Verified Date Range -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Verifikasi</label>
                            <div class="flex space-x-3">
                                <input type="date" name="verified_from" value="{{ request('verified_from') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <input type="date" name="verified_to" value="{{ request('verified_to') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.reports.index') }}"
                           class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium text-center">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset Filter
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>

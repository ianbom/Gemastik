@extends('admin.layouts.app')

@section('content')

@include('admin.components.notification')

<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Redeem #{{ $redeems->id }}</h1>
                    <p class="text-gray-600 text-lg">Update status dan informasi pengiriman</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.redeems.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800">Update Status Redeem</h2>
                    </div>

                    <form action="{{ route('admin.redeems.update', $redeems->id) }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Status Field -->
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-gray-700">Status Redeem</label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                                <option value="pending" {{ $redeems->status == 'pending' ? 'selected' : '' }}>
                                    🕐 Pending - Menunggu konfirmasi
                                </option>
                                <option value="processing" {{ $redeems->status == 'processing' ? 'selected' : '' }}>
                                    ⚙️ Processing - Sedang diproses
                                </option>
                                <option value="shipped" {{ $redeems->status == 'shipped' ? 'selected' : '' }}>
                                    🚚 Shipped - Sedang dikirim
                                </option>
                                <option value="completed" {{ $redeems->status == 'completed' ? 'selected' : '' }}>
                                    ✅ Completed - Selesai
                                </option>
                                <option value="cancelled" {{ $redeems->status == 'cancelled' ? 'selected' : '' }}>
                                    ❌ Cancelled - Dibatalkan
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Shipping Information Section -->
                        <div id="shippingSection" class="space-y-6 p-6 bg-blue-50 rounded-lg border border-blue-200" style="{{ in_array($redeems->status, ['shipped', 'completed']) ? '' : 'display: none;' }}">
                            <h3 class="text-lg font-semibold text-blue-800 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                Informasi Pengiriman
                            </h3>

                            <!-- Logistic Provider -->
                           <div class="space-y-2">
                                <label for="logistic" class="block text-sm font-semibold text-gray-700">Pilih Kurir</label>
                                <select name="logistic" id="logistic" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <option value="">Pilih Kurir</option>
                                    <option value="JNE" {{ old('logistic', $redeems->logistic) == 'JNE' ? 'selected' : '' }}>
                                        JNE - Jalur Nugraha Ekakurir
                                    </option>
                                    <option value="J&T" {{ old('logistic', $redeems->logistic) == 'J&T' ? 'selected' : '' }}>
                                        J&T Express
                                    </option>
                                    <option value="SiCepat" {{ old('logistic', $redeems->logistic) == 'SiCepat' ? 'selected' : '' }}>
                                        SiCepat Express
                                    </option>
                                    <option value="Pos Indonesia" {{ old('logistic', $redeems->logistic) == 'Pos Indonesia' ? 'selected' : '' }}>
                                        Pos Indonesia
                                    </option>
                                    <option value="Tiki" {{ old('logistic', $redeems->logistic) == 'Tiki' ? 'selected' : '' }}>
                                        Tiki (Titipan Kilat)
                                    </option>
                                    <option value="Anteraja" {{ old('logistic', $redeems->logistic) == 'Anteraja' ? 'selected' : '' }}>
                                        AnterAja
                                    </option>
                                    <option value="Lion Parcel" {{ old('logistic', $redeems->logistic) == 'Lion Parcel' ? 'selected' : '' }}>
                                        Lion Parcel
                                    </option>
                                </select>
                                @error('logistic')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tracking Number -->
                            <div class="space-y-2">
                                <label for="tracking_number" class="block text-sm font-semibold text-gray-700">Nomor Resi</label>
                                <div class="relative">
                                    <input type="text" name="tracking_number" id="tracking_number"
                                           value="{{ old('tracking_number', $redeems->tracking_number) }}"
                                           placeholder="Masukkan nomor resi pengiriman"
                                           class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('tracking_number')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500">Masukkan nomor resi yang diberikan oleh kurir</p>
                            </div>

                            <!-- Tracking Button -->
                            <div id="trackingButton" style="{{ $redeems->tracking_number ? '' : 'display: none;' }}">
                                <button type="button" id="trackShipment" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Lacak Pengiriman
                                </button>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-100">
                            <a href="{{ route('admin.redeems.index') }}" class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 font-medium text-center">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium inline-flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Redeem
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar - Redeem Details -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Current Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Saat Ini</h3>
                        <div class="space-y-4">
                            @php
                                $statusInfo = [
                                    'pending' => ['color' => 'yellow', 'icon' => '🕐', 'text' => 'Menunggu Konfirmasi'],
                                    'processing' => ['color' => 'orange', 'icon' => '⚙️', 'text' => 'Sedang Diproses'],
                                    'shipped' => ['color' => 'blue', 'icon' => '🚚', 'text' => 'Sedang Dikirim'],
                                    'completed' => ['color' => 'green', 'icon' => '✅', 'text' => 'Selesai'],
                                    'cancelled' => ['color' => 'red', 'icon' => '❌', 'text' => 'Dibatalkan']
                                ];
                                $current = $statusInfo[$redeems->status] ?? $statusInfo['pending'];
                            @endphp
                            <div class="flex items-center p-3 bg-{{ $current['color'] }}-50 rounded-lg border border-{{ $current['color'] }}-200">
                                <span class="text-2xl mr-3">{{ $current['icon'] }}</span>
                                <div>
                                    <p class="font-semibold text-{{ $current['color'] }}-800">{{ ucfirst($redeems->status) }}</p>
                                    <p class="text-sm text-{{ $current['color'] }}-600">{{ $current['text'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi User</h3>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ substr($redeems->user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">{{ $redeems->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $redeems->user->email }}</p>
                                </div>
                            </div>

                            @if($redeems->user->phone)
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                {{ $redeems->user->phone }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Merchandise Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Merchandise</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-4">
                                @if($redeems->merchandise->image_url)
                                    <img src="{{ Storage::url($redeems->merchandise->image_url) }}" alt="Merchandise" class="h-16 w-16 rounded-lg object-cover">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $redeems->merchandise->name }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ $redeems->merchandise->description }}</p>
                                    <div class="flex items-center mt-2">
                                        <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">
                                            {{ number_format($redeems->points_spent) }} Poin
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                @if($redeems->shipping_address)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Alamat Pengiriman
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $redeems->shipping_address }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Timeline/History -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Redeem Created</p>
                                    <p class="text-xs text-gray-500">{{ $redeems->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($redeems->updated_at != $redeems->created_at)
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                    <p class="text-xs text-gray-500">{{ $redeems->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Show/hide shipping section based on status
        $('#status').change(function() {
            const status = $(this).val();
            const shippingSection = $('#shippingSection');

            if (status === 'shipped' || status === 'completed') {
                shippingSection.slideDown(300);
                $('#logistic').prop('required', true);
                $('#tracking_number').prop('required', true);
            } else {
                shippingSection.slideUp(300);
                $('#logistic').prop('required', false);
                $('#tracking_number').prop('required', false);
            }
        });

        // Show/hide tracking button based on tracking number input
        $('#tracking_number').on('input', function() {
            const trackingNumber = $(this).val().trim();
            if (trackingNumber.length > 0) {
                $('#trackingButton').slideDown(200);
            } else {
                $('#trackingButton').slideUp(200);
            }
        });

        // Track shipment functionality
        $('#trackShipment').click(function() {
            const trackingNumber = $('#tracking_number').val().trim();
            const logistic = $('#logistic').val();

            if (!trackingNumber) {
                alert('Masukkan nomor resi terlebih dahulu');
                return;
            }

            if (!logistic) {
                alert('Pilih kurir terlebih dahulu');
                return;
            }

            // Open tracking URL based on logistic provider
            let trackingUrl = '';
            switch(logistic.toLowerCase()) {
                case 'jne':
                    trackingUrl = 'https://www.jne.co.id/id/tracking/trace/' + trackingNumber;
                    break;
                case 'j&t':
                    trackingUrl = 'https://www.jet.co.id/track/' + trackingNumber;
                    break;
                case 'sicepat':
                    trackingUrl = 'https://www.sicepat.com/checkAwb/' + trackingNumber;
                    break;
                case 'pos indonesia':
                    trackingUrl = 'https://www.posindonesia.co.id/id/tracking/' + trackingNumber;
                    break;
                case 'tiki':
                    trackingUrl = 'https://www.tiki.id/id/tracking/' + trackingNumber;
                    break;
                case 'anteraja':
                    trackingUrl = 'https://www.anteraja.id/tracking/' + trackingNumber;
                    break;
                case 'lion parcel':
                    trackingUrl = 'https://www.lionparcel.com/tracking/' + trackingNumber;
                    break;
                default:
                    alert('Tracking URL tidak tersedia untuk kurir ' + logistic);
                    return;
            }

            window.open(trackingUrl, '_blank');
        });

        // Form validation
        $('form').submit(function(e) {
            const status = $('#status').val();

            if ((status === 'shipped' || status === 'completed')) {
                const logistic = $('#logistic').val();
                const trackingNumber = $('#tracking_number').val().trim();

                if (!logistic) {
                    e.preventDefault();
                    alert('Pilih kurir untuk status shipped/completed');
                    $('#logistic').focus();
                    return false;
                }

                if (!trackingNumber) {
                    e.preventDefault();
                    alert('Masukkan nomor resi untuk status shipped/completed');
                    $('#tracking_number').focus();
                    return false;
                }
            }
        });

        // Auto-uppercase tracking number
        $('#tracking_number').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });

        // Copy tracking number functionality
        $('#tracking_number').on('dblclick', function() {
            $(this).select();
            document.execCommand('copy');

            // Show temporary tooltip
            const originalPlaceholder = $(this).attr('placeholder');
            $(this).attr('placeholder', 'Nomor resi disalin!');
            setTimeout(() => {
                $(this).attr('placeholder', originalPlaceholder);
            }, 2000);
        });
    });
</script>
@endpush

@php
    // Cek apakah ada pesan di session, prioritas: success, error, warning, info
    $message = session('success') ?? session('error') ?? session('warning') ?? session('info');

    if ($message) {
        $type = session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : 'info'));

        $colors = [
            'success' => 'green',
            'error'   => 'red',
            'warning' => 'yellow',
            'info'    => 'blue',
        ];

        $icons = [
            'success' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            'error'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />',
            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />',
            'info'    => '<path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />',
        ];

        $color = $colors[$type];
        $icon = $icons[$type];
    }
@endphp

@if ($message)
<div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-50">
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
        {{-- Panel Notifikasi --}}
        <div id="notification-panel" class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 transition transform ease-out duration-300">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="shrink-0">
                        {{-- Ikon Dinamis --}}
                        <svg class="h-6 w-6 text-{{ $color }}-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            {!! $icon !!}
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        {{-- Judul dan Pesan Dinamis --}}
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($type) }}!</p>
                        <p class="mt-1 text-sm text-gray-500">{{ $message }}</p>
                    </div>
                    <div class="ml-4 flex shrink-0">
                        <button id="close-notification-btn" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const notificationPanel = document.getElementById('notification-panel');

    // Jika panel notifikasi ada di halaman...
    if (notificationPanel) {
        const closeBtn = document.getElementById('close-notification-btn');

        // Fungsi untuk menutup notifikasi dengan animasi
        const closeNotification = () => {
            notificationPanel.classList.remove('translate-y-0', 'opacity-100', 'sm:translate-x-0');
            notificationPanel.classList.add('opacity-0');

            // Hapus elemen dari DOM setelah animasi selesai
            setTimeout(() => {
                notificationPanel.remove();
            }, 300); // Sesuaikan dengan durasi transisi
        };

        // Tampilkan notifikasi dengan animasi saat halaman dimuat
        setTimeout(() => {
            notificationPanel.classList.add('translate-y-0', 'opacity-100', 'sm:translate-x-0');
        }, 100);

        // Tutup notifikasi setelah 5 detik
        const autoCloseTimeout = setTimeout(closeNotification, 5000);

        // Tutup notifikasi jika tombol close diklik
        closeBtn.addEventListener('click', () => {
            clearTimeout(autoCloseTimeout); // Batalkan auto-close jika ditutup manual
            closeNotification();
        });
    }
});
</script>
@endpush

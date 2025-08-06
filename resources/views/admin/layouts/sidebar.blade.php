<div>
    {{-- Mobile sidebar --}}
    <div id="mobile-sidebar" class="relative z-50 hidden lg:hidden" role="dialog" aria-modal="true">
        <div id="sidebar-backdrop"
            class="fixed inset-0 transition-opacity duration-300 ease-linear opacity-0 bg-gray-900/80" aria-hidden="true">
        </div>
        <div class="fixed inset-0 flex">
            <div id="mobile-sidebar-panel"
                class="relative flex flex-1 w-full max-w-xs mr-16 transition duration-300 ease-in-out transform -translate-x-full">
                <div class="absolute top-0 flex justify-center w-16 pt-5 left-full">
                    <button id="mobile-close-btn" type="button" class="-m-2.5 p-2.5">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div
                    class="flex flex-col px-6 pb-4 overflow-y-auto bg-gradient-to-b from-emerald-700 to-emerald-900 grow gap-y-5">
                    <div class="flex items-center h-16 shrink-0">
                        <img class="w-auto h-8" src="/LogoSobatBumi.png" alt="Sobat Bumi">
                        <span class="ml-3 font-semibold text-white">Admin Sobat Bumi</span>
                    </div>
                    @include('admin.layouts.nav-link')
                </div>
            </div>
        </div>
    </div>

    {{-- Static sidebar for desktop --}}
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        {{-- PERUBAHAN: Latar belakang hijau dan padding di sini --}}
        <div class="flex flex-col px-6 overflow-y-auto bg-gradient-to-b from-emerald-600 to-emerald-800 grow gap-y-5">
            <div class="flex items-center h-16 shrink-0">
                <div class="p-1 border border-white rounded-md bg-white/70 backdrop-blur-sm">
                    <img class="w-auto h-8" src="/LogoSobatBumi.png" alt="Sobat Bumi">
                </div>
                <span class="ml-3 font-bold text-white">
                    Admin
                </span>
                <span
                    class="ml-1 font-bold text-transparent bg-clip-text bg-gradient-to-r from-lime-200 via-emerald-300 to-green-500">
                    SobatBumi
                </span>
            </div>

            @include('admin.layouts.nav-link')
        </div>
    </div>

    {{-- Header untuk mobile --}}
    {{-- <div class="sticky top-0 z-40 flex items-center px-4 py-4 bg-white shadow-sm gap-x-6 sm:px-6 lg:hidden">
        <button id="open-sidebar-btn" type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <div class="flex-1 text-sm font-semibold text-gray-900">Dashboard</div>
        <a href="#">
            <span class="sr-only">Your profile</span>
            <img class="w-8 h-8 rounded-full bg-gray-50"
                src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                alt="">
        </a>
    </div> --}}

    {{-- Main content area --}}
    <main class="lg:pl-72">
        @include('admin.layouts.header')
        <div class="px-4 py-5 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>
</div>

@push('scripts')
    {{-- Script untuk toggle sidebar mobile (tidak ada perubahan) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!sidebar) return; // Hentikan jika elemen tidak ada

            const openSidebar = () => {
                sidebar.classList.remove('hidden');
                // Gunakan timeout kecil agar transisi berjalan setelah display berubah
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                    sidebarPanel.classList.remove('-translate-x-full');
                    sidebarPanel.classList.add('translate-x-0');
                    closeBtn.parentElement.classList.remove('opacity-0');
                    closeBtn.parentElement.classList.add('opacity-100');
                }, 10);
            };

            const closeSidebar = () => {
                backdrop.classList.remove('opacity-100');
                backdrop.classList.add('opacity-0');
                sidebarPanel.classList.remove('translate-x-0');
                sidebarPanel.classList.add('-translate-x-full');
                closeBtn.parentElement.classList.remove('opacity-100');
                closeBtn.parentElement.classList.add('opacity-0');
                // Sembunyikan elemen setelah transisi selesai (300ms)
                setTimeout(() => {
                    sidebar.classList.add('hidden');
                }, 300);
            };

            // Tambahkan event listener
            openBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            backdrop.addEventListener('click', closeSidebar);
        });
    </script>
@endpush

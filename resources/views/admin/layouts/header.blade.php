@php
    use Carbon\Carbon;
    $today = Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y');
@endphp
<header class="sticky top-0 z-40 shadow-sm bg-white/75 backdrop-blur-lg">
    <div class="flex items-center justify-between h-16 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

        {{-- Tombol Hamburger untuk Mobile --}}
        <div class="flex items-center gap-x-6 lg:hidden">
            <button id="open-sidebar-btn" type="button" class="-m-2.5 p-2.5 text-gray-700">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        {{-- Judul Halaman (Opsional, bisa diisi dinamis) --}}
        <div class="hidden lg:flex lg:flex-1">
            <h1 class="text-lg font-semibold text-gray-800">{{ $today }}</h1>
        </div>

        {{-- Bagian Kanan Header: Notifikasi & Profil --}}
        <div class="flex items-center justify-end flex-1 gap-x-4">
            @auth
                <!-- Pemisah Vertikal -->
                <div class="hidden sm:block sm:h-6 sm:w-px sm:bg-gray-200" aria-hidden="true"></div>

                <!-- Dropdown Profil Pengguna -->
                <div class="relative">
                    <button type="button"
                        class="flex items-center p-1 text-sm transition-colors duration-200 bg-white rounded-full gap-x-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 hover:bg-gray-50"
                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                        <img class="object-cover w-8 h-8 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=107555&color=ffffff&size=128"
                            alt="User avatar">
                        <span class="hidden lg:flex lg:items-center">
                            <span class="text-sm font-semibold text-gray-900"
                                aria-hidden="true">{{ Auth::user()->name }}</span>
                            <svg class="w-5 h-5 ml-2 text-gray-400 transition-transform duration-200" id="dropdown-arrow"
                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="user-menu"
                        class="absolute right-0 z-10 w-56 py-2 mt-2 transition-all duration-200 origin-top-right transform scale-95 bg-white rounded-lg shadow-lg opacity-0 pointer-events-none ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 group hover:bg-red-50"
                                role="menuitem">
                                <svg class="w-5 h-5 mr-3 text-red-400 group-hover:text-red-500" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="text-sm font-semibold leading-6 text-gray-900 hover:text-emerald-600">Log in <span
                        aria-hidden="true">→</span></a>
            @endauth
        </div>
    </div>
</header>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- User Profile Dropdown (Desktop) ---
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            const dropdownArrow = document.getElementById('dropdown-arrow');

            if (userMenuButton && userMenu && dropdownArrow) {
                userMenuButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true';

                    if (isExpanded) {
                        // Menutup dropdown
                        userMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                        dropdownArrow.classList.remove('rotate-180');
                    } else {
                        // Membuka dropdown
                        userMenu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                        dropdownArrow.classList.add('rotate-180');
                    }
                    userMenuButton.setAttribute('aria-expanded', !isExpanded);
                });

                // Menutup dropdown saat mengklik di luar
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        if (userMenuButton.getAttribute('aria-expanded') === 'true') {
                            userMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                            dropdownArrow.classList.remove('rotate-180');
                            userMenuButton.setAttribute('aria-expanded', 'false');
                        }
                    }
                });

                // Menutup dropdown dengan tombol Escape
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && userMenuButton.getAttribute('aria-expanded') === 'true') {
                        userMenu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                        dropdownArrow.classList.remove('rotate-180');
                        userMenuButton.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // --- Tombol Hamburger (Jika ada di layout ini) ---
            // Logika untuk tombol hamburger sudah ada di file sidebar.blade.php Anda,
            // jadi tidak perlu diduplikasi di sini kecuali jika ini adalah file yang terpisah.
        });
    </script>
@endpush

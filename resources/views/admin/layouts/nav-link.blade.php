<nav class="flex flex-col flex-1">
    <ul role="list" class="flex flex-col flex-1 gap-y-7">
        <li>
            <div class="font-semibold text-xs/6 text-sky-200">Dashboard</div>
            <ul role="list" class="mt-2 -mx-2 space-y-1">
                <li>
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.dashboard.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.dashboard.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.5 6.5C2.5 4.61438 2.5 3.67157 3.08579 3.08579C3.67157 2.5 4.61438 2.5 6.5 2.5C8.38562 2.5 9.32843 2.5 9.91421 3.08579C10.5 3.67157 10.5 4.61438 10.5 6.5V17.5C10.5 19.3856 10.5 20.3284 9.91421 20.9142C9.32843 21.5 8.38562 21.5 6.5 21.5C4.61438 21.5 3.67157 21.5 3.08579 20.9142C2.5 20.3284 2.5 19.3856 2.5 17.5V6.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M13.5 15.5C13.5 13.6144 13.5 12.6716 14.0858 12.0858C14.6716 11.5 15.6144 11.5 17.5 11.5C19.3856 11.5 20.3284 11.5 20.9142 12.0858C21.5 12.6716 21.5 13.6144 21.5 15.5V17.5C21.5 19.3856 21.5 20.3284 20.9142 20.9142C20.3284 21.5 19.3856 21.5 17.5 21.5C15.6144 21.5 14.6716 21.5 14.0858 20.9142C13.5 20.3284 13.5 19.3856 13.5 17.5V15.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M13.5 5.5C13.5 4.56812 13.5 4.10218 13.6522 3.73463C13.8552 3.24458 14.2446 2.85523 14.7346 2.65224C15.1022 2.5 15.5681 2.5 16.5 2.5H18.5C19.4319 2.5 19.8978 2.5 20.2654 2.65224C20.7554 2.85523 21.1448 3.24458 21.3478 3.73463C21.5 4.10218 21.5 4.56812 21.5 5.5C21.5 6.43188 21.5 6.89782 21.3478 7.26537C21.1448 7.75542 20.7554 8.14477 20.2654 8.34776C19.8978 8.5 19.4319 8.5 18.5 8.5H16.5C15.5681 8.5 15.1022 8.5 14.7346 8.34776C14.2446 8.14477 13.8552 7.75542 13.6522 7.26537C13.5 6.89782 13.5 6.43188 13.5 5.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                        </svg>

                        Dashboard
                    </a>
                </li>

            </ul>
        </li>
        <li>
            <div class="font-semibold text-xs/6 text-sky-200">Laporan dan Misi</div>
            <ul role="list" class="mt-2 -mx-2 space-y-1">
                <li>
                    {{-- Reports --}}
                    <a href="{{ route('admin.reports.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.reports.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M16 4.00195C18.175 4.01406 19.3529 4.11051 20.1213 4.87889C21 5.75757 21 7.17179 21 10.0002V16.0002C21 18.8286 21 20.2429 20.1213 21.1215C19.2426 22.0002 17.8284 22.0002 15 22.0002H9C6.17157 22.0002 4.75736 22.0002 3.87868 21.1215C3 20.2429 3 18.8286 3 16.0002V10.0002C3 7.17179 3 5.75757 3.87868 4.87889C4.64706 4.11051 5.82497 4.01406 8 4.00195"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M7 14.5H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7 18H12.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z"
                                stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        Laporan
                    </a>

                </li>
                <li>
                    {{-- Missions --}}
                    <a href="{{ route('admin.missions.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.missions.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.missions.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.1497 8.80219L9.70794 9.40825L10.1497 8.80219ZM12 3.10615L11.4925 3.65833C11.7794 3.9221 12.2206 3.9221 12.5075 3.65833L12 3.10615ZM13.8503 8.8022L14.2921 9.40826L13.8503 8.8022ZM12 9.67598L12 10.426H12L12 9.67598ZM10.5915 8.19612C9.90132 7.69298 9.16512 7.08112 8.60883 6.43627C8.03452 5.77053 7.75 5.18233 7.75 4.71476H6.25C6.25 5.73229 6.82845 6.66885 7.47305 7.41607C8.13569 8.18419 8.97435 8.87349 9.70794 9.40825L10.5915 8.19612ZM7.75 4.71476C7.75 3.65612 8.27002 3.05231 8.8955 2.84182C9.54754 2.62238 10.5199 2.76435 11.4925 3.65833L12.5075 2.55398C11.2302 1.37988 9.70254 0.987559 8.41707 1.42016C7.10502 1.8617 6.25 3.09623 6.25 4.71476H7.75ZM14.2921 9.40826C15.0257 8.8735 15.8643 8.18421 16.527 7.41608C17.1716 6.66886 17.75 5.73229 17.75 4.71475H16.25C16.25 5.18234 15.9655 5.77055 15.3912 6.43629C14.8349 7.08113 14.0987 7.69299 13.4085 8.19613L14.2921 9.40826ZM17.75 4.71475C17.75 3.09622 16.895 1.8617 15.5829 1.42016C14.2975 0.987559 12.7698 1.37988 11.4925 2.55398L12.5075 3.65833C13.4801 2.76435 14.4525 2.62238 15.1045 2.84181C15.73 3.0523 16.25 3.65612 16.25 4.71475H17.75ZM9.70794 9.40825C10.463 9.95869 11.0618 10.426 12 10.426L12 8.92598C11.635 8.92598 11.4347 8.81074 10.5915 8.19612L9.70794 9.40825ZM13.4085 8.19613C12.5653 8.81074 12.365 8.92598 12 8.92598L12 10.426C12.9382 10.426 13.537 9.9587 14.2921 9.40826L13.4085 8.19613Z"
                                fill="currentColor" />
                            <path
                                d="M4 21.3884H6.25993C7.27079 21.3884 8.29253 21.4937 9.27633 21.6964C11.0166 22.0549 12.8488 22.0983 14.6069 21.8138C15.4738 21.6734 16.326 21.4589 17.0975 21.0865C17.7939 20.7504 18.6469 20.2766 19.2199 19.7459C19.7921 19.216 20.388 18.3487 20.8109 17.6707C21.1736 17.0894 20.9982 16.3762 20.4245 15.943C19.7873 15.4619 18.8417 15.462 18.2046 15.9433L16.3974 17.3084C15.697 17.8375 14.932 18.3245 14.0206 18.4699C13.911 18.4874 13.7962 18.5033 13.6764 18.5172M13.6764 18.5172C13.6403 18.5214 13.6038 18.5254 13.5668 18.5292M13.6764 18.5172C13.8222 18.486 13.9669 18.396 14.1028 18.2775C14.746 17.7161 14.7866 16.77 14.2285 16.1431C14.0991 15.9977 13.9475 15.8764 13.7791 15.7759C10.9817 14.1074 6.62942 15.3782 4 17.2429M13.6764 18.5172C13.6399 18.525 13.6033 18.5292 13.5668 18.5292M13.5668 18.5292C13.0434 18.5829 12.4312 18.5968 11.7518 18.5326"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Misi Lingkungan
                    </a>
                </li>


                <li>
                    {{-- Content --}}
                    <a href="{{ route('admin.contents.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.contents.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.contents.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M21.5 8H2.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M10.5 2.5L7 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M17 2.5L13.5 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M15 14.5C15 13.8666 14.338 13.4395 13.014 12.5852C11.6719 11.7193 11.0008 11.2863 10.5004 11.6042C10 11.9221 10 12.7814 10 14.5C10 16.2186 10 17.0779 10.5004 17.3958C11.0008 17.7137 11.6719 17.2807 13.014 16.4148C14.338 15.5605 15 15.1334 15 14.5Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Konten Edukasi
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <div class="font-semibold text-xs/6 text-sky-200">Sertifikat dan Badges</div>
            <ul role="list" class="mt-2 -mx-2 space-y-1">
                <li>
                    {{-- Sertifikat --}}
                    <a href="{{ route('admin.certificates.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.certificates.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.certificates.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="16" r="3" stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M12 19.2599L9.73713 21.4293C9.41306 21.74 9.25102 21.8953 9.1138 21.9491C8.80111 22.0716 8.45425 21.9667 8.28977 21.7C8.21758 21.583 8.19509 21.3719 8.1501 20.9496C8.1247 20.7113 8.112 20.5921 8.07345 20.4922C7.98715 20.2687 7.80579 20.0948 7.57266 20.0121C7.46853 19.9751 7.3442 19.963 7.09553 19.9386C6.65512 19.8955 6.43491 19.8739 6.31283 19.8047C6.03463 19.647 5.92529 19.3145 6.05306 19.0147C6.10913 18.8832 6.27116 18.7278 6.59523 18.4171L8.07345 16.9999L9.1138 15.9596"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M12 19.2599L14.2629 21.4294C14.5869 21.7401 14.749 21.8954 14.8862 21.9492C15.1989 22.0717 15.5457 21.9668 15.7102 21.7001C15.7824 21.5831 15.8049 21.372 15.8499 20.9497C15.8753 20.7113 15.888 20.5921 15.9265 20.4923C16.0129 20.2688 16.1942 20.0949 16.4273 20.0122C16.5315 19.9752 16.6558 19.9631 16.9045 19.9387C17.3449 19.8956 17.5651 19.874 17.6872 19.8048C17.9654 19.6471 18.0747 19.3146 17.9469 19.0148C17.8909 18.8832 17.7288 18.7279 17.4048 18.4172L15.9265 17L15 16.0735"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M17.3197 17.9957C19.2921 17.9748 20.3915 17.8512 21.1213 17.1213C22 16.2426 22 14.8284 22 12V8C22 5.17157 22 3.75736 21.1213 2.87868C20.2426 2 18.8284 2 16 2L8 2C5.17157 2 3.75736 2 2.87868 2.87868C2 3.75736 2 5.17157 2 8L2 12C2 14.8284 2 16.2426 2.87868 17.1213C3.64706 17.8897 4.82497 17.9862 7 17.9983"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M9 6L15 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7 9.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>

                        <span class="truncate">Sertifikat</span>
                    </a>
                </li>
                <li>
                    {{-- Badges --}}
                    <a href="{{ route('admin.badges.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.badges.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.badges.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.1459 7.02251C11.5259 6.34084 11.7159 6 12 6C12.2841 6 12.4741 6.34084 12.8541 7.02251L12.9524 7.19887C13.0603 7.39258 13.1143 7.48944 13.1985 7.55334C13.2827 7.61725 13.3875 7.64097 13.5972 7.68841L13.7881 7.73161C14.526 7.89857 14.895 7.98205 14.9828 8.26432C15.0706 8.54659 14.819 8.84072 14.316 9.42898L14.1858 9.58117C14.0429 9.74833 13.9714 9.83191 13.9392 9.93531C13.9071 10.0387 13.9179 10.1502 13.9395 10.3733L13.9592 10.5763C14.0352 11.3612 14.0733 11.7536 13.8435 11.9281C13.6136 12.1025 13.2682 11.9435 12.5773 11.6254L12.3986 11.5431C12.2022 11.4527 12.1041 11.4075 12 11.4075C11.8959 11.4075 11.7978 11.4527 11.6014 11.5431L11.4227 11.6254C10.7318 11.9435 10.3864 12.1025 10.1565 11.9281C9.92674 11.7536 9.96476 11.3612 10.0408 10.5763L10.0605 10.3733C10.0821 10.1502 10.0929 10.0387 10.0608 9.93531C10.0286 9.83191 9.95713 9.74833 9.81418 9.58117L9.68403 9.42898C9.18097 8.84072 8.92945 8.54659 9.01723 8.26432C9.10501 7.98205 9.47396 7.89857 10.2119 7.73161L10.4028 7.68841C10.6125 7.64097 10.7173 7.61725 10.8015 7.55334C10.8857 7.48944 10.9397 7.39258 11.0476 7.19887L11.1459 7.02251Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M19 9C19 12.866 15.866 16 12 16C8.13401 16 5 12.866 5 9C5 5.13401 8.13401 2 12 2C15.866 2 19 5.13401 19 9Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M7.35111 15L6.71424 17.323C6.0859 19.6148 5.77173 20.7607 6.19097 21.3881C6.3379 21.6079 6.535 21.7844 6.76372 21.9008C7.41635 22.2331 8.42401 21.7081 10.4393 20.658C11.1099 20.3086 11.4452 20.1339 11.8014 20.0959C11.9335 20.0818 12.0665 20.0818 12.1986 20.0959C12.5548 20.1339 12.8901 20.3086 13.5607 20.658C15.576 21.7081 16.5837 22.2331 17.2363 21.9008C17.465 21.7844 17.6621 21.6079 17.809 21.3881C18.2283 20.7607 17.9141 19.6148 17.2858 17.323L16.6489 15"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>


                        <span class="truncate">Badges</span>
                    </a>
                </li>
                <li>
                    {{-- Merchandise --}}
                    <a href="{{ route('admin.merchandise.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.merchandise.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.merchandise.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 12H2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M12 2V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M13 12C13 14.2091 14.7909 16 17 16" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M11 12C11 14.2091 9.20914 16 7 16" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path
                                d="M12 10.0352C12 8.54529 13.014 7.24659 14.4594 6.88524C16.0631 6.48431 17.5158 7.93697 17.1148 9.5407C16.7535 10.9861 15.4548 12.0001 13.9649 12.0001H12V10.0352Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M12.0001 10.0352C12.0001 8.54529 10.9861 7.24659 9.5407 6.88524C7.93698 6.48431 6.48431 7.93697 6.88524 9.5407C7.24659 10.9861 8.54529 12.0001 10.0352 12.0001H12.0001V10.0352Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path
                                d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                                stroke="currentColor" stroke-width="1.5" />
                        </svg>


                        <span class="truncate">Merchandise</span>
                    </a>
                </li>

                <li>
                    {{-- Quiz --}}
                    <a href="{{ route('admin.quizzes.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.quizzes.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">

                        {{-- PERUBAHAN: Ikon diubah menjadi ikon "Tanda Tanya" --}}
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.quizzes.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>

                        <span class="truncate">Quiz</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <div class="font-semibold text-xs/6 text-sky-200">Manajemen Pengguna</div>
            <ul role="list" class="mt-2 -mx-2 space-y-1">
                <li>
                    {{-- Users --}}
                    <a href="{{ route('admin.users.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="9" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" />
                            <ellipse cx="9" cy="17" rx="7" ry="4"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        Pengguna
                    </a>
                </li>

                <li>
                    {{-- Redeems --}}
                    <a href="{{ route('admin.redeems.index') }}"
                        class="group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold {{ request()->routeIs('admin.redeems.*') ? 'bg-sky-800 text-white' : 'text-sky-100 hover:text-white hover:bg-sky-800' }}">
                        <svg class="size-6 shrink-0 {{ request()->routeIs('admin.redeems.*') ? 'text-white' : 'text-sky-300 group-hover:text-white' }}"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.625 21.0002C18.2666 20.4299 19.2334 20.4299 19.875 21.0002C20.3109 21.3876 21 21.0782 21 20.495V3.50519C21 2.92196 20.3109 2.61251 19.875 2.99999C19.2334 3.57029 18.2666 3.57029 17.625 2.99999C16.9834 2.42969 16.0166 2.42969 15.375 2.99999C14.7334 3.57029 13.7666 3.57029 13.125 2.99999C12.4834 2.42969 11.5166 2.42969 10.875 2.99999C10.2334 3.57029 9.26659 3.57029 8.625 2.99999C7.98341 2.42969 7.01659 2.42969 6.375 2.99999C5.73341 3.57029 4.76659 3.57029 4.125 2.99999C3.68909 2.61251 3 2.92196 3 3.50519V20.495C3 21.0782 3.68909 21.3876 4.125 21.0002C4.76659 20.4299 5.73341 20.4299 6.375 21.0002C7.01659 21.5705 7.98341 21.5705 8.625 21.0002C9.26659 20.4299 10.2334 20.4299 10.875 21.0002C11.5166 21.5705 12.4834 21.5705 13.125 21.0002C13.7666 20.4299 14.7334 20.4299 15.375 21.0002C16.0166 21.5705 16.9834 21.5705 17.625 21.0002Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M7.5 15.5H16.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                            <path d="M7.5 12H16.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M7.5 8.5H16.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" />
                        </svg>
                        Redeems
                    </a>
                </li>

            </ul>
        </li>
        <li class="mt-auto -mx-6">
            <a href="#"
                class="flex items-center px-6 py-3 font-semibold gap-x-4 text-sm/6 text-sky-100 hover:bg-sky-800 hover:text-white">
                <img class="object-cover w-8 h-8 rounded-full"
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=D9F99D&color=107555&size=128"
                    alt="User avatar">
                <span class="sr-only">Your profile</span>
                <span aria-hidden="true">{{ auth()->user()->name }}</span>
            </a>
        </li>
    </ul>
</nav>

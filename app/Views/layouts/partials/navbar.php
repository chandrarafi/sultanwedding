<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?= site_url() ?>" class="flex items-center">
                        <span class="text-primary-600 font-serif text-2xl font-bold">Sultan</span>
                        <span class="text-secondary-800 font-serif text-xl ml-1">Wedding</span>
                    </a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="<?= site_url() ?>" class="<?= current_url() == site_url() ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Beranda
                    </a>

                    <!-- <?php if (!session()->get('logged_in') || session()->get('role') !== 'pelanggan') : ?>
                        <a href="<?= site_url('about') ?>" class="<?= current_url() == site_url('about') ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Tentang Kami
                        </a>
                    <?php endif; ?> -->

                    <a href="<?= site_url('paket') ?>" class="<?= strpos(current_url(), site_url('paket')) !== false ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Paket
                    </a>

                    <a href="<?= site_url('barang') ?>" class="<?= strpos(current_url(), site_url('barang')) !== false ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Sewa Barang
                    </a>

                    <!-- <a href="<?= site_url('galeri') ?>" class="<?= strpos(current_url(), site_url('galeri')) !== false ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Galeri
                    </a> -->

                    <!-- <?php if (!session()->get('logged_in') || session()->get('role') !== 'pelanggan') : ?>
                        <a href="<?= site_url('kontak') ?>" class="<?= current_url() == site_url('kontak') ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Kontak
                        </a>
                    <?php endif; ?> -->

                    <?php if (session()->get('logged_in') === true && session()->get('role') === 'pelanggan') : ?>
                        <a href="<?= site_url('pemesanan') ?>" class="<?= strpos(current_url(), site_url('pemesanan')) !== false ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Pemesanan
                        </a>
                        <a href="<?= site_url('pelanggan/notifikasi') ?>" class="<?= strpos(current_url(), site_url('pelanggan/notifikasi')) !== false ? 'border-primary-500 text-primary-600' : 'border-transparent text-secondary-500 hover:border-secondary-300 hover:text-secondary-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Notifikasi
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <?php if (session()->get('logged_in') === true) : ?>
                    <!-- Notification bell -->
                    <?php if (session()->get('role') === 'pelanggan') : ?>
                        <div class="ml-3 relative" x-data="{ open: false, notifications: [], count: 0 }">
                            <div>
                                <button @click="open = !open; if(open && count > 0) { fetchNotifications(); }" type="button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 relative" id="notification-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open notifications</span>
                                    <i class="fas fa-bell text-secondary-500 text-xl"></i>
                                    <span x-show="count > 0" x-text="count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"></span>
                                </button>
                            </div>
                            <div x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="notification-menu-button"
                                tabindex="-1"
                                style="display: none;">
                                <div class="py-1">
                                    <div class="px-4 py-2 border-b border-gray-200">
                                        <h3 class="text-sm font-medium text-secondary-900">Notifikasi</h3>
                                    </div>
                                    <div class="max-h-60 overflow-y-auto">
                                        <template x-if="notifications.length === 0">
                                            <div class="px-4 py-3 text-sm text-secondary-500 text-center">
                                                Tidak ada notifikasi
                                            </div>
                                        </template>
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0 mr-3">
                                                        <div class="h-8 w-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center">
                                                            <i class="fas fa-times-circle"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-secondary-900" x-text="notification.title"></p>
                                                        <p class="text-xs text-secondary-500 mt-1" x-text="notification.message"></p>
                                                        <p class="text-xs font-medium text-red-500 mt-1" x-text="notification.action_required"></p>
                                                        <div class="mt-2 flex items-center justify-between">
                                                            <span class="text-xs text-secondary-400" x-text="notification.time"></span>
                                                            <a :href="notification.action_url" class="text-xs text-primary-600 hover:text-primary-800 font-medium">Lihat Detail</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Profile dropdown -->
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                <div class="h-8 w-8 rounded-full bg-primary-600 text-white flex items-center justify-center">
                                    <?php
                                    // Access user data directly from session
                                    $name = session()->get('name') ?? 'User';
                                    echo substr($name, 0, 1);
                                    ?>
                                </div>
                            </button>
                        </div>
                        <div x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="user-menu-button"
                            tabindex="-1"
                            style="display: none;">
                            <?php if (session()->get('role') === 'admin') : ?>
                                <a href="<?= site_url('admin') ?>" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-gray-100" role="menuitem">Dashboard Admin</a>
                            <?php elseif (session()->get('role') === 'pelanggan') : ?>
                                <a href="<?= site_url('profile') ?>" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-gray-100" role="menuitem">Profil Saya</a>
                                <a href="<?= site_url('pemesanan') ?>" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-gray-100" role="menuitem">Pemesanan Saya</a>
                            <?php endif; ?>
                            <a href="<?= site_url('auth/logout') ?>" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-gray-100" role="menuitem">Logout</a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="flex space-x-3">
                        <a href="<?= site_url('auth/register') ?>" class="inline-flex items-center px-4 py-2 border border-primary-600 text-sm font-medium rounded-md text-primary-600 bg-white hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Daftar
                        </a>
                        <a href="<?= site_url('auth/login') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Login
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="-mr-2 flex items-center sm:hidden">
                <!-- Mobile menu button -->
                <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-secondary-400 hover:text-secondary-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="mobile-menu hidden sm:hidden" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="<?= site_url() ?>" class="<?= current_url() == site_url() ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Beranda
            </a>

            <?php if (!session()->get('logged_in') || session()->get('role') !== 'pelanggan') : ?>
                <a href="<?= site_url('about') ?>" class="<?= current_url() == site_url('about') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Tentang Kami
                </a>
            <?php endif; ?>

            <a href="<?= site_url('paket') ?>" class="<?= strpos(current_url(), site_url('paket')) !== false ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Paket Wedding
            </a>

            <a href="<?= site_url('barang') ?>" class="<?= strpos(current_url(), site_url('barang')) !== false ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Sewa Barang
            </a>

            <a href="<?= site_url('galeri') ?>" class="<?= current_url() == site_url('galeri') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                Galeri
            </a>

            <?php if (!session()->get('logged_in') || session()->get('role') !== 'pelanggan') : ?>
                <a href="<?= site_url('kontak') ?>" class="<?= current_url() == site_url('kontak') ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Kontak
                </a>
            <?php endif; ?>

            <?php if (session()->get('logged_in') === true && session()->get('role') === 'pelanggan') : ?>
                <a href="<?= site_url('pemesanan') ?>" class="<?= strpos(current_url(), site_url('pemesanan')) !== false ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Pemesanan
                </a>
                <a href="<?= site_url('pelanggan/notifikasi') ?>" class="<?= strpos(current_url(), site_url('pelanggan/notifikasi')) !== false ? 'bg-primary-50 border-primary-500 text-primary-700' : 'border-transparent text-secondary-600 hover:bg-gray-50 hover:border-secondary-300 hover:text-secondary-800' ?> block pl-3 pr-4 py-2 border-l-4 text-base font-medium">
                    Notifikasi
                </a>
            <?php endif; ?>
        </div>

        <?php if (session()->get('logged_in') === true) : ?>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-primary-600 text-white flex items-center justify-center">
                            <?php
                            // Access user data directly from session
                            $name = session()->get('name') ?? 'User';
                            echo substr($name, 0, 1);
                            ?>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-secondary-800"><?= session()->get('name') ?? 'User' ?></div>
                        <div class="text-sm font-medium text-secondary-500"><?= session()->get('email') ?? '' ?></div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <?php if (session()->get('role') === 'admin') : ?>
                        <a href="<?= site_url('admin') ?>" class="block px-4 py-2 text-base font-medium text-secondary-500 hover:text-secondary-800 hover:bg-gray-100">
                            Dashboard Admin
                        </a>
                    <?php elseif (session()->get('role') === 'pelanggan') : ?>
                        <a href="<?= site_url('profile') ?>" class="block px-4 py-2 text-base font-medium text-secondary-500 hover:text-secondary-800 hover:bg-gray-100">
                            Profil Saya
                        </a>
                        <a href="<?= site_url('pemesanan') ?>" class="block px-4 py-2 text-base font-medium text-secondary-500 hover:text-secondary-800 hover:bg-gray-100">
                            Pemesanan Saya
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('auth/logout') ?>" class="block px-4 py-2 text-base font-medium text-secondary-500 hover:text-secondary-800 hover:bg-gray-100">
                        Logout
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <a href="<?= site_url('auth/register') ?>" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-600 hover:bg-gray-50 hover:border-primary-300">
                    Daftar
                </a>
                <a href="<?= site_url('auth/login') ?>" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-primary-600 hover:bg-gray-50 hover:border-primary-300">
                    Login
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>
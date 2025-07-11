<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sultan Wedding' ?> - Sultan Wedding Organizer</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon-32x32.png') ?>">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-50: #fdf2f8;
            --primary-100: #fce7f3;
            --primary-200: #fbcfe8;
            --primary-300: #f9a8d4;
            --primary-400: #f472b6;
            --primary-500: #ec4899;
            --primary-600: #db2777;
            --primary-700: #be185d;
            --primary-800: #9d174d;
            --primary-900: #831843;

            --secondary-50: #f8fafc;
            --secondary-100: #f1f5f9;
            --secondary-200: #e2e8f0;
            --secondary-300: #cbd5e1;
            --secondary-400: #94a3b8;
            --secondary-500: #64748b;
            --secondary-600: #475569;
            --secondary-700: #334155;
            --secondary-800: #1e293b;
            --secondary-900: #0f172a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--secondary-700);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Tailwind Custom Colors */
        .bg-primary-50 {
            background-color: var(--primary-50);
        }

        .bg-primary-100 {
            background-color: var(--primary-100);
        }

        .bg-primary-200 {
            background-color: var(--primary-200);
        }

        .bg-primary-300 {
            background-color: var(--primary-300);
        }

        .bg-primary-400 {
            background-color: var(--primary-400);
        }

        .bg-primary-500 {
            background-color: var(--primary-500);
        }

        .bg-primary-600 {
            background-color: var(--primary-600);
        }

        .bg-primary-700 {
            background-color: var(--primary-700);
        }

        .bg-primary-800 {
            background-color: var(--primary-800);
        }

        .bg-primary-900 {
            background-color: var(--primary-900);
        }

        .text-primary-50 {
            color: var(--primary-50);
        }

        .text-primary-100 {
            color: var(--primary-100);
        }

        .text-primary-200 {
            color: var(--primary-200);
        }

        .text-primary-300 {
            color: var(--primary-300);
        }

        .text-primary-400 {
            color: var(--primary-400);
        }

        .text-primary-500 {
            color: var(--primary-500);
        }

        .text-primary-600 {
            color: var(--primary-600);
        }

        .text-primary-700 {
            color: var(--primary-700);
        }

        .text-primary-800 {
            color: var(--primary-800);
        }

        .text-primary-900 {
            color: var(--primary-900);
        }

        .text-secondary-50 {
            color: var(--secondary-50);
        }

        .text-secondary-100 {
            color: var(--secondary-100);
        }

        .text-secondary-200 {
            color: var(--secondary-200);
        }

        .text-secondary-300 {
            color: var(--secondary-300);
        }

        .text-secondary-400 {
            color: var(--secondary-400);
        }

        .text-secondary-500 {
            color: var(--secondary-500);
        }

        .text-secondary-600 {
            color: var(--secondary-600);
        }

        .text-secondary-700 {
            color: var(--secondary-700);
        }

        .text-secondary-800 {
            color: var(--secondary-800);
        }

        .text-secondary-900 {
            color: var(--secondary-900);
        }

        .border-primary-600 {
            border-color: var(--primary-600);
        }

        .hover\:bg-primary-50:hover {
            background-color: var(--primary-50);
        }

        .hover\:bg-primary-600:hover {
            background-color: var(--primary-600);
        }

        .hover\:bg-primary-700:hover {
            background-color: var(--primary-700);
        }

        .hover\:bg-primary-800:hover {
            background-color: var(--primary-800);
        }

        .hover\:text-white:hover {
            color: white;
        }

        /* Custom Styles */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="<?= site_url() ?>" class="flex items-center">
                        <span class="text-2xl font-serif font-bold text-primary-600">Sultan Wedding</span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="<?= site_url() ?>" class="text-secondary-600 hover:text-primary-600 font-medium transition-colors duration-200">Beranda</a>
                    <a href="<?= site_url('about') ?>" class="text-secondary-600 hover:text-primary-600 font-medium transition-colors duration-200">Tentang Kami</a>
                    <a href="<?= site_url('paket') ?>" class="text-secondary-600 hover:text-primary-600 font-medium transition-colors duration-200">Paket Wedding</a>
                    <a href="<?= site_url('barang') ?>" class="text-secondary-600 hover:text-primary-600 font-medium transition-colors duration-200">Sewa Barang</a>
                    <a href="<?= site_url('galeri') ?>" class="text-secondary-600 hover:text-primary-600 font-medium transition-colors duration-200">Galeri</a>
                    <a href="<?= site_url('kontak') ?>" class="text-secondary-600 hover:text-primary-600 font-medium transition-colors duration-200">Kontak</a>
                    <a href="<?= site_url('auth/login') ?>" class="px-4 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 transition-colors duration-200">Login</a>
                </nav>

                <!-- Mobile Navigation Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="text-secondary-600 hover:text-primary-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="<?= site_url() ?>" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-600 hover:text-primary-600 hover:bg-gray-50">Beranda</a>
                <a href="<?= site_url('about') ?>" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-600 hover:text-primary-600 hover:bg-gray-50">Tentang Kami</a>
                <a href="<?= site_url('paket') ?>" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-600 hover:text-primary-600 hover:bg-gray-50">Paket Wedding</a>
                <a href="<?= site_url('barang') ?>" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-600 hover:text-primary-600 hover:bg-gray-50">Sewa Barang</a>
                <a href="<?= site_url('galeri') ?>" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-600 hover:text-primary-600 hover:bg-gray-50">Galeri</a>
                <a href="<?= site_url('kontak') ?>" class="block px-3 py-2 rounded-md text-base font-medium text-secondary-600 hover:text-primary-600 hover:bg-gray-50">Kontak</a>
                <a href="<?= site_url('auth/login') ?>" class="block px-3 py-2 rounded-md text-base font-medium bg-primary-600 text-white hover:bg-primary-700 mt-2">Login</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-secondary-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-xl font-serif font-bold mb-4">Sultan Wedding</h3>
                    <p class="text-gray-300 mb-4">
                        Kami menyediakan layanan pernikahan terbaik dengan sentuhan elegansi dan keindahan yang akan membuat hari spesial Anda menjadi kenangan tak terlupakan.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-serif font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="<?= site_url() ?>" class="text-gray-300 hover:text-white transition-colors duration-200">Beranda</a></li>
                        <li><a href="<?= site_url('about') ?>" class="text-gray-300 hover:text-white transition-colors duration-200">Tentang Kami</a></li>
                        <li><a href="<?= site_url('paket') ?>" class="text-gray-300 hover:text-white transition-colors duration-200">Paket Wedding</a></li>
                        <li><a href="<?= site_url('galeri') ?>" class="text-gray-300 hover:text-white transition-colors duration-200">Galeri</a></li>
                        <li><a href="<?= site_url('kontak') ?>" class="text-gray-300 hover:text-white transition-colors duration-200">Kontak</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-xl font-serif font-bold mb-4">Kontak Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-400"></i>
                            <span class="text-gray-300">Jl. Sultan Hasanuddin No. 123, Makassar, Sulawesi Selatan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt mt-1 mr-3 text-primary-400"></i>
                            <span class="text-gray-300">+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope mt-1 mr-3 text-primary-400"></i>
                            <span class="text-gray-300">info@sultanwedding.com</span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="text-xl font-serif font-bold mb-4">Newsletter</h3>
                    <p class="text-gray-300 mb-4">
                        Dapatkan informasi terbaru dan promo menarik dari kami.
                    </p>
                    <form action="#" method="POST" class="flex">
                        <input type="email" placeholder="Email Anda" class="px-4 py-2 w-full rounded-l-md focus:outline-none text-secondary-800">
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-r-md hover:bg-primary-700 transition-colors duration-200">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">
                    &copy; <?= date('Y') ?> Sultan Wedding Organizer. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
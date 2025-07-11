<?= $this->extend('home/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative bg-cover bg-center h-screen" style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
        <div class="text-center md:text-left md:max-w-2xl">
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-white leading-tight">
                Wujudkan Pernikahan Impian Anda
            </h1>
            <p class="mt-6 text-xl text-white opacity-90">
                Sultan Wedding Organizer menyediakan layanan pernikahan terbaik dengan sentuhan elegansi dan keindahan yang akan membuat hari spesial Anda menjadi kenangan tak terlupakan.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row justify-center md:justify-start gap-4">
                <a href="<?= site_url('paket') ?>" class="px-8 py-4 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 transition-colors duration-300 text-center text-lg">
                    Lihat Paket
                </a>
                <a href="#layanan" class="px-8 py-4 bg-white text-secondary-800 font-medium rounded-md hover:bg-gray-100 transition-colors duration-300 text-center text-lg">
                    Layanan Kami
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Section -->
<section id="layanan" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary-900">Layanan Kami</h2>
            <div class="w-24 h-1 bg-primary-500 mx-auto mt-4"></div>
            <p class="mt-6 text-xl text-secondary-600 max-w-3xl mx-auto">
                Kami menyediakan berbagai layanan untuk memastikan hari pernikahan Anda berjalan sempurna dan tak terlupakan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <!-- Layanan 1 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?q=80&w=2069&auto=format&fit=crop" alt="Dekorasi Pernikahan" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-secondary-900 mb-3">Dekorasi Pernikahan</h3>
                    <p class="text-secondary-600 text-lg">
                        Dekorasi pernikahan yang elegan dan sesuai dengan tema yang Anda inginkan untuk menciptakan suasana yang memukau.
                    </p>
                    <a href="#" class="inline-flex items-center mt-4 text-primary-600 font-medium">
                        Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Layanan 2 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                <img src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?q=80&w=2036&auto=format&fit=crop" alt="Katering" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-secondary-900 mb-3">Katering</h3>
                    <p class="text-secondary-600 text-lg">
                        Menu makanan berkualitas dengan berbagai pilihan masakan yang lezat untuk memanjakan tamu undangan Anda.
                    </p>
                    <a href="#" class="inline-flex items-center mt-4 text-primary-600 font-medium">
                        Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Layanan 3 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105">
                <img src="https://images.unsplash.com/photo-1527529482837-4698179dc6ce?q=80&w=2070&auto=format&fit=crop" alt="Dokumentasi" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-secondary-900 mb-3">Dokumentasi</h3>
                    <p class="text-secondary-600 text-lg">
                        Abadikan momen berharga Anda dengan layanan fotografi dan videografi profesional.
                    </p>
                    <a href="#" class="inline-flex items-center mt-4 text-primary-600 font-medium">
                        Selengkapnya <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary-900 mb-6">Tentang Sultan Wedding Organizer</h2>
                <p class="text-lg text-secondary-600 mb-6">
                    Sultan Wedding Organizer adalah penyedia layanan pernikahan terpercaya dengan pengalaman lebih dari 10 tahun dalam industri. Kami berkomitmen untuk memberikan pelayanan terbaik dan mewujudkan pernikahan impian Anda.
                </p>
                <p class="text-lg text-secondary-600 mb-6">
                    Tim kami terdiri dari para profesional yang berpengalaman dan berdedikasi untuk memastikan setiap detail pernikahan Anda direncanakan dan dilaksanakan dengan sempurna.
                </p>
                <ul class="space-y-3 text-lg text-secondary-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                        <span>Perencanaan pernikahan yang detail dan terorganisir</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                        <span>Tim profesional yang berpengalaman</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                        <span>Vendor dan partner terpercaya</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                        <span>Solusi pernikahan yang disesuaikan dengan budget</span>
                    </li>
                </ul>
                <a href="<?= site_url('about') ?>" class="inline-flex items-center mt-8 px-6 py-3 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 transition-colors duration-300">
                    Pelajari Lebih Lanjut <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?q=80&w=2070&auto=format&fit=crop" alt="Wedding" class="rounded-lg h-64 w-full object-cover">
                    <img src="https://images.unsplash.com/photo-1550005809-91ad75fb315f?q=80&w=2069&auto=format&fit=crop" alt="Wedding" class="rounded-lg h-48 w-full object-cover">
                </div>
                <div class="space-y-4 mt-8">
                    <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop" alt="Wedding" class="rounded-lg h-48 w-full object-cover">
                    <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=2070&auto=format&fit=crop" alt="Wedding" class="rounded-lg h-64 w-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Paket Wedding Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary-900">Paket Wedding</h2>
            <div class="w-24 h-1 bg-primary-500 mx-auto mt-4"></div>
            <p class="mt-6 text-xl text-secondary-600 max-w-3xl mx-auto">
                Pilih paket pernikahan yang sesuai dengan kebutuhan dan budget Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Paket 1 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl">
                <div class="p-8 bg-primary-600 text-white text-center">
                    <h3 class="text-2xl font-semibold">Paket Silver</h3>
                    <div class="mt-4 text-4xl font-bold">Rp 25.000.000</div>
                </div>
                <div class="p-8">
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Dekorasi pelaminan standar</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Katering untuk 300 orang</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Dokumentasi foto</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Rias pengantin</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>MC acara</span>
                        </li>
                    </ul>
                    <a href="<?= site_url('paket/1') ?>" class="mt-8 block w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                        Lihat Detail
                    </a>
                </div>
            </div>

            <!-- Paket 2 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl relative transform scale-110">
                <div class="absolute top-0 right-0 bg-yellow-500 text-white px-4 py-1 rounded-bl-lg font-medium">
                    Terpopuler
                </div>
                <div class="p-8 bg-primary-700 text-white text-center">
                    <h3 class="text-2xl font-semibold">Paket Gold</h3>
                    <div class="mt-4 text-4xl font-bold">Rp 45.000.000</div>
                </div>
                <div class="p-8">
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Dekorasi pelaminan premium</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Katering untuk 500 orang</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Dokumentasi foto dan video</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Rias pengantin dan keluarga</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>MC profesional</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Entertainment</span>
                        </li>
                    </ul>
                    <a href="<?= site_url('paket/2') ?>" class="mt-8 block w-full py-3 px-4 bg-primary-700 hover:bg-primary-800 text-white font-medium text-center rounded-md transition-colors duration-300">
                        Lihat Detail
                    </a>
                </div>
            </div>

            <!-- Paket 3 -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl">
                <div class="p-8 bg-primary-600 text-white text-center">
                    <h3 class="text-2xl font-semibold">Paket Platinum</h3>
                    <div class="mt-4 text-4xl font-bold">Rp 75.000.000</div>
                </div>
                <div class="p-8">
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Dekorasi pelaminan mewah</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Katering untuk 1000 orang</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Dokumentasi foto, video, dan drone</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Rias pengantin, keluarga, dan bridesmaid</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>MC selebriti</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-primary-600 mt-1 mr-3"></i>
                            <span>Entertainment dan live music</span>
                        </li>
                    </ul>
                    <a href="<?= site_url('paket/3') ?>" class="mt-8 block w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="<?= site_url('paket') ?>" class="inline-flex items-center px-8 py-3 border-2 border-primary-600 text-primary-600 font-medium rounded-md hover:bg-primary-600 hover:text-white transition-colors duration-300 text-lg">
                Lihat Semua Paket
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Galeri Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary-900">Galeri Pernikahan</h2>
            <div class="w-24 h-1 bg-primary-500 mx-auto mt-4"></div>
            <p class="mt-6 text-xl text-secondary-600 max-w-3xl mx-auto">
                Momen-momen indah pernikahan yang telah kami dokumentasikan.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="relative group">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2069&auto=format&fit=crop" alt="Wedding Gallery" class="w-full h-64 object-cover rounded-lg">
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center rounded-lg">
                    <a href="#" class="text-white text-xl"><i class="fas fa-search-plus"></i></a>
                </div>
            </div>
            <div class="relative group">
                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop" alt="Wedding Gallery" class="w-full h-64 object-cover rounded-lg">
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center rounded-lg">
                    <a href="#" class="text-white text-xl"><i class="fas fa-search-plus"></i></a>
                </div>
            </div>
            <div class="relative group">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop" alt="Wedding Gallery" class="w-full h-64 object-cover rounded-lg">
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center rounded-lg">
                    <a href="#" class="text-white text-xl"><i class="fas fa-search-plus"></i></a>
                </div>
            </div>
            <div class="relative group">
                <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=2070&auto=format&fit=crop" alt="Wedding Gallery" class="w-full h-64 object-cover rounded-lg">
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center rounded-lg">
                    <a href="#" class="text-white text-xl"><i class="fas fa-search-plus"></i></a>
                </div>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="<?= site_url('galeri') ?>" class="inline-flex items-center px-8 py-3 border-2 border-primary-600 text-primary-600 font-medium rounded-md hover:bg-primary-600 hover:text-white transition-colors duration-300 text-lg">
                Lihat Semua Galeri
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Testimonial Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-secondary-900">Testimoni Pelanggan</h2>
            <div class="w-24 h-1 bg-primary-500 mx-auto mt-4"></div>
            <p class="mt-6 text-xl text-secondary-600 max-w-3xl mx-auto">
                Apa kata mereka yang telah menggunakan jasa Sultan Wedding Organizer?
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-gray-50 p-8 rounded-lg shadow-md">
                <div class="flex items-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                        <span class="text-2xl font-semibold">D</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-xl font-semibold text-secondary-900">Dewi & Budi</h4>
                        <div class="flex text-yellow-400 mt-1">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-secondary-600 text-lg italic">
                    "Terima kasih Sultan Wedding telah membuat hari pernikahan kami begitu sempurna. Semua berjalan lancar dan dekorasinya sangat cantik sesuai dengan yang kami impikan."
                </p>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-gray-50 p-8 rounded-lg shadow-md">
                <div class="flex items-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                        <span class="text-2xl font-semibold">R</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-xl font-semibold text-secondary-900">Rina & Andi</h4>
                        <div class="flex text-yellow-400 mt-1">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p class="text-secondary-600 text-lg italic">
                    "Profesionalitas tim Sultan Wedding sangat luar biasa. Mereka sangat detail dan responsif terhadap semua permintaan kami. Para tamu sangat terkesan dengan pelayanan yang diberikan."
                </p>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-gray-50 p-8 rounded-lg shadow-md">
                <div class="flex items-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                        <span class="text-2xl font-semibold">S</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-xl font-semibold text-secondary-900">Sinta & Reza</h4>
                        <div class="flex text-yellow-400 mt-1">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-secondary-600 text-lg italic">
                    "Kami sangat puas dengan paket Platinum yang kami pilih. Semua kebutuhan pernikahan kami terpenuhi dan tim Sultan Wedding sangat membantu dalam proses persiapan hingga hari H."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-primary-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-5xl font-serif font-bold">Siap Mewujudkan Pernikahan Impian Anda?</h2>
        <p class="mt-6 text-xl max-w-3xl mx-auto text-white text-opacity-90">
            Konsultasikan kebutuhan pernikahan Anda dengan tim kami dan dapatkan penawaran terbaik.
        </p>
        <div class="mt-10">
            <a href="<?= site_url('kontak') ?>" class="inline-flex items-center px-8 py-4 bg-white text-primary-600 font-medium rounded-md hover:bg-gray-100 transition-colors duration-300 text-lg">
                Konsultasi Sekarang
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
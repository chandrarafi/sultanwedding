<?= $this->extend('home/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative bg-cover bg-center h-72" style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
        <div class="text-center w-full">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white leading-tight">
                <?= $barang['namabarang'] ?>
            </h1>
            <p class="mt-4 text-xl text-white opacity-90 max-w-3xl mx-auto">
                Sewa Barang
            </p>
        </div>
    </div>
</section>

<!-- Barang Detail Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Gambar Barang -->
            <div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <?php if (!empty($barang['foto'])) : ?>
                        <img src="<?= base_url('uploads/barang/' . $barang['foto']) ?>" alt="<?= $barang['namabarang'] ?>" class="w-full h-auto">
                    <?php else : ?>
                        <?php
                        // Menggunakan gambar dari gallery berdasarkan id barang
                        $imageIndex = ($barang['kdbarang'] % 37) + 1;
                        $imageIndex = str_pad($imageIndex, 2, '0', STR_PAD_LEFT);
                        ?>
                        <img src="<?= base_url('assets/images/gallery/' . $imageIndex . '.png') ?>" alt="<?= $barang['namabarang'] ?>" class="w-full h-auto">
                    <?php endif; ?>
                </div>

                <!-- Gambar Tambahan (dummy) -->
                <div class="grid grid-cols-4 gap-4 mt-4">
                    <?php for ($i = 1; $i <= 4; $i++) :
                        $additionalImageIndex = (($barang['kdbarang'] + $i) % 37) + 1;
                        $additionalImageIndex = str_pad($additionalImageIndex, 2, '0', STR_PAD_LEFT);
                    ?>
                        <div class="bg-white rounded-lg shadow overflow-hidden cursor-pointer">
                            <img src="<?= base_url('assets/images/gallery/' . $additionalImageIndex . '.png') ?>" alt="<?= $barang['namabarang'] ?> <?= $i ?>" class="w-full h-24 object-cover">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Detail Barang -->
            <div>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden p-8">
                    <h2 class="text-3xl font-semibold text-secondary-900 mb-4"><?= $barang['namabarang'] ?></h2>

                    <div class="mb-6">
                        <div class="text-3xl font-bold text-primary-600">
                            Rp <?= number_format($barang['hargasewa'], 0, ',', '.') ?> / <?= $barang['satuan'] ?>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-secondary-900 mb-2">Informasi Barang:</h3>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <span class="font-medium w-32">Satuan:</span>
                                <span><?= $barang['satuan'] ?></span>
                            </li>
                            <li class="flex items-center">
                                <span class="font-medium w-32">Stok Tersedia:</span>
                                <span><?= $barang['jumlah'] ?> <?= $barang['satuan'] ?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-secondary-900 mb-2">Deskripsi:</h3>
                        <p class="text-secondary-600">
                            <?= $barang['namabarang'] ?> adalah pilihan yang tepat untuk menyempurnakan acara Anda.
                            Dengan kualitas terbaik dan perawatan yang baik, kami menjamin kepuasan Anda dalam menggunakan barang ini.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <a href="<?= site_url('auth/login') ?>" class="block w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                            Pesan Sekarang
                        </a>
                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20tertarik%20untuk%20menyewa%20<?= urlencode($barang['namabarang']) ?>" target="_blank" class="block w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                            <i class="fab fa-whatsapp mr-2"></i> Hubungi via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif font-bold text-secondary-900">Barang Lainnya</h2>
            <div class="w-24 h-1 bg-primary-500 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Related products will be dynamically loaded here -->
            <!-- This is just a placeholder for now -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6 text-center">
                    <p class="text-secondary-600">Lihat barang lainnya yang mungkin sesuai dengan kebutuhan Anda</p>
                    <a href="<?= site_url('barang') ?>" class="inline-block mt-4 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition-colors duration-300">
                        Lihat Semua Barang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->extend('home/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative bg-cover bg-center h-72" style="background-image: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
        <div class="text-center w-full">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white leading-tight">
                <?= $paket['namapaket'] ?>
            </h1>
            <p class="mt-4 text-xl text-white opacity-90 max-w-3xl mx-auto">
                <?= $paket['namakategori'] ?>
            </p>
        </div>
    </div>
</section>

<!-- Paket Detail Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    <!-- Foto Paket -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                        <?php if (!empty($paket['foto'])) : ?>
                            <img src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" alt="<?= $paket['namapaket'] ?>" class="w-full h-auto">
                        <?php else : ?>
                            <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop" alt="<?= $paket['namapaket'] ?>" class="w-full h-auto">
                        <?php endif; ?>
                    </div>

                    <!-- Harga dan CTA -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-secondary-900">Harga Paket</h3>
                                <div class="text-3xl font-bold text-primary-600 mt-2">
                                    Rp <?= number_format($paket['harga'], 0, ',', '.') ?>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="<?= site_url('auth/login') ?>" class="block w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                                    Pesan Sekarang
                                </a>
                                <a href="https://wa.me/6281234567890?text=Halo,%20saya%20tertarik%20dengan%20paket%20<?= urlencode($paket['namapaket']) ?>" target="_blank" class="block w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium text-center rounded-md transition-colors duration-300 mt-3">
                                    <i class="fab fa-whatsapp mr-2"></i> Hubungi via WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-secondary-900 mb-4">Kategori</h3>
                            <span class="inline-block px-4 py-2 bg-primary-100 text-primary-800 rounded-full">
                                <?= $paket['namakategori'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Detail Paket -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-secondary-900 mb-4">Detail Paket</h2>
                        <?php if (!empty($paket['detailpaket'])) : ?>
                            <div class="prose max-w-none">
                                <?= nl2br($paket['detailpaket']) ?>
                            </div>
                        <?php else : ?>
                            <p class="text-secondary-600">Tidak ada detail untuk paket ini.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Barang dalam Paket -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold text-secondary-900 mb-4">Barang dalam Paket</h2>
                        <?php if (isset($paket['items']) && count($paket['items']) > 0) : ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($paket['items'] as $index => $item) : ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $index + 1 ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900"><?= $item['namabarang'] ?></div>
                                                    <div class="text-sm text-gray-500"><?= $item['satuan'] ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $item['jumlah'] ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $item['keterangan'] ?: '-' ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else : ?>
                            <p class="text-secondary-600">Tidak ada barang dalam paket ini.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- CTA -->
                <div class="bg-primary-50 rounded-lg p-8 text-center">
                    <h3 class="text-2xl font-semibold text-secondary-900 mb-4">Tertarik dengan paket ini?</h3>
                    <p class="text-secondary-600 mb-6">Jangan ragu untuk menghubungi kami atau melakukan pemesanan sekarang juga!</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?= site_url('auth/login') ?>" class="py-3 px-6 bg-primary-600 hover:bg-primary-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                            Pesan Sekarang
                        </a>
                        <a href="<?= site_url('kontak') ?>" class="py-3 px-6 bg-white border border-primary-600 text-primary-600 hover:bg-primary-50 font-medium text-center rounded-md transition-colors duration-300">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Packages Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-serif font-bold text-secondary-900">Paket Lainnya</h2>
            <div class="w-24 h-1 bg-primary-500 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Related packages will be dynamically loaded here -->
            <!-- This is just a placeholder for now -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6 text-center">
                    <p class="text-secondary-600">Lihat paket lainnya yang mungkin sesuai dengan kebutuhan Anda</p>
                    <a href="<?= site_url('paket') ?>" class="inline-block mt-4 py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition-colors duration-300">
                        Lihat Semua Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
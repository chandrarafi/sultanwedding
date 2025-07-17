<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative bg-cover bg-center h-72" style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
        <div class="text-center w-full">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white leading-tight">
                Sewa Barang
            </h1>
            <p class="mt-4 text-xl text-white opacity-90 max-w-3xl mx-auto">
                Pilih barang berkualitas untuk disewa sesuai kebutuhan acara Anda
            </p>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between">
            <div class="w-full md:w-auto mb-4 md:mb-0">
                <h2 class="text-xl font-semibold text-secondary-900">Filter Barang</h2>
            </div>
            <div class="w-full md:w-auto flex flex-wrap gap-4">
                <select id="sortFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="default">Urutan Default</option>
                    <option value="price_asc">Harga: Rendah ke Tinggi</option>
                    <option value="price_desc">Harga: Tinggi ke Rendah</option>
                    <option value="name_asc">Nama: A-Z</option>
                    <option value="name_desc">Nama: Z-A</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Barang List Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($barangs)) : ?>
            <div class="text-center py-12">
                <h3 class="text-2xl font-medium text-secondary-900">Tidak ada barang yang tersedia saat ini</h3>
                <p class="mt-4 text-secondary-600">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut</p>
            </div>
        <?php else : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="barangContainer">
                <?php foreach ($barangs as $barang) : ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl barang-item"
                        data-harga="<?= $barang['hargasewa'] ?>"
                        data-nama="<?= $barang['namabarang'] ?>">
                        <div class="h-48 overflow-hidden">
                            <img src="<?= !empty($barang['foto']) ? base_url('uploads/barang/' . $barang['foto']) : base_url('assets/images/gallery/' . str_pad(($barang['kdbarang'] % 37) + 1, 2, '0', STR_PAD_LEFT) . '.png') ?>" alt="<?= $barang['namabarang'] ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-secondary-900"><?= $barang['namabarang'] ?></h3>
                            </div>
                            <div class="mb-3">
                                <div class="text-lg font-bold text-primary-600">
                                    Rp <?= number_format($barang['hargasewa'], 0, ',', '.') ?> / <?= $barang['satuan'] ?>
                                </div>
                                <div class="text-sm text-secondary-500">
                                    Stok: <?= $barang['jumlah'] ?>
                                </div>
                            </div>
                            <a href="<?= site_url('barang/' . $barang['kdbarang']) ?>" class="block w-full py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const barangContainer = document.getElementById('barangContainer');
        const sortFilter = document.getElementById('sortFilter');
        const barangItems = document.querySelectorAll('.barang-item');

        // Filter dan sort barang
        function filterAndSortBarangs() {
            const selectedSort = sortFilter.value;

            // Konversi NodeList ke Array untuk sorting
            const barangArray = Array.from(barangItems);

            // Sort berdasarkan pilihan
            barangArray.sort((a, b) => {
                switch (selectedSort) {
                    case 'price_asc':
                        return parseInt(a.dataset.harga) - parseInt(b.dataset.harga);
                    case 'price_desc':
                        return parseInt(b.dataset.harga) - parseInt(a.dataset.harga);
                    case 'name_asc':
                        return a.dataset.nama.localeCompare(b.dataset.nama);
                    case 'name_desc':
                        return b.dataset.nama.localeCompare(a.dataset.nama);
                    default:
                        return 0;
                }
            });

            // Hapus semua item dari container
            while (barangContainer.firstChild) {
                barangContainer.removeChild(barangContainer.firstChild);
            }

            // Tambahkan item yang sudah difilter dan disort
            barangArray.forEach(item => {
                item.style.display = 'block';
                barangContainer.appendChild(item);
            });
        }

        // Event listeners
        sortFilter.addEventListener('change', filterAndSortBarangs);
    });
</script>
<?= $this->endSection() ?>
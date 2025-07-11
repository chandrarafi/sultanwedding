x<?= $this->extend('home/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative bg-cover bg-center h-72" style="background-image: url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
        <div class="text-center w-full">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white leading-tight">
                Paket Wedding
            </h1>
            <p class="mt-4 text-xl text-white opacity-90 max-w-3xl mx-auto">
                Pilih paket pernikahan yang sesuai dengan kebutuhan dan budget Anda
            </p>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between">
            <div class="w-full md:w-auto mb-4 md:mb-0">
                <h2 class="text-xl font-semibold text-secondary-900">Filter Paket</h2>
            </div>
            <div class="w-full md:w-auto flex flex-wrap gap-4">
                <select id="kategoriFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori as $k) : ?>
                        <option value="<?= $k['kdkategori'] ?>"><?= $k['namakategori'] ?></option>
                    <?php endforeach; ?>
                </select>
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

<!-- Paket List Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (empty($pakets)) : ?>
            <div class="text-center py-12">
                <h3 class="text-2xl font-medium text-secondary-900">Tidak ada paket yang tersedia saat ini</h3>
                <p class="mt-4 text-secondary-600">Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut</p>
            </div>
        <?php else : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="paketContainer">
                <?php foreach ($pakets as $paket) : ?>
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl paket-item"
                        data-kategori="<?= $paket['kdkategori'] ?>"
                        data-harga="<?= $paket['harga'] ?>"
                        data-nama="<?= $paket['namapaket'] ?>">
                        <div class="h-64 overflow-hidden">
                            <?php if (!empty($paket['foto'])) : ?>
                                <img src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" alt="<?= $paket['namapaket'] ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop" alt="<?= $paket['namapaket'] ?>" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-2xl font-semibold text-secondary-900"><?= $paket['namapaket'] ?></h3>
                                <span class="px-3 py-1 bg-primary-100 text-primary-800 text-sm font-medium rounded-full">
                                    <?= $paket['namakategori'] ?>
                                </span>
                            </div>
                            <div class="mb-4">
                                <div class="text-2xl font-bold text-primary-600">
                                    Rp <?= number_format($paket['harga'], 0, ',', '.') ?>
                                </div>
                            </div>
                            <?php if (!empty($paket['detailpaket'])) : ?>
                                <p class="text-secondary-600 mb-6 line-clamp-3">
                                    <?= substr($paket['detailpaket'], 0, 150) . (strlen($paket['detailpaket']) > 150 ? '...' : '') ?>
                                </p>
                            <?php endif; ?>
                            <div class="mt-6">
                                <a href="<?= site_url('paket/' . $paket['kdpaket']) ?>" class="block w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-center rounded-md transition-colors duration-300">
                                    Lihat Detail
                                </a>
                            </div>
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
        const paketContainer = document.getElementById('paketContainer');
        const kategoriFilter = document.getElementById('kategoriFilter');
        const sortFilter = document.getElementById('sortFilter');
        const paketItems = document.querySelectorAll('.paket-item');

        // Filter dan sort paket
        function filterAndSortPakets() {
            const selectedKategori = kategoriFilter.value;
            const selectedSort = sortFilter.value;

            // Konversi NodeList ke Array untuk sorting
            const paketArray = Array.from(paketItems);

            // Filter berdasarkan kategori
            const filteredPakets = paketArray.filter(item => {
                if (!selectedKategori) return true;
                return item.dataset.kategori === selectedKategori;
            });

            // Sort berdasarkan pilihan
            filteredPakets.sort((a, b) => {
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
            while (paketContainer.firstChild) {
                paketContainer.removeChild(paketContainer.firstChild);
            }

            // Tambahkan item yang sudah difilter dan disort
            filteredPakets.forEach(item => {
                item.style.display = 'block';
                paketContainer.appendChild(item);
            });

            // Tampilkan pesan jika tidak ada hasil
            if (filteredPakets.length === 0) {
                const noResults = document.createElement('div');
                noResults.className = 'text-center py-12 col-span-full';
                noResults.innerHTML = `
                    <h3 class="text-2xl font-medium text-secondary-900">Tidak ada paket yang sesuai dengan filter</h3>
                    <p class="mt-4 text-secondary-600">Silakan coba filter lain atau reset filter</p>
                `;
                paketContainer.appendChild(noResults);
            }
        }

        // Event listeners
        kategoriFilter.addEventListener('change', filterAndSortPakets);
        sortFilter.addEventListener('change', filterAndSortPakets);
    });
</script>
<?= $this->endSection() ?>
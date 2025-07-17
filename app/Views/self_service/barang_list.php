<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-primary text-white py-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Katalog Barang</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa') ?>" class="text-white">Beranda</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Katalog Barang</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="py-4">
    <div class="container">
        <!-- Filter and Search -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari barang...">
                    <button class="btn btn-outline-primary" type="button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end">
                    <select class="form-select w-auto" id="sortBy">
                        <option value="name-asc">Nama (A-Z)</option>
                        <option value="name-desc">Nama (Z-A)</option>
                        <option value="price-asc">Harga (Rendah-Tinggi)</option>
                        <option value="price-desc">Harga (Tinggi-Rendah)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="row" id="productsContainer">
            <?php if (empty($barang)): ?>
                <div class="col-12 text-center py-5">
                    <h3>Tidak ada barang yang tersedia saat ini.</h3>
                    <p>Silakan coba lagi nanti atau hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            <?php else: ?>
                <?php foreach ($barang as $item): ?>
                    <div class="col-md-4 col-lg-3 mb-4 product-item"
                        data-name="<?= strtolower($item['namabarang']) ?>"
                        data-price="<?= $item['hargasewa'] ?>">
                        <div class="card product-card h-100">
                            <img src="<?= !empty($item['foto']) ? base_url('uploads/barang/' . $item['foto']) : 'https://via.placeholder.com/300x200?text=No+Image' ?>" class="card-img-top" alt="<?= $item['namabarang'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $item['namabarang'] ?></h5>
                                <p class="card-text text-muted"><?= $item['satuan'] ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Rp <?= number_format($item['hargasewa'], 0, ',', '.') ?></h6>
                                    <span class="badge <?= $item['jumlah'] > 0 ? 'bg-success' : 'bg-danger' ?>"><?= $item['jumlah'] > 0 ? 'Stok: ' . $item['jumlah'] : 'Habis' ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="<?= site_url('sewa/barang/' . $item['kdbarang']) ?>" class="btn btn-outline-primary w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- No Results -->
        <div class="row d-none" id="noResults">
            <div class="col-12 text-center py-5">
                <h3>Tidak ada barang yang sesuai dengan pencarian Anda.</h3>
                <p>Silakan coba kata kunci lain atau reset pencarian.</p>
                <button class="btn btn-primary" id="resetSearch">Reset Pencarian</button>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Search functionality
        $('#searchButton, #searchInput').on('click keyup', function(e) {
            if (e.type === 'click' || e.keyCode === 13) {
                const searchTerm = $('#searchInput').val().toLowerCase();
                filterProducts(searchTerm);
            }
        });

        // Reset search
        $('#resetSearch').on('click', function() {
            $('#searchInput').val('');
            filterProducts('');
        });

        // Sort functionality
        $('#sortBy').on('change', function() {
            const sortValue = $(this).val();
            sortProducts(sortValue);
        });

        // Filter products based on search term
        function filterProducts(searchTerm) {
            let hasResults = false;

            $('.product-item').each(function() {
                const productName = $(this).data('name');

                if (productName.includes(searchTerm)) {
                    $(this).removeClass('d-none');
                    hasResults = true;
                } else {
                    $(this).addClass('d-none');
                }
            });

            if (hasResults) {
                $('#noResults').addClass('d-none');
            } else {
                $('#noResults').removeClass('d-none');
            }
        }

        // Sort products
        function sortProducts(sortValue) {
            const $products = $('.product-item').toArray();

            $products.sort(function(a, b) {
                const aName = $(a).data('name');
                const bName = $(b).data('name');
                const aPrice = $(a).data('price');
                const bPrice = $(b).data('price');

                switch (sortValue) {
                    case 'name-asc':
                        return aName.localeCompare(bName);
                    case 'name-desc':
                        return bName.localeCompare(aName);
                    case 'price-asc':
                        return aPrice - bPrice;
                    case 'price-desc':
                        return bPrice - aPrice;
                    default:
                        return 0;
                }
            });

            const $container = $('#productsContainer');
            $.each($products, function(i, product) {
                $container.append(product);
            });
        }
    });
</script>
<?= $this->endSection() ?>
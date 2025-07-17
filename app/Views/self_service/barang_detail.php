<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-primary text-white py-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Detail Barang</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa') ?>" class="text-white">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa/barang') ?>" class="text-white">Katalog Barang</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page"><?= $barang['namabarang'] ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Product Detail Section -->
<section class="py-4">
    <div class="container">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow">
                    <img src="<?= !empty($barang['foto']) ? base_url('uploads/barang/' . $barang['foto']) : 'https://via.placeholder.com/600x400?text=No+Image' ?>"
                        class="card-img-top img-fluid" alt="<?= $barang['namabarang'] ?>">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <h2 class="mb-3"><?= $barang['namabarang'] ?></h2>

                <div class="mb-3">
                    <h4 class="text-primary mb-0">Rp <?= number_format($barang['hargasewa'], 0, ',', '.') ?></h4>
                    <small class="text-muted">per <?= $barang['satuan'] ?></small>
                </div>

                <div class="mb-3">
                    <span class="badge <?= $barang['jumlah'] > 0 ? 'bg-success' : 'bg-danger' ?> p-2">
                        <?= $barang['jumlah'] > 0 ? 'Tersedia: ' . $barang['jumlah'] . ' ' . $barang['satuan'] : 'Stok Habis' ?>
                    </span>
                </div>

                <hr>

                <!-- Add to Cart Form -->
                <?php if (session()->get('logged_in')): ?>
                    <?php if ($barang['jumlah'] > 0): ?>
                        <form action="<?= site_url('sewa/cart/add') ?>" method="post">
                            <input type="hidden" name="barang_id" value="<?= $barang['kdbarang'] ?>">

                            <div class="mb-3">
                                <label for="qty" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="qty" name="qty" min="1" max="<?= $barang['jumlah'] ?>" value="1" required>
                                <div class="form-text">Maksimal <?= $barang['jumlah'] ?> <?= $barang['satuan'] ?></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                                        min="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                                        min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-cart-plus me-2"></i> Tambahkan ke Keranjang
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i> Maaf, barang ini sedang tidak tersedia.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Silakan <a href="<?= site_url('auth/login') ?>" class="alert-link">login</a> untuk menyewa barang ini.
                    </div>
                <?php endif; ?>

                <hr>

                <!-- Product Description -->
                <div class="mt-4">
                    <h5>Informasi Tambahan</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Kode Barang</span>
                            <span><?= $barang['kdbarang'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Satuan</span>
                            <span><?= $barang['satuan'] ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Status</span>
                            <span><?= $barang['jumlah'] > 0 ? 'Tersedia' : 'Habis' ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Barang Terkait</h3>
                <!-- This would typically be populated from the database based on category or similar criteria -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Sedang memuat barang terkait...
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Date validation
        $('#tanggal_mulai').on('change', function() {
            const startDate = new Date($(this).val());
            const nextDay = new Date(startDate);
            nextDay.setDate(nextDay.getDate() + 1);

            const nextDayFormatted = nextDay.toISOString().split('T')[0];
            $('#tanggal_selesai').attr('min', nextDayFormatted);

            // If end date is earlier than new min date, update it
            if (new Date($('#tanggal_selesai').val()) <= startDate) {
                $('#tanggal_selesai').val(nextDayFormatted);
            }
        });

        // Set today as the default start date
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal_mulai').val(today);

        // Set tomorrow as the default end date
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
        $('#tanggal_selesai').val(tomorrowFormatted);
    });
</script>
<?= $this->endSection() ?>
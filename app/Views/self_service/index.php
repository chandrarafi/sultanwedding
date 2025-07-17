<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3">Sewa Perlengkapan Pernikahan</h1>
                <p class="lead mb-4">Temukan berbagai perlengkapan pernikahan berkualitas untuk mewujudkan pernikahan impian Anda dengan harga terjangkau.</p>
                <a href="<?= site_url('sewa/barang') ?>" class="btn btn-light btn-lg">Lihat Katalog</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1169&q=80" alt="Wedding Decoration" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-3x mb-3" style="color: var(--primary);"></i>
                        <h4>Kualitas Terjamin</h4>
                        <p class="text-muted">Semua perlengkapan pernikahan kami selalu dijaga kualitasnya untuk kepuasan pelanggan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-shipping-fast fa-3x mb-3" style="color: var(--primary);"></i>
                        <h4>Pengiriman Cepat</h4>
                        <p class="text-muted">Kami menyediakan layanan pengiriman cepat agar barang dapat sampai tepat waktu.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-wallet fa-3x mb-3" style="color: var(--primary);"></i>
                        <h4>Harga Terjangkau</h4>
                        <p class="text-muted">Nikmati harga sewa yang bersaing dengan kualitas yang terjamin.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="mb-0">Barang Pilihan</h2>
                <p class="text-muted">Beberapa barang populer untuk pernikahan impian Anda</p>
            </div>
        </div>
        <div class="row">
            <?php foreach ($featuredItems as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="<?= !empty($item['foto']) ? base_url('uploads/barang/' . $item['foto']) : 'https://via.placeholder.com/300x200?text=No+Image' ?>" class="card-img-top" alt="<?= $item['namabarang'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $item['namabarang'] ?></h5>
                            <p class="card-text text-muted"><?= $item['satuan'] ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Rp <?= number_format($item['hargasewa'], 0, ',', '.') ?></h6>
                                <span class="badge bg-success">Stok: <?= $item['jumlah'] ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <a href="<?= site_url('sewa/barang/' . $item['kdbarang']) ?>" class="btn btn-outline-primary w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="<?= site_url('sewa/barang') ?>" class="btn btn-primary">Lihat Semua Barang</a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="mb-0">Apa Kata Mereka</h2>
                <p class="text-muted">Testimonial dari pelanggan kami</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text">"Sultan Wedding sangat membantu dalam menyediakan perlengkapan pernikahan kami. Barang-barangnya bagus dan pelayanannya memuaskan."</p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Customer" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Jakarta</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text">"Pengalaman sewa yang menyenangkan. Prosesnya mudah dan barang yang disewakan berkualitas bagus. Akan menyewa lagi di lain waktu."</p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">David Chen</h6>
                                <small class="text-muted">Surabaya</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                        </div>
                        <p class="card-text">"Barang-barang yang disewakan sangat lengkap dan sesuai dengan ekspektasi. Pengembalian juga sangat mudah. Terima kasih Sultan Wedding!"</p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Customer" class="rounded-circle me-3" width="50">
                            <div>
                                <h6 class="mb-0">Anisa Rahman</h6>
                                <small class="text-muted">Bandung</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="mb-0">Siap untuk menyewa perlengkapan pernikahan?</h2>
                <p class="lead mb-0">Jelajahi katalog kami dan temukan perlengkapan pernikahan yang Anda butuhkan.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?= site_url('sewa/barang') ?>" class="btn btn-light btn-lg">Mulai Sekarang</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
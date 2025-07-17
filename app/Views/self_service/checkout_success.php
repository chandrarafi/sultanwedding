<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-primary text-white py-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Pesanan Berhasil</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa') ?>" class="text-white">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa/cart') ?>" class="text-white">Keranjang</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Pesanan Berhasil</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Success Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center py-5">
                        <div class="display-1 text-success mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2 class="mb-3">Pesanan Anda Berhasil!</h2>
                        <p class="text-muted mb-4">Terima kasih telah menyewa di Sultan Wedding. Pesanan Anda telah berhasil dibuat dan sedang diproses.</p>
                        <div class="d-flex justify-content-center mb-4">
                            <div class="alert alert-primary d-inline-block">
                                <h5 class="mb-0">Nomor Pesanan: <?= $order['nopesanan'] ?></h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6>Informasi Pesanan</h6>
                                <p class="small mb-1">
                                    <span class="text-muted">Tanggal Pesanan:</span>
                                    <?= date('d M Y', strtotime($order['tanggalpemesanan'])) ?>
                                </p>
                                <p class="small mb-1">
                                    <span class="text-muted">Tanggal Sewa:</span>
                                    <?= date('d M Y', strtotime($order['tanggalmulai'])) ?> -
                                    <?= date('d M Y', strtotime($order['tanggalselesai'])) ?>
                                </p>
                                <p class="small mb-0">
                                    <span class="text-muted">Status Pesanan:</span>
                                    <span class="badge bg-warning text-dark"><?= ucfirst($order['status']) ?></span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Informasi Pembayaran</h6>
                                <p class="small mb-1">
                                    <span class="text-muted">Total Pesanan:</span>
                                    Rp <?= number_format($order['total'], 0, ',', '.') ?>
                                </p>
                                <p class="small mb-1">
                                    <span class="text-muted">Metode Pembayaran:</span>
                                    <?= ucfirst($order['metodepembayaran']) ?>
                                </p>
                                <p class="small mb-0">
                                    <span class="text-muted">Status Pembayaran:</span>
                                    <span class="badge bg-danger">Belum Bayar</span>
                                </p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <?php if ($order['metodepembayaran'] == 'transfer'): ?>
                            <div class="alert alert-info">
                                <h6 class="mb-3">Instruksi Pembayaran</h6>
                                <p class="mb-3">Silakan transfer pembayaran uang muka (DP) sebesar <strong>Rp <?= number_format($order['total'] * 0.5, 0, ',', '.') ?></strong> ke salah satu rekening bank berikut:</p>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Bank BCA</h6>
                                                <p class="mb-0">No. Rekening: 1234567890</p>
                                                <p class="mb-0">Atas Nama: PT. Sultan Wedding</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Bank Mandiri</h6>
                                                <p class="mb-0">No. Rekening: 0987654321</p>
                                                <p class="mb-0">Atas Nama: PT. Sultan Wedding</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="mb-3">Setelah melakukan pembayaran, silakan unggah bukti transfer melalui halaman detail pesanan Anda.</p>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4 text-center">
                            <a href="<?= site_url('sewa/orders/' . $order['nopesanan']) ?>" class="btn btn-primary">
                                <i class="fas fa-file-alt me-2"></i> Lihat Detail Pesanan
                            </a>
                            <a href="<?= site_url('sewa/orders') ?>" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-list me-2"></i> Riwayat Pesanan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="mb-3">Informasi Penting</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-info-circle me-2 text-primary"></i> Silakan unggah bukti pembayaran sebelum 24 jam, atau pesanan akan otomatis dibatalkan.
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-info-circle me-2 text-primary"></i> Pengambilan barang dapat dilakukan setelah pembayaran uang muka (DP) terverifikasi.
                            </li>
                            <li>
                                <i class="fas fa-info-circle me-2 text-primary"></i> Jika ada pertanyaan, silakan hubungi customer service kami di <a href="tel:+6281234567890">+62 812-3456-7890</a>.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
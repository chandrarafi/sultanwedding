<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-primary text-white py-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Detail Pesanan #<?= $order['nopesanan'] ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa') ?>" class="text-white">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa/orders') ?>" class="text-white">Riwayat Pesanan</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Detail Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Order Details Section -->
<section class="py-4">
    <div class="container">
        <div class="row">
            <!-- Order Info -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informasi Pesanan</h5>
                        <?php
                        $statusBadge = 'bg-secondary';
                        switch ($order['status']) {
                            case 'pending':
                                $statusBadge = 'bg-warning text-dark';
                                break;
                            case 'processed':
                                $statusBadge = 'bg-info text-dark';
                                break;
                            case 'completed':
                                $statusBadge = 'bg-success';
                                break;
                            case 'cancelled':
                                $statusBadge = 'bg-danger';
                                break;
                        }
                        ?>
                        <span class="badge <?= $statusBadge ?>"><?= ucfirst($order['status']) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6>Detail Pesanan</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><span class="text-muted">Nomor Pesanan:</span> <?= $order['nopesanan'] ?></li>
                                    <li class="mb-2"><span class="text-muted">Tanggal Pesanan:</span> <?= date('d M Y', strtotime($order['tanggalpemesanan'])) ?></li>
                                    <li class="mb-2"><span class="text-muted">Tanggal Sewa:</span> <?= date('d M Y', strtotime($order['tanggalmulai'])) ?></li>
                                    <li class="mb-2"><span class="text-muted">Tanggal Selesai:</span> <?= date('d M Y', strtotime($order['tanggalselesai'])) ?></li>
                                    <li>
                                        <span class="text-muted">Durasi:</span>
                                        <?php
                                        $start = new DateTime($order['tanggalmulai']);
                                        $end = new DateTime($order['tanggalselesai']);
                                        $days = $end->diff($start)->days;
                                        echo $days . ' hari';
                                        ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Pembayaran</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><span class="text-muted">Total:</span> Rp <?= number_format($order['total'], 0, ',', '.') ?></li>
                                    <li class="mb-2"><span class="text-muted">Metode Pembayaran:</span> <?= ucfirst($order['metodepembayaran']) ?></li>
                                    <li class="mb-2">
                                        <span class="text-muted">Status Pembayaran:</span>
                                        <?php
                                        $paymentBadge = 'bg-secondary';
                                        switch ($order['statuspembayaran']) {
                                            case 'belum':
                                                $paymentBadge = 'bg-danger';
                                                $paymentStatus = 'Belum Bayar';
                                                break;
                                            case 'verifikasi':
                                                $paymentBadge = 'bg-warning text-dark';
                                                $paymentStatus = 'Verifikasi';
                                                break;
                                            case 'dp':
                                                $paymentBadge = 'bg-info text-dark';
                                                $paymentStatus = 'DP';
                                                break;
                                            case 'lunas':
                                                $paymentBadge = 'bg-success';
                                                $paymentStatus = 'Lunas';
                                                break;
                                            default:
                                                $paymentStatus = ucfirst($order['statuspembayaran']);
                                        }
                                        ?>
                                        <span class="badge <?= $paymentBadge ?>"><?= $paymentStatus ?></span>
                                    </li>
                                    <li><span class="text-muted">Jenis Pembayaran:</span> <?= $order['jenispembayaran'] == 'dp' ? 'Uang Muka (DP)' : 'Lunas' ?></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <h6 class="mb-3">Barang yang Disewa</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Barang</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($details as $detail): ?>
                                        <tr>
                                            <td><?= $detail['namabarang'] ?></td>
                                            <td>Rp <?= number_format($detail['hargasewa'], 0, ',', '.') ?></td>
                                            <td><?= $detail['jumlah'] ?></td>
                                            <td class="text-end">Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th class="text-end">Rp <?= number_format($order['total'], 0, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Riwayat Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($payments)): ?>
                            <p class="text-center text-muted my-3">Belum ada riwayat pembayaran.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Metode</th>
                                            <th>Jenis</th>
                                            <th>Status</th>
                                            <th>Bukti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payments as $payment): ?>
                                            <tr>
                                                <td><?= date('d M Y', strtotime($payment['tanggalpembayaran'])) ?></td>
                                                <td>Rp <?= number_format($payment['jumlahpembayaran'], 0, ',', '.') ?></td>
                                                <td><?= ucfirst($payment['metodepembayaran']) ?></td>
                                                <td><?= $payment['jenispembayaran'] == 'dp' ? 'Uang Muka (DP)' : 'Pelunasan' ?></td>
                                                <td>
                                                    <?php
                                                    $statusBadge = 'bg-secondary';
                                                    switch ($payment['status']) {
                                                        case 'pending':
                                                            $statusBadge = 'bg-warning text-dark';
                                                            break;
                                                        case 'verifikasi':
                                                            $statusBadge = 'bg-info text-dark';
                                                            break;
                                                        case 'success':
                                                            $statusBadge = 'bg-success';
                                                            break;
                                                        case 'rejected':
                                                            $statusBadge = 'bg-danger';
                                                            break;
                                                    }
                                                    ?>
                                                    <span class="badge <?= $statusBadge ?>"><?= ucfirst($payment['status']) ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($payment['bukti'])): ?>
                                                        <a href="<?= base_url('uploads/pembayaran/' . $payment['bukti']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-file-image"></i> Lihat
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Payment Actions -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Aksi</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        // Show different actions based on order and payment status
                        if ($order['status'] == 'cancelled'):
                        ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i> Pesanan ini telah dibatalkan.
                            </div>
                        <?php elseif ($order['statuspembayaran'] == 'belum'): ?>
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i> Mohon lakukan pembayaran uang muka untuk melanjutkan pesanan ini.
                            </div>

                            <?php if ($order['metodepembayaran'] == 'transfer'): ?>
                                <div class="mb-4">
                                    <h6 class="mb-3">Instruksi Pembayaran</h6>
                                    <p class="small">Silakan transfer uang muka sebesar <strong>Rp <?= number_format($order['total'] * 0.5, 0, ',', '.') ?></strong> ke rekening berikut:</p>
                                    <div class="card bg-light mb-3">
                                        <div class="card-body py-2">
                                            <p class="small mb-1"><strong>Bank BCA</strong></p>
                                            <p class="small mb-1">No. Rekening: 1234567890</p>
                                            <p class="small mb-0">Atas Nama: PT. Sultan Wedding</p>
                                        </div>
                                    </div>
                                </div>

                                <form action="<?= site_url('sewa/payment/upload/' . $order['nopesanan']) ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="payment_id" value="<?= $payments[0]['idpembayaran'] ?? '' ?>">

                                    <div class="mb-3">
                                        <label for="bukti" class="form-label">Upload Bukti Pembayaran</label>
                                        <input type="file" class="form-control" id="bukti" name="bukti" accept="image/*" required>
                                        <div class="form-text">Format: JPG, PNG, JPEG. Maksimal 2MB.</div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload me-2"></i> Upload Bukti Pembayaran
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        <?php elseif ($order['statuspembayaran'] == 'verifikasi'): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Pembayaran sedang diverifikasi. Mohon tunggu konfirmasi dari admin.
                            </div>
                        <?php elseif ($order['statuspembayaran'] == 'dp' && $order['status'] != 'completed'): ?>
                            <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle me-2"></i> Uang muka telah diterima. Anda dapat mengambil barang sesuai tanggal yang ditentukan.
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Pelunasan sebesar <strong>Rp <?= number_format($order['total'] * 0.5, 0, ',', '.') ?></strong> dapat dilakukan saat pengembalian barang.
                            </div>
                        <?php elseif ($order['statuspembayaran'] == 'lunas'): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> Pembayaran telah lunas. Terima kasih telah menyewa di Sultan Wedding.
                            </div>
                        <?php endif; ?>

                        <?php if ($order['status'] != 'cancelled' && $order['status'] != 'completed'): ?>
                            <hr>
                            <div class="d-grid">
                                <a href="<?= site_url('sewa/orders') ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Pesanan
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="mb-3">Butuh Bantuan?</h6>
                        <p class="small mb-3">Jika Anda memiliki pertanyaan atau kendala terkait pesanan ini, silakan hubungi customer service kami.</p>
                        <div class="d-grid gap-2">
                            <a href="tel:+6281234567890" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone me-2"></i> +62 812-3456-7890
                            </a>
                            <a href="mailto:info@sultanwedding.com" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-2"></i> info@sultanwedding.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
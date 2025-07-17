<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pembayaran</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pembayaran') ?>">Daftar Pembayaran</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pembayaran</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Detail Pemesanan -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Detail Pemesanan</h5>
                </div>
                <hr>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Kode Pemesanan</div>
                    <div class="col-md-8"><?= $pemesanan['kdpemesananpaket'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Tanggal Acara</div>
                    <div class="col-md-8"><?= date('d-m-Y', strtotime($pemesanan['tgl'])) ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Nama Pelanggan</div>
                    <div class="col-md-8"><?= $pemesanan['namapelanggan'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Paket</div>
                    <div class="col-md-8"><?= $pemesanan['namapaket'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Harga Paket</div>
                    <div class="col-md-8">Rp <?= number_format($pemesanan['harga'], 0, ',', '.') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Jumlah Hari</div>
                    <div class="col-md-8"><?= $pemesanan['jumlahhari'] ?> hari</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Luas Lokasi</div>
                    <div class="col-md-8"><?= $pemesanan['luaslokasi'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Alamat</div>
                    <div class="col-md-8"><?= $pemesanan['alamatpesanan'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Grand Total</div>
                    <div class="col-md-8 fw-bold text-primary">Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Status Pemesanan</div>
                    <div class="col-md-8">
                        <?php
                        $statusBadge = 'bg-warning';
                        $statusText = 'Pending';

                        switch ($pemesanan['status']) {
                            case 'pending':
                                $statusBadge = 'bg-warning';
                                $statusText = 'Pending';
                                break;
                            case 'process':
                                $statusBadge = 'bg-info';
                                $statusText = 'Diproses';
                                break;
                            case 'completed':
                                $statusBadge = 'bg-success';
                                $statusText = 'Selesai';
                                break;
                            case 'cancelled':
                                $statusBadge = 'bg-danger';
                                $statusText = 'Dibatalkan';
                                break;
                        }
                        ?>
                        <span class="badge <?= $statusBadge ?>"><?= $statusText ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Pembayaran -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Detail Pembayaran</h5>
                </div>
                <hr>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Kode Pembayaran</div>
                    <div class="col-md-8"><?= $pemesanan['kdpembayaran'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Metode Pembayaran</div>
                    <div class="col-md-8"><?= ucfirst($pemesanan['metodepembayaran']) ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Status Pembayaran</div>
                    <div class="col-md-8">
                        <?php
                        $paymentStatusBadge = 'bg-warning';
                        $paymentStatusText = 'Menunggu Pembayaran';

                        switch ($pemesanan['status']) {
                            case 'pending':
                                $paymentStatusBadge = 'bg-warning';
                                $paymentStatusText = 'Menunggu Konfirmasi DP';
                                break;
                            case 'partial':
                                $paymentStatusBadge = 'bg-info';
                                $paymentStatusText = 'DP Dibayar';
                                break;
                            case 'success':
                                $paymentStatusBadge = 'bg-success';
                                $paymentStatusText = 'Lunas';
                                break;
                            case 'rejected':
                                $paymentStatusBadge = 'bg-danger';
                                $paymentStatusText = 'Ditolak';
                                break;
                        }
                        ?>
                        <span class="badge <?= $paymentStatusBadge ?>"><?= $paymentStatusText ?></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Total Dibayar</div>
                    <div class="col-md-8">Rp <?= number_format($pemesanan['totalpembayaran'], 0, ',', '.') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Sisa Pembayaran</div>
                    <div class="col-md-8">Rp <?= number_format($pemesanan['sisa'], 0, ',', '.') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Progress Pembayaran</div>
                    <div class="col-md-8">
                        <?php
                        $percent = 0;
                        if (isset($pemesanan['totalpembayaran']) && isset($pemesanan['grandtotal']) && $pemesanan['grandtotal'] > 0) {
                            $percent = ($pemesanan['totalpembayaran'] / $pemesanan['grandtotal']) * 100;
                        }
                        ?>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percent ?>%;" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100"><?= number_format($percent, 0) ?>%</div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($pemesanan['buktipembayaran'])) : ?>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Bukti Pembayaran</div>
                        <div class="col-md-8">
                            <a href="<?= base_url('uploads/pembayaran/' . $pemesanan['buktipembayaran']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-image"></i> Lihat Bukti Pembayaran
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tombol Aksi -->
                <div class="mt-4">
                    <h6 class="mb-3">Aksi Pembayaran</h6>

                    <!-- Debug info (hanya untuk admin) -->
                    <!-- <div class="alert alert-secondary mb-3">
                        <h6>Debug Info (Admin Only)</h6>
                        <p class="mb-1">Status: <?= $pemesanan['status'] ?? 'not set' ?></p>
                        <p class="mb-1">Tipe Pembayaran: <?= $pemesanan['tipepembayaran'] ?? 'not set' ?></p>
                        <p class="mb-1">Full Confirmed: <?= isset($pemesanan['full_confirmed']) ? $pemesanan['full_confirmed'] : 'not set' ?></p>
                        <p class="mb-1">Full Paid: <?= isset($pemesanan['full_paid']) ? $pemesanan['full_paid'] : 'not set' ?></p>
                        <p class="mb-1">H1 Confirmed: <?= isset($pemesanan['h1_confirmed']) ? $pemesanan['h1_confirmed'] : 'not set' ?></p>
                        <p class="mb-1">H1 Paid: <?= isset($pemesanan['h1_paid']) ? $pemesanan['h1_paid'] : 'not set' ?></p>
                        <p class="mb-1">DP Confirmed: <?= isset($pemesanan['dp_confirmed']) ? $pemesanan['dp_confirmed'] : 'not set' ?></p>
                        <p class="mb-1">Kondisi Tombol Konfirmasi Pelunasan: <?= ($pemesanan['status'] == 'partial' && isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas' && (!isset($pemesanan['full_confirmed']) || empty($pemesanan['full_confirmed']))) ? 'true' : 'false' ?></p>
                    </div> -->

                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($pemesanan['status'] == 'pending' && (!isset($pemesanan['dp_confirmed']) || empty($pemesanan['dp_confirmed']))): ?>
                            <!-- Konfirmasi DP -->
                            <button type="button" class="btn btn-success confirm-action"
                                data-action="konfirmasi-dp"
                                data-url="<?= site_url('admin/pembayaran/konfirmasi-dp/' . $pemesanan['kdpemesananpaket']) ?>"
                                data-title="Konfirmasi Pembayaran DP"
                                data-message="Anda yakin akan mengkonfirmasi pembayaran DP ini?">
                                <i class="bx bx-check-circle"></i> Konfirmasi DP
                            </button>
                        <?php elseif ($pemesanan['status'] == 'partial' && isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'dp2' && (!isset($pemesanan['h1_confirmed']) || empty($pemesanan['h1_confirmed']))): ?>
                            <!-- Konfirmasi H-1 -->
                            <button type="button" class="btn btn-success confirm-action"
                                data-action="konfirmasi-h1"
                                data-url="<?= site_url('admin/pembayaran/konfirmasi-h1/' . $pemesanan['kdpemesananpaket']) ?>"
                                data-title="Konfirmasi Pembayaran H-1"
                                data-message="Anda yakin akan mengkonfirmasi pembayaran H-1 ini?">
                                <i class="bx bx-check-circle"></i> Konfirmasi Pembayaran H-1
                            </button>
                        <?php elseif ($pemesanan['status'] == 'partial' && isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas' && (!isset($pemesanan['full_confirmed']) || empty($pemesanan['full_confirmed']))): ?>
                            <!-- Konfirmasi Pelunasan -->
                            <button type="button" class="btn btn-success confirm-action"
                                data-action="konfirmasi-pelunasan"
                                data-url="<?= site_url('admin/pembayaran/konfirmasi-pelunasan/' . $pemesanan['kdpemesananpaket']) ?>"
                                data-title="Konfirmasi Pelunasan"
                                data-message="Anda yakin akan mengkonfirmasi pelunasan ini?">
                                <i class="bx bx-check-circle"></i> Konfirmasi Pelunasan
                            </button>
                        <?php elseif (($pemesanan['sisa'] == 0 || isset($pemesanan['full_paid']) && $pemesanan['full_paid'] == '1') && (!isset($pemesanan['full_confirmed']) || empty($pemesanan['full_confirmed']))): ?>
                            <!-- Konfirmasi Pelunasan (Alternatif) -->
                            <button type="button" class="btn btn-success confirm-action"
                                data-action="konfirmasi-pelunasan"
                                data-url="<?= site_url('admin/pembayaran/konfirmasi-pelunasan/' . $pemesanan['kdpemesananpaket']) ?>"
                                data-title="Konfirmasi Pelunasan"
                                data-message="Anda yakin akan mengkonfirmasi pelunasan ini?">
                                <i class="bx bx-check-circle"></i> Konfirmasi Pelunasan
                            </button>
                        <?php endif; ?>

                        <?php if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] != 1) && (!isset($pemesanan['h1_paid']) || $pemesanan['h1_paid'] != 1)): ?>
                            <!-- Bayar H-1 (Walk-in) -->
                            <button type="button" class="btn btn-primary confirm-action"
                                data-action="bayar-h1"
                                data-url="<?= site_url('admin/pembayaran/bayar-h1/' . $pemesanan['kdpemesananpaket']) ?>"
                                data-title="Bayar H-1 (Walk-in)"
                                data-message="Proses pembayaran H-1 untuk pelanggan yang datang langsung?">
                                <i class="bx bx-money"></i> Bayar H-1 (Walk-in)
                            </button>
                        <?php endif; ?>

                        <?php if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && (!isset($pemesanan['full_confirmed']) || $pemesanan['full_confirmed'] != 1) && $pemesanan['sisa'] > 0): ?>
                            <!-- Bayar Pelunasan (Walk-in) -->
                            <button type="button" class="btn btn-primary confirm-action"
                                data-action="bayar-pelunasan"
                                data-url="<?= site_url('admin/pembayaran/bayar-pelunasan/' . $pemesanan['kdpemesananpaket']) ?>"
                                data-title="Bayar Pelunasan (Walk-in)"
                                data-message="Proses pelunasan untuk pelanggan yang datang langsung?">
                                <i class="bx bx-money"></i> Bayar Pelunasan (Walk-in)
                            </button>
                        <?php endif; ?>

                        <?php if (in_array($pemesanan['status'], ['pending', 'partial']) && $pemesanan['status'] !== 'cancelled') : ?>
                            <!-- Tolak Pembayaran -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">
                                <i class="bx bx-x-circle"></i> Tolak Pembayaran
                            </button>
                        <?php endif; ?>

                        <a href="<?= site_url('admin/pembayaran') ?>" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline Pembayaran -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Timeline Pembayaran</h5>
                <div class="position-relative ms-3">
                    <div class="position-absolute top-0 start-0 bottom-0" style="width: 2px; background-color: #e9ecef; left: 7px;"></div>

                    <!-- DP Booking -->
                    <div class="d-flex mb-4">
                        <div class="position-absolute rounded-circle <?= isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] ? 'bg-success' : (isset($pemesanan['buktipembayaran']) && !empty($pemesanan['buktipembayaran']) ? 'bg-info' : 'bg-warning') ?>" style="width: 16px; height: 16px; left: 0;"></div>
                        <div class="ms-4">
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 fw-bold">DP Booking (10%)</h6>
                                <?php if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) : ?>
                                    <span class="badge bg-success ms-2">Dikonfirmasi</span>
                                <?php elseif (isset($pemesanan['buktipembayaran']) && !empty($pemesanan['buktipembayaran'])) : ?>
                                    <span class="badge bg-info ms-2">Menunggu Konfirmasi</span>
                                <?php else : ?>
                                    <span class="badge bg-warning ms-2">Belum Dibayar</span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-0 text-muted small">
                                <?php if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) : ?>
                                    Dikonfirmasi pada: <?= date('d-m-Y H:i', strtotime($pemesanan['dp_confirmed_at'])) ?>
                                <?php elseif (isset($pemesanan['buktipembayaran']) && !empty($pemesanan['buktipembayaran'])) : ?>
                                    Bukti pembayaran telah diunggah. Menunggu konfirmasi admin.
                                <?php else : ?>
                                    Pembayaran DP sebesar 10% dari total biaya.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <!-- Pembayaran H-1 -->
                    <div class="d-flex mb-4">
                        <div class="position-absolute rounded-circle 
                            <?php
                            if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed']) {
                                echo 'bg-success';
                            } elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid']) {
                                echo 'bg-info';
                            } elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) {
                                echo 'bg-secondary';
                            } else {
                                echo 'bg-light';
                            }
                            ?>"
                            style="width: 16px; height: 16px; left: 0;">
                        </div>
                        <div class="ms-4">
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 fw-bold">Pembayaran H-1 (10%)</h6>
                                <?php if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed']) : ?>
                                    <span class="badge bg-success ms-2">Dikonfirmasi</span>
                                <?php elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid']) : ?>
                                    <span class="badge bg-info ms-2">Menunggu Konfirmasi</span>
                                <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) : ?>
                                    <span class="badge bg-secondary ms-2">Belum Dibayar</span>
                                <?php else : ?>
                                    <span class="badge bg-light text-dark ms-2">Menunggu DP</span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-0 text-muted small">
                                <?php if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed']) : ?>
                                    Dikonfirmasi pada: <?= date('d-m-Y H:i', strtotime($pemesanan['h1_confirmed_at'])) ?>
                                <?php elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid']) : ?>
                                    Pembayaran H-1 telah dilakukan. Menunggu konfirmasi admin.
                                <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) : ?>
                                    Pembayaran H-1 sebesar 10% dari total biaya perlu dilakukan.
                                <?php else : ?>
                                    Pembayaran H-1 dapat dilakukan setelah DP dikonfirmasi.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <!-- Pelunasan -->
                    <div class="d-flex">
                        <div class="position-absolute rounded-circle 
                            <?php
                            if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed']) {
                                echo 'bg-success';
                            } elseif (isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') {
                                echo 'bg-info';
                            } elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) {
                                echo 'bg-secondary';
                            } else {
                                echo 'bg-light';
                            }
                            ?>"
                            style="width: 16px; height: 16px; left: 0;">
                        </div>
                        <div class="ms-4">
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 fw-bold">Pelunasan (80%)</h6>
                                <?php if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed']) : ?>
                                    <span class="badge bg-success ms-2">Dikonfirmasi</span>
                                <?php elseif (isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') : ?>
                                    <span class="badge bg-info ms-2">Menunggu Konfirmasi</span>
                                <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) : ?>
                                    <span class="badge bg-secondary ms-2">Belum Dibayar</span>
                                <?php else : ?>
                                    <span class="badge bg-light text-dark ms-2">Menunggu DP</span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-0 text-muted small">
                                <?php if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed']) : ?>
                                    Dikonfirmasi pada: <?= date('d-m-Y H:i', strtotime($pemesanan['full_confirmed_at'])) ?>
                                <?php elseif (isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') : ?>
                                    Pelunasan telah dilakukan. Menunggu konfirmasi admin.
                                <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) : ?>
                                    Pelunasan sebesar 80% dari total biaya perlu dilakukan.
                                <?php else : ?>
                                    Pelunasan dapat dilakukan setelah DP dikonfirmasi.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form untuk submit konfirmasi -->
<form id="confirmForm" method="post" style="display: none;">
    <?= csrf_field() ?>
</form>

<!-- Modal Tolak Pembayaran -->
<div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tolakModalLabel">Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/pembayaran/tolak/' . $pemesanan['kdpemesananpaket']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Handle confirmation buttons
        $('.confirm-action').on('click', function() {
            const url = $(this).data('url');
            const title = $(this).data('title');
            const message = $(this).data('message');
            const action = $(this).data('action');

            Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        allowEnterKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // For methods that need POST request
                    if (action === 'konfirmasi-dp' || action === 'konfirmasi-h1' || action === 'konfirmasi-pelunasan') {
                        const form = $('#confirmForm');
                        form.attr('action', url);
                        form.submit();
                    } else {
                        // For methods that use GET request
                        window.location.href = url;
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
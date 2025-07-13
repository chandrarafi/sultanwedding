<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan Paket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananpaket') ?>">Daftar Pemesanan Paket</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pemesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Order Information -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Informasi Pemesanan</h5>
                    <?php if ($pemesanan['status'] !== 'cancelled') : ?>
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                <i class="bx bx-edit"></i> Update Status
                            </button>
                            <?php if ($pemesanan['status'] !== 'completed') : ?>
                                <button class="btn btn-sm btn-danger btn-cancel-order" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                    <i class="bx bx-x"></i> Batalkan
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
                    <div class="col-md-4 fw-bold">Tanggal Pemesanan</div>
                    <div class="col-md-8"><?= date('d-m-Y H:i', strtotime($pemesanan['created_at'])) ?></div>
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

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Nama Pelanggan</div>
                    <div class="col-md-8"><?= $pemesanan['namapelanggan'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Nama Paket</div>
                    <div class="col-md-8"><?= $pemesanan['namapaket'] ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Harga Paket</div>
                    <div class="col-md-8">Rp <?= number_format($pemesanan['hargapaket'], 0, ',', '.') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Jumlah Hari</div>
                    <div class="col-md-8">
                        <?= $pemesanan['jumlahhari'] ?> hari
                        <?php if ($pemesanan['jumlahhari'] > 4) : ?>
                            <span class="badge bg-info">Dikenakan biaya tambahan 10%</span>
                        <?php endif; ?>
                    </div>
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
                    <div class="col-md-8 fw-bold text-primary">
                        Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?>
                        <?php if ($pemesanan['jumlahhari'] > 4) : ?>
                            <div class="small text-muted mt-1">
                                Harga Paket: Rp <?= number_format($pemesanan['hargapaket'], 0, ',', '.') ?><br>
                                Biaya Tambahan (10%): Rp <?= number_format($pemesanan['hargapaket'] * 0.1, 0, ',', '.') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="col-12 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Informasi Pembayaran</h5>
                    <?php if (!empty($pemesanan['kdpembayaran'])) : ?>
                        <div class="ms-auto">
                            <a href="<?= site_url('admin/pembayaran/detail/' . $pemesanan['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                <i class="bx bx-money"></i> Detail Pembayaran
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <hr>

                <?php if (!empty($pemesanan['kdpembayaran'])) : ?>
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
                            $paymentStatus = isset($pemesanan['statuspembayaran']) ? $pemesanan['statuspembayaran'] : 'pending';
                            $paymentBadge = 'bg-warning';
                            $paymentText = 'Menunggu Pembayaran';

                            // First check if full payment is confirmed
                            if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1) {
                                $paymentBadge = 'bg-success';
                                $paymentText = 'Lunas';
                            }
                            // Then check if full payment is pending confirmation
                            else if ((isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') ||
                                (isset($pemesanan['full_paid']) && $pemesanan['full_paid'] == 1)
                            ) {
                                $paymentBadge = 'bg-info';
                                $paymentText = 'Pelunasan Menunggu Konfirmasi';
                            }
                            // Then check other payment statuses
                            else {
                                switch ($paymentStatus) {
                                    case 'pending':
                                        $paymentBadge = 'bg-warning';
                                        $paymentText = 'Menunggu Pembayaran';
                                        break;
                                    case 'partial':
                                        if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1) {
                                            $paymentBadge = 'bg-info';
                                            $paymentText = 'Pembayaran H-1 Dikonfirmasi';
                                        } else if (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1) {
                                            $paymentBadge = 'bg-info';
                                            $paymentText = 'Pembayaran H-1 Menunggu Konfirmasi';
                                        } else {
                                            $paymentBadge = 'bg-info';
                                            $paymentText = 'DP Dibayar';
                                        }
                                        break;
                                    case 'success':
                                        $paymentBadge = 'bg-success';
                                        $paymentText = 'Lunas';
                                        break;
                                    case 'failed':
                                    case 'rejected':
                                        $paymentBadge = 'bg-danger';
                                        $paymentText = 'Ditolak';
                                        break;
                                }
                            }
                            ?>
                            <span class="badge <?= $paymentBadge ?>"><?= $paymentText ?></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Total Dibayar</div>
                        <div class="col-md-8">Rp <?= number_format(isset($pemesanan['totalpembayaran']) ? $pemesanan['totalpembayaran'] : 0, 0, ',', '.') ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Sisa Pembayaran</div>
                        <div class="col-md-8">Rp <?= number_format(isset($pemesanan['sisa']) ? $pemesanan['sisa'] : $pemesanan['grandtotal'], 0, ',', '.') ?></div>
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

                    <!-- Payment Timeline -->
                    <div class="mt-4">
                        <h6 class="mb-3">Timeline Pembayaran</h6>
                        <div class="position-relative ms-3 mb-4">
                            <div class="position-absolute top-0 start-0 bottom-0" style="width: 2px; background-color: #e9ecef; left: 7px;"></div>

                            <!-- DP Booking -->
                            <div class="d-flex mb-3">
                                <div class="position-absolute rounded-circle <?= isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] ? 'bg-success' : (isset($pemesanan['buktipembayaran']) && !empty($pemesanan['buktipembayaran']) ? 'bg-info' : 'bg-warning') ?>" style="width: 16px; height: 16px; left: 0;"></div>
                                <div class="ms-4">
                                    <div class="fw-bold">DP Booking (10%)</div>
                                    <div class="text-muted small">
                                        <?php if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed']) : ?>
                                            <span class="text-success">Dikonfirmasi pada <?= date('d-m-Y H:i', strtotime($pemesanan['dp_confirmed_at'])) ?></span>
                                        <?php elseif (isset($pemesanan['buktipembayaran']) && !empty($pemesanan['buktipembayaran'])) : ?>
                                            <span class="text-info">Menunggu konfirmasi admin</span>
                                        <?php else : ?>
                                            <span class="text-warning">Menunggu pembayaran</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- H-1 Payment -->
                            <div class="d-flex mb-3">
                                <div class="position-absolute rounded-circle <?= isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 ? 'bg-success' : (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1 ? 'bg-info' : 'bg-secondary') ?>" style="width: 16px; height: 16px; left: 0;"></div>
                                <div class="ms-4">
                                    <div class="fw-bold">Pembayaran H-1 (10%)</div>
                                    <div class="text-muted small">
                                        <?php if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1) : ?>
                                            <span class="text-success">Dikonfirmasi pada <?= date('d-m-Y H:i', strtotime($pemesanan['h1_confirmed_at'])) ?></span>
                                        <?php elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1) : ?>
                                            <span class="text-info">Menunggu konfirmasi admin</span>
                                        <?php else : ?>
                                            <span class="text-secondary">Belum dibayar</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Payment -->
                            <div class="d-flex">
                                <div class="position-absolute rounded-circle <?= isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] ? 'bg-success' : (isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas' ? 'bg-info' : 'bg-secondary') ?>" style="width: 16px; height: 16px; left: 0;"></div>
                                <div class="ms-4">
                                    <div class="fw-bold">Pelunasan (80%)</div>
                                    <div class="text-muted small">
                                        <?php if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed']) : ?>
                                            <span class="text-success">Dikonfirmasi pada <?= date('d-m-Y H:i', strtotime($pemesanan['full_confirmed_at'])) ?></span>
                                        <?php elseif (isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') : ?>
                                            <span class="text-info">Menunggu konfirmasi admin</span>
                                        <?php else : ?>
                                            <span class="text-secondary">Belum dibayar</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info">
                        Belum ada informasi pembayaran.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Back Button -->
<div class="mt-3">
    <a href="<?= site_url('admin/pemesananpaket') ?>" class="btn btn-secondary">
        <i class="bx bx-arrow-back"></i> Kembali
    </a>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/pemesananpaket/update-status/' . $pemesanan['kdpemesananpaket']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" <?= $pemesanan['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="process" <?= $pemesanan['status'] == 'process' ? 'selected' : '' ?>>Diproses</option>
                            <option value="completed" <?= $pemesanan['status'] == 'completed' ? 'selected' : '' ?>>Selesai</option>
                            <option value="cancelled" <?= $pemesanan['status'] == 'cancelled' ? 'selected' : '' ?>>Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-update-status">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Batalkan Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/pemesananpaket/cancel/' . $pemesanan['kdpemesananpaket']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="alasan" class="form-label">Alasan Pembatalan</label>
                        <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-cancel-order">Batalkan Pemesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Show SweetAlert for success messages
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('success') ?>',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        // Show SweetAlert for error messages
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                title: 'Error!',
                text: '<?= session()->getFlashdata('error') ?>',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        // Confirmation for cancel order
        $('.btn-cancel-order').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Konfirmasi Pembatalan',
                text: "Apakah Anda yakin ingin membatalkan pesanan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Confirmation for update status
        $('.btn-update-status').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Konfirmasi Update Status',
                text: "Apakah Anda yakin ingin mengubah status pesanan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Update!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan Paket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Pemesanan Paket</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Daftar Pemesanan Paket</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/pemesananpaket/create') ?>" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Pemesanan
                </a>
            </div>
        </div>
        <hr>

        <!-- Tabs for different status -->
        <ul class="nav nav-tabs" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                    Semua
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">
                    Pending
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="process-tab" data-bs-toggle="tab" data-bs-target="#process" type="button" role="tab" aria-controls="process" aria-selected="false">
                    Diproses
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                    Selesai
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">
                    Dibatalkan
                </button>
            </li>
        </ul>

        <div class="tab-content py-3" id="orderTabContent">
            <!-- All Orders Tab -->
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-all">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Pemesanan</th>
                                <th>Tanggal Acara</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($pemesanan as $order) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $order['kdpemesananpaket'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($order['tgl'])) ?></td>
                                    <td><?= $order['namapelanggan'] ?></td>
                                    <td><?= $order['namapaket'] ?></td>
                                    <td>Rp <?= number_format($order['grandtotal'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $statusBadge = 'bg-warning';
                                        $statusText = 'Pending';

                                        switch ($order['status']) {
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
                                    </td>
                                    <td>
                                        <?php
                                        $paymentStatus = isset($order['statuspembayaran']) ? $order['statuspembayaran'] : 'pending';
                                        $paymentBadge = 'bg-warning';
                                        $paymentText = 'Menunggu Pembayaran';

                                        // First check if full payment is confirmed
                                        if (isset($order['full_confirmed']) && $order['full_confirmed'] == 1) {
                                            $paymentBadge = 'bg-success';
                                            $paymentText = 'Lunas';
                                        }
                                        // Then check if full payment is pending confirmation
                                        else if ((isset($order['tipepembayaran']) && $order['tipepembayaran'] == 'lunas') ||
                                            (isset($order['full_paid']) && $order['full_paid'] == 1)
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
                                                    $paymentBadge = 'bg-info';
                                                    $paymentText = 'DP Dibayar';
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
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/pemesananpaket/detail/' . $order['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bx bx-show"></i> Detail
                                        </a>
                                        <?php if ($order['status'] !== 'completed' && $order['status'] !== 'cancelled') : ?>
                                            <a href="<?= site_url('admin/pemesananpaket/edit/' . $order['kdpemesananpaket']) ?>" class="btn btn-sm btn-warning">
                                                <i class="bx bx-edit"></i> Edit
                                            </a>
                                            <a href="<?= site_url('admin/pemesananpaket/delete/' . $order['kdpemesananpaket']) ?>" class="btn btn-sm btn-danger btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus pemesanan ini?')">
                                                <i class="bx bx-trash"></i> Hapus
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Orders Tab -->
            <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-pending">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Pemesanan</th>
                                <th>Tanggal Acara</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($pemesanan as $order) :
                                if ($order['status'] !== 'pending') continue;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $order['kdpemesananpaket'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($order['tgl'])) ?></td>
                                    <td><?= $order['namapelanggan'] ?></td>
                                    <td><?= $order['namapaket'] ?></td>
                                    <td>Rp <?= number_format($order['grandtotal'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $paymentStatus = isset($order['statuspembayaran']) ? $order['statuspembayaran'] : 'pending';
                                        $paymentBadge = 'bg-warning';
                                        $paymentText = 'Menunggu Pembayaran';

                                        // First check if full payment is confirmed
                                        if (isset($order['full_confirmed']) && $order['full_confirmed'] == 1) {
                                            $paymentBadge = 'bg-success';
                                            $paymentText = 'Lunas';
                                        }
                                        // Then check if full payment is pending confirmation
                                        else if ((isset($order['tipepembayaran']) && $order['tipepembayaran'] == 'lunas') ||
                                            (isset($order['full_paid']) && $order['full_paid'] == 1)
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
                                                    $paymentBadge = 'bg-info';
                                                    $paymentText = 'DP Dibayar';
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
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/pemesananpaket/detail/' . $order['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bx bx-show"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Process Orders Tab -->
            <div class="tab-pane fade" id="process" role="tabpanel" aria-labelledby="process-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-process">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Pemesanan</th>
                                <th>Tanggal Acara</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($pemesanan as $order) :
                                if ($order['status'] !== 'process') continue;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $order['kdpemesananpaket'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($order['tgl'])) ?></td>
                                    <td><?= $order['namapelanggan'] ?></td>
                                    <td><?= $order['namapaket'] ?></td>
                                    <td>Rp <?= number_format($order['grandtotal'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $paymentStatus = isset($order['statuspembayaran']) ? $order['statuspembayaran'] : 'pending';
                                        $paymentBadge = 'bg-warning';
                                        $paymentText = 'Menunggu Pembayaran';

                                        switch ($paymentStatus) {
                                            case 'pending':
                                                $paymentBadge = 'bg-warning';
                                                $paymentText = 'Menunggu Pembayaran';
                                                break;
                                            case 'partial':
                                                $paymentBadge = 'bg-info';
                                                $paymentText = 'DP Dibayar';
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
                                        ?>
                                        <span class="badge <?= $paymentBadge ?>"><?= $paymentText ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/pemesananpaket/detail/' . $order['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bx bx-show"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Completed Orders Tab -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-completed">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Pemesanan</th>
                                <th>Tanggal Acara</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($pemesanan as $order) :
                                if ($order['status'] !== 'completed') continue;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $order['kdpemesananpaket'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($order['tgl'])) ?></td>
                                    <td><?= $order['namapelanggan'] ?></td>
                                    <td><?= $order['namapaket'] ?></td>
                                    <td>Rp <?= number_format($order['grandtotal'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $paymentStatus = isset($order['statuspembayaran']) ? $order['statuspembayaran'] : 'pending';
                                        $paymentBadge = 'bg-warning';
                                        $paymentText = 'Menunggu Pembayaran';

                                        switch ($paymentStatus) {
                                            case 'pending':
                                                $paymentBadge = 'bg-warning';
                                                $paymentText = 'Menunggu Pembayaran';
                                                break;
                                            case 'partial':
                                                $paymentBadge = 'bg-info';
                                                $paymentText = 'DP Dibayar';
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
                                        ?>
                                        <span class="badge <?= $paymentBadge ?>"><?= $paymentText ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/pemesananpaket/detail/' . $order['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bx bx-show"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cancelled Orders Tab -->
            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-cancelled">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Pemesanan</th>
                                <th>Tanggal Acara</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Total</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($pemesanan as $order) :
                                if ($order['status'] !== 'cancelled') continue;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $order['kdpemesananpaket'] ?></td>
                                    <td><?= date('d-m-Y', strtotime($order['tgl'])) ?></td>
                                    <td><?= $order['namapelanggan'] ?></td>
                                    <td><?= $order['namapaket'] ?></td>
                                    <td>Rp <?= number_format($order['grandtotal'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php
                                        $paymentStatus = isset($order['statuspembayaran']) ? $order['statuspembayaran'] : 'pending';
                                        $paymentBadge = 'bg-warning';
                                        $paymentText = 'Menunggu Pembayaran';

                                        switch ($paymentStatus) {
                                            case 'pending':
                                                $paymentBadge = 'bg-warning';
                                                $paymentText = 'Menunggu Pembayaran';
                                                break;
                                            case 'partial':
                                                $paymentBadge = 'bg-info';
                                                $paymentText = 'DP Dibayar';
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
                                        ?>
                                        <span class="badge <?= $paymentBadge ?>"><?= $paymentText ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/pemesananpaket/detail/' . $order['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bx bx-show"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Order Modal -->
<div class="modal fade" id="createOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pemesanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createOrderForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kdpelanggan" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="pelanggan_display" readonly placeholder="Pilih Pelanggan">
                                <input type="hidden" name="kdpelanggan" id="kdpelanggan" required>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#selectPelangganModal">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="kdpelanggan-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="kdpaket" class="form-label">Paket <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="paket_display" readonly placeholder="Pilih Paket">
                                <input type="hidden" name="kdpaket" id="kdpaket" required>
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#selectPaketModal">
                                    <i class="bx bx-search"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback" id="kdpaket-error"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tgl" class="form-label">Tanggal Acara <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tgl" name="tgl" required>
                            <div class="invalid-feedback" id="tgl-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="jumlahhari" class="form-label">Jumlah Hari <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlahhari" name="jumlahhari" min="1" value="1" required>
                            <div class="invalid-feedback" id="jumlahhari-error"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamatpesanan" class="form-label">Alamat Acara <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alamatpesanan" name="alamatpesanan" rows="3" required></textarea>
                        <div class="invalid-feedback" id="alamatpesanan-error"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="luaslokasi" class="form-label">Luas Lokasi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="luaslokasi" name="luaslokasi" required>
                            <div class="invalid-feedback" id="luaslokasi-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="metodepembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-select" id="metodepembayaran" name="metodepembayaran" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Tunai</option>
                            </select>
                            <div class="invalid-feedback" id="metodepembayaran-error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveOrder">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Select Pelanggan Modal -->
<div class="modal fade" id="selectPelangganModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-pelanggan">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($pelanggan as $p) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['namapelanggan'] ?></td>
                                    <td><?= $p['nohp'] ?></td>
                                    <td><?= $p['alamat'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-select-pelanggan"
                                            data-id="<?= $p['kdpelanggan'] ?>"
                                            data-nama="<?= $p['namapelanggan'] ?>">
                                            <i class="bx bx-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select Paket Modal -->
<div class="modal fade" id="selectPaketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="table-paket">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Paket</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($paket as $p) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p['namapaket'] ?></td>
                                    <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-select-paket"
                                            data-id="<?= $p['kdpaket'] ?>"
                                            data-nama="<?= $p['namapaket'] ?>"
                                            data-harga="<?= $p['harga'] ?>">
                                            <i class="bx bx-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#table-all').DataTable();
        $('#table-pending').DataTable();
        $('#table-process').DataTable();
        $('#table-completed').DataTable();
        $('#table-cancelled').DataTable();
    });
</script>

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
    });
</script>
<?= $this->endSection() ?>
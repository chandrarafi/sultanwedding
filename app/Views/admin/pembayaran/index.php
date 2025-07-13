<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pembayaran</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Pembayaran</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Pembayaran Menunggu Konfirmasi</h5>
        </div>
        <hr>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-success"><i class="bx bx-check-circle"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-success">Berhasil</h6>
                        <div><?= session()->getFlashdata('success') ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-danger"><i class="bx bx-x-circle"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-danger">Error</h6>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dp-tab" data-bs-toggle="tab" data-bs-target="#dp" type="button" role="tab" aria-controls="dp" aria-selected="true">
                    DP Booking
                    <?php if (!empty($pembayaran_pending)) : ?>
                        <span class="badge rounded-pill bg-danger"><?= count($pembayaran_pending) ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="h1-tab" data-bs-toggle="tab" data-bs-target="#h1" type="button" role="tab" aria-controls="h1" aria-selected="false">
                    Pembayaran H-1
                    <?php if (!empty($pembayaran_h1_pending)) : ?>
                        <span class="badge rounded-pill bg-danger"><?= count($pembayaran_h1_pending) ?></span>
                    <?php endif; ?>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pelunasan-tab" data-bs-toggle="tab" data-bs-target="#pelunasan" type="button" role="tab" aria-controls="pelunasan" aria-selected="false">
                    Pelunasan
                    <?php if (!empty($pembayaran_full_pending)) : ?>
                        <span class="badge rounded-pill bg-danger"><?= count($pembayaran_full_pending) ?></span>
                    <?php endif; ?>
                </button>
            </li>
        </ul>

        <div class="tab-content py-3" id="myTabContent">
            <!-- Tab DP Booking -->
            <div class="tab-pane fade show active" id="dp" role="tabpanel" aria-labelledby="dp-tab">
                <?php if (empty($pembayaran_pending)) : ?>
                    <div class="alert alert-info">
                        Tidak ada pembayaran DP yang menunggu konfirmasi.
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-dp">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Tanggal Acara</th>
                                    <th>Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Jumlah DP</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($pembayaran_pending as $dp) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $dp['kdpemesananpaket'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($dp['tglacara'])) ?></td>
                                        <td><?= $dp['namapelanggan'] ?></td>
                                        <td><?= $dp['namapaket'] ?></td>
                                        <td>Rp <?= number_format($dp['jumlahbayar'], 0, ',', '.') ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($dp['created_at'])) ?></td>
                                        <td>
                                            <a href="<?= site_url('admin/pembayaran/detail/' . $dp['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                                <i class="bx bx-show"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab H-1 -->
            <div class="tab-pane fade" id="h1" role="tabpanel" aria-labelledby="h1-tab">
                <?php if (empty($pembayaran_h1_pending)) : ?>
                    <div class="alert alert-info">
                        Tidak ada pembayaran H-1 yang menunggu konfirmasi.
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-h1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Tanggal Acara</th>
                                    <th>Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($pembayaran_h1_pending as $h1) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $h1['kdpemesananpaket'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($h1['tglacara'])) ?></td>
                                        <td><?= $h1['namapelanggan'] ?></td>
                                        <td><?= $h1['namapaket'] ?></td>
                                        <td>Rp <?= number_format($h1['jumlahbayar'], 0, ',', '.') ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($h1['updated_at'])) ?></td>
                                        <td>
                                            <a href="<?= site_url('admin/pembayaran/detail/' . $h1['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                                <i class="bx bx-show"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tab Pelunasan -->
            <div class="tab-pane fade" id="pelunasan" role="tabpanel" aria-labelledby="pelunasan-tab">
                <?php if (empty($pembayaran_full_pending)) : ?>
                    <div class="alert alert-info">
                        Tidak ada pelunasan yang menunggu konfirmasi.
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-pelunasan">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Tanggal Acara</th>
                                    <th>Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($pembayaran_full_pending as $full) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $full['kdpemesananpaket'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($full['tglacara'])) ?></td>
                                        <td><?= $full['namapelanggan'] ?></td>
                                        <td><?= $full['namapaket'] ?></td>
                                        <td>Rp <?= number_format($full['jumlahbayar'], 0, ',', '.') ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($full['updated_at'])) ?></td>
                                        <td>
                                            <a href="<?= site_url('admin/pembayaran/detail/' . $full['kdpemesananpaket']) ?>" class="btn btn-sm btn-primary">
                                                <i class="bx bx-show"></i> Detail
                                            </a>
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#table-dp, #table-h1, #table-pelunasan').DataTable({
            responsive: true
        });
    });
</script>
<?= $this->endSection() ?>
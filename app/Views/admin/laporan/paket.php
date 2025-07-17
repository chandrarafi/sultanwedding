<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Paket</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Laporan Paket</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/laporan/cetakPaket') ?>" class="btn btn-primary" target="_blank">
                    <i class="bx bx-printer"></i> Cetak Laporan
                </a>
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="<?= site_url('admin/laporan/paket') ?>" method="get">
                    <div class="input-group">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($kategori as $kat) : ?>
                                <option value="<?= $kat['kdkategori'] ?>" <?= $selected_kategori == $kat['kdkategori'] ? 'selected' : '' ?>>
                                    <?= $kat['namakategori'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-outline-primary" type="submit">Filter</button>
                        <?php if (!empty($selected_kategori)) : ?>
                            <a href="<?= site_url('admin/laporan/paket') ?>" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <?php if (!empty($selected_kategori)) : ?>
                    <a href="<?= site_url('admin/laporan/cetakPaket?kategori=' . $selected_kategori) ?>" class="btn btn-success" target="_blank">
                        <i class="bx bx-printer"></i> Cetak Berdasarkan Filter
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="paketTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="40%">Nama Paket</th>
                        <th width="25%">Kategori</th>
                        <th width="20%">Harga</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($pakets as $paket) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $paket['namapaket'] ?></td>
                            <td><?= $paket['namakategori'] ?></td>
                            <td>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal<?= $paket['kdpaket'] ?>">
                                    <i class="bx bx-info-circle"></i> Detail
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail untuk setiap paket -->
<?php foreach ($pakets as $paket) : ?>
    <div class="modal fade" id="detailModal<?= $paket['kdpaket'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Paket: <?= $paket['namapaket'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <?php if (!empty($paket['foto']) && file_exists(FCPATH . 'uploads/paket/' . $paket['foto'])) : ?>
                                <img src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" class="img-fluid rounded" alt="<?= $paket['namapaket'] ?>">
                            <?php else : ?>
                                <img src="<?= base_url('assets/images/products/01.png') ?>" class="img-fluid rounded" alt="Default Image">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <table class="table">
                                <tr>
                                    <th width="30%">Nama Paket</th>
                                    <td><?= $paket['namapaket'] ?></td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td><?= $paket['namakategori'] ?></td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></td>
                                </tr>
                                <?php if (!empty($paket['detailpaket'])) : ?>
                                    <tr>
                                        <th>Detail Paket</th>
                                        <td><?= $paket['detailpaket'] ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    $(document).ready(function() {
        $('#paketTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>
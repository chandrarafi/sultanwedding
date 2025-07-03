<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Paket</h1>
        <div>
            <a href="<?= base_url('admin/paket/edit/' . $paket['kdpaket']) ?>" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="<?= base_url('admin/paket') ?>" class="btn btn-sm btn-secondary shadow-sm ml-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Foto Paket</h6>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($paket['foto'])) : ?>
                        <img src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" class="img-fluid img-thumbnail" alt="<?= $paket['namapaket'] ?>">
                    <?php else : ?>
                        <div class="alert alert-info">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p>Tidak ada foto untuk paket ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Paket</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama Paket</th>
                            <td width="5%">:</td>
                            <td><?= $paket['namapaket'] ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>:</td>
                            <td><?= $paket['namakategori'] ?></td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>:</td>
                            <td>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <th>Barang Terkait</th>
                            <td>:</td>
                            <td><?= $paket['namabarang'] ?? 'Tidak ada' ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Dibuat</th>
                            <td>:</td>
                            <td><?= date('d F Y H:i', strtotime($paket['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Terakhir Diupdate</th>
                            <td>:</td>
                            <td><?= date('d F Y H:i', strtotime($paket['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Paket</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($paket['detailpaket'])) : ?>
                        <p><?= nl2br($paket['detailpaket']) ?></p>
                    <?php else : ?>
                        <p class="text-muted">Tidak ada detail untuk paket ini</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
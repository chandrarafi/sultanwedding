<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/paket') ?>">Paket</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <div class="d-flex gap-3">
            <a href="<?= base_url('admin/paket/edit/' . $paket['kdpaket']) ?>" class="btn btn-warning px-3 radius-30">
                <i class="bx bx-edit"></i>Edit
            </a>
            <a href="<?= base_url('admin/paket') ?>" class="btn btn-light px-3 radius-30">
                <i class="bx bx-arrow-back"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="mb-0">Foto Paket</h5>
                </div>
                <div class="text-center">
                    <?php if (!empty($paket['foto'])) : ?>
                        <img src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" class="img-fluid rounded" alt="<?= $paket['namapaket'] ?>">
                    <?php else : ?>
                        <div class="border border-3 border-light py-5 rounded">
                            <div class="font-45 text-light-3"><i class="bx bx-image-alt"></i></div>
                            <h5 class="mb-0 text-light-3">Tidak ada foto</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="mb-0">Informasi Paket</h5>
                </div>
                <div class="list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                        <span class="fw-bold">Nama Paket</span>
                        <span class="text-secondary"><?= $paket['namapaket'] ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                        <span class="fw-bold">Kategori</span>
                        <span class="text-secondary"><?= $paket['namakategori'] ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                        <span class="fw-bold">Harga</span>
                        <span class="text-secondary">Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                        <span class="fw-bold">Tanggal Dibuat</span>
                        <span class="text-secondary"><?= date('d F Y H:i', strtotime($paket['created_at'])) ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap py-3">
                        <span class="fw-bold">Terakhir Diupdate</span>
                        <span class="text-secondary"><?= date('d F Y H:i', strtotime($paket['updated_at'])) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="mb-0">Detail Paket</h5>
                </div>
                <div class="p-3">
                    <?php if (!empty($paket['detailpaket'])) : ?>
                        <p><?= nl2br($paket['detailpaket']) ?></p>
                    <?php else : ?>
                        <p class="text-muted fst-italic">Tidak ada detail untuk paket ini</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="mb-0">Daftar Barang dalam Paket</h5>
                </div>
                <?php if (isset($paket['items']) && count($paket['items']) > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paket['items'] as $index => $item) : ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $item['namabarang'] ?> (<?= $item['satuan'] ?>)</td>
                                        <td><?= $item['jumlah'] ?></td>
                                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                        <td><?= $item['keterangan'] ?: '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="3" class="text-end">Total Harga Barang</th>
                                    <th colspan="2">
                                        <?php
                                        $totalHarga = array_reduce($paket['items'], function ($total, $item) {
                                            return $total + ($item['harga'] * $item['jumlah']);
                                        }, 0);
                                        echo 'Rp ' . number_format($totalHarga, 0, ',', '.');
                                        ?>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="text-muted fst-italic">Tidak ada barang yang ditambahkan ke paket ini</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananbarang') ?>">Pemesanan Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pemesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Detail Pemesanan Barang</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/pemesananbarang') ?>" class="btn btn-secondary px-3 radius-30">
                    <i class="bx bx-arrow-back"></i>Kembali
                </a>
                <a href="<?= site_url('admin/pemesananbarang/edit/' . $pemesanan['kdpemesananbarang']) ?>" class="btn btn-warning px-3 radius-30">
                    <i class="bx bx-edit"></i>Edit
                </a>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">ID Pemesanan</th>
                        <td width="60%">: <?= $pemesanan['kdpemesananbarang'] ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pemesanan</th>
                        <td>: <?= date('d F Y', strtotime($pemesanan['tgl'])) ?></td>
                    </tr>
                    <tr>
                        <th>Pelanggan</th>
                        <td>: <?= $pemesanan['namapelanggan'] ?? '<span class="badge bg-warning text-dark">Walk-in</span>' ?></td>
                    </tr>
                    <tr>
                        <th>Alamat Pengiriman</th>
                        <td>: <?= $pemesanan['alamatpesanan'] ?: '-' ?></td>
                    </tr>
                    <tr>
                        <th>Lama Pemesanan</th>
                        <td>: <?= $pemesanan['lamapemesanan'] ?> hari</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="40%">Status Pemesanan</th>
                        <td width="60%">:
                            <?php if ($pemesanan['status'] == 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php elseif ($pemesanan['status'] == 'process'): ?>
                                <span class="badge bg-info">Proses</span>
                            <?php elseif ($pemesanan['status'] == 'completed'): ?>
                                <span class="badge bg-success">Selesai</span>
                            <?php elseif ($pemesanan['status'] == 'cancelled'): ?>
                                <span class="badge bg-danger">Dibatalkan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td>:
                            <?php if (isset($pemesanan['statuspembayaran'])): ?>
                                <?php if ($pemesanan['statuspembayaran'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Belum Bayar</span>
                                <?php elseif ($pemesanan['statuspembayaran'] == 'partial'): ?>
                                    <span class="badge bg-info">DP</span>
                                <?php elseif ($pemesanan['statuspembayaran'] == 'confirmed'): ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum Ada Pembayaran</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>: <?= isset($pemesanan['metodepembayaran']) ? ucfirst($pemesanan['metodepembayaran']) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Total Pembayaran</th>
                        <td>: Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Sisa Pembayaran</th>
                        <td>: Rp <?= isset($pemesanan['sisa']) ? number_format($pemesanan['sisa'], 0, ',', '.') : number_format($pemesanan['grandtotal'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <h5>Detail Barang</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="40%">Nama Barang</th>
                            <th width="15%">Harga</th>
                            <th width="15%">Jumlah</th>
                            <th width="25%">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($detail as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $item['namabarang'] ?></td>
                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th>Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <?php if (isset($pemesanan['statuspembayaran']) && $pemesanan['statuspembayaran'] != 'confirmed'): ?>
            <div class="mt-4">
                <h5>Proses Pembayaran</h5>
                <div class="row">
                    <?php if (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] == 0): ?>
                        <!-- Belum ada pembayaran atau DP belum dikonfirmasi -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title">Pembayaran DP (50%)</h6>
                                    <p>Total DP: Rp <?= number_format($pemesanan['grandtotal'] * 0.5, 0, ',', '.') ?></p>
                                    <form action="<?= site_url('admin/pemesananbarang/bayar-h1/' . $pemesanan['kdpemesananbarang']) ?>" method="post">
                                        <div class="mb-3">
                                            <label for="metodepembayaran" class="form-label">Metode Pembayaran</label>
                                            <select class="form-select" id="metodepembayaran" name="metodepembayaran" required>
                                                <option value="tunai">Tunai</option>
                                                <option value="transfer">Transfer</option>
                                                <option value="qris">QRIS</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
                                            <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" value="<?= $pemesanan['grandtotal'] * 0.5 ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Proses Pembayaran DP</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php elseif (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 0): ?>
                        <!-- DP sudah dikonfirmasi, H-1 belum -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title">Pembayaran H-1 (25%)</h6>
                                    <p>Total H-1: Rp <?= number_format($pemesanan['grandtotal'] * 0.25, 0, ',', '.') ?></p>
                                    <form action="<?= site_url('admin/pemesananbarang/bayar-h1/' . $pemesanan['kdpemesananbarang']) ?>" method="post">
                                        <div class="mb-3">
                                            <label for="metodepembayaran" class="form-label">Metode Pembayaran</label>
                                            <select class="form-select" id="metodepembayaran" name="metodepembayaran" required>
                                                <option value="tunai">Tunai</option>
                                                <option value="transfer">Transfer</option>
                                                <option value="qris">QRIS</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
                                            <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" value="<?= $pemesanan['grandtotal'] * 0.25 ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Proses Pembayaran H-1</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- H-1 sudah dikonfirmasi, pelunasan belum -->
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="card-title">Pelunasan (25%)</h6>
                                    <p>Total Pelunasan: Rp <?= number_format($pemesanan['sisa'], 0, ',', '.') ?></p>
                                    <form action="<?= site_url('admin/pemesananbarang/bayar-pelunasan/' . $pemesanan['kdpemesananbarang']) ?>" method="post">
                                        <div class="mb-3">
                                            <label for="metodepembayaran" class="form-label">Metode Pembayaran</label>
                                            <select class="form-select" id="metodepembayaran" name="metodepembayaran" required>
                                                <option value="tunai">Tunai</option>
                                                <option value="transfer">Transfer</option>
                                                <option value="qris">QRIS</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
                                            <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar" value="<?= $pemesanan['sisa'] ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Proses Pelunasan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan Paket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananpaket') ?>">Daftar Pemesanan Paket</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Pemesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Edit Pemesanan</h5>
        </div>
        <hr>

        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif; ?>

        <?php if (session()->has('errors')) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('admin/pemesananpaket/update/' . $pemesanan['kdpemesananpaket']) ?>" method="post">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kode Pemesanan</label>
                    <input type="text" class="form-control" value="<?= $pemesanan['kdpemesananpaket'] ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
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
                    <div>
                        <span class="badge <?= $statusBadge ?>"><?= $statusText ?></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Pelanggan</label>
                    <input type="text" class="form-control" value="<?= $pemesanan['namapelanggan'] ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Paket</label>
                    <input type="text" class="form-control" value="<?= $pemesanan['namapaket'] ?> - Rp <?= number_format($pemesanan['hargapaket'], 0, ',', '.') ?>" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tgl" class="form-label">Tanggal Acara <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tgl" name="tgl" value="<?= old('tgl', $pemesanan['tgl']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="jumlahhari" class="form-label">Jumlah Hari <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="jumlahhari" name="jumlahhari" min="1" value="<?= old('jumlahhari', $pemesanan['jumlahhari']) ?>" required>
                    <small class="text-info">Jika jumlah hari melebihi 4 hari, akan dikenakan biaya tambahan 10% dari harga paket.</small>
                </div>
            </div>

            <div class="mb-3">
                <label for="alamatpesanan" class="form-label">Alamat Acara <span class="text-danger">*</span></label>
                <textarea class="form-control" id="alamatpesanan" name="alamatpesanan" rows="3" required><?= old('alamatpesanan', $pemesanan['alamatpesanan']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="luaslokasi" class="form-label">Luas Lokasi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="luaslokasi" name="luaslokasi" value="<?= old('luaslokasi', $pemesanan['luaslokasi']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Metode Pembayaran</label>
                <input type="text" class="form-control" value="<?= ucfirst($pemesanan['metodepembayaran']) ?>" readonly>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= site_url('admin/pemesananpaket') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
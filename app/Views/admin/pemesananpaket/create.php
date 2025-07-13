<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan Paket</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananpaket') ?>">Daftar Pemesanan Paket</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Pemesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Tambah Pemesanan Baru</h5>
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

        <form action="<?= site_url('admin/pemesananpaket/store') ?>" method="post">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kdpelanggan" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="pelanggan_display" readonly placeholder="Pilih Pelanggan" value="<?= old('pelanggan_display', session('pelanggan_display')) ?>">
                        <input type="hidden" name="kdpelanggan" id="kdpelanggan" value="<?= old('kdpelanggan') ?>" required>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#selectPelangganModal">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                    <?php if (isset(session('errors')['kdpelanggan'])) : ?>
                        <div class="text-danger"><?= session('errors')['kdpelanggan'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="kdpaket" class="form-label">Paket <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="paket_display" readonly placeholder="Pilih Paket" value="<?= old('paket_display', session('paket_display')) ?>">
                        <input type="hidden" name="kdpaket" id="kdpaket" value="<?= old('kdpaket') ?>" required>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#selectPaketModal">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                    <?php if (isset(session('errors')['kdpaket'])) : ?>
                        <div class="text-danger"><?= session('errors')['kdpaket'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tgl" class="form-label">Tanggal Acara <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tgl" name="tgl" value="<?= old('tgl') ?>" required>
                    <?php if (isset(session('errors')['tgl'])) : ?>
                        <div class="text-danger"><?= session('errors')['tgl'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="jumlahhari" class="form-label">Jumlah Hari <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="jumlahhari" name="jumlahhari" min="1" value="<?= old('jumlahhari', 1) ?>" required>
                    <?php if (isset(session('errors')['jumlahhari'])) : ?>
                        <div class="text-danger"><?= session('errors')['jumlahhari'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="alamatpesanan" class="form-label">Alamat Acara <span class="text-danger">*</span></label>
                <textarea class="form-control" id="alamatpesanan" name="alamatpesanan" rows="3" required><?= old('alamatpesanan') ?></textarea>
                <?php if (isset(session('errors')['alamatpesanan'])) : ?>
                    <div class="text-danger"><?= session('errors')['alamatpesanan'] ?></div>
                <?php endif; ?>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="luaslokasi" class="form-label">Luas Lokasi <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="luaslokasi" name="luaslokasi" value="<?= old('luaslokasi') ?>" required>
                    <?php if (isset(session('errors')['luaslokasi'])) : ?>
                        <div class="text-danger"><?= session('errors')['luaslokasi'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <label for="metodepembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select class="form-select" id="metodepembayaran" name="metodepembayaran" required>
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="transfer" <?= old('metodepembayaran') == 'transfer' ? 'selected' : '' ?>>Transfer Bank</option>
                        <option value="cash" <?= old('metodepembayaran') == 'cash' ? 'selected' : '' ?>>Tunai</option>
                    </select>
                    <?php if (isset(session('errors')['metodepembayaran'])) : ?>
                        <div class="text-danger"><?= session('errors')['metodepembayaran'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <div class="alert alert-info">
                    <strong>Informasi:</strong> Untuk pembayaran tunai (cash), DP akan otomatis dikonfirmasi oleh sistem.
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= site_url('admin/pemesananpaket') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
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
        $('#table-pelanggan').DataTable();
        $('#table-paket').DataTable();

        // Handle pelanggan selection
        $('.btn-select-pelanggan').click(function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#kdpelanggan').val(id);
            $('#pelanggan_display').val(nama);

            $('#selectPelangganModal').modal('hide');
        });

        // Handle paket selection
        $('.btn-select-paket').click(function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');

            $('#kdpaket').val(id);
            $('#paket_display').val(nama + ' - Rp ' + new Intl.NumberFormat('id-ID').format(harga));

            $('#selectPaketModal').modal('hide');
        });
    });
</script>
<?= $this->endSection() ?>
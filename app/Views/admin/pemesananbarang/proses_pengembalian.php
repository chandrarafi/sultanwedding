<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datetimepicker/css/classic.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datetimepicker/css/classic.date.css') ?>" />
<style>
    .detail-table th {
        width: 30%;
    }

    .table-bordered td,
    .table-bordered th {
        border: 1px solid #e9ecef;
    }

    .picker {
        font-size: 1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananbarang') ?>">Pemesanan Barang</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananbarang/pengembalian') ?>">Pengembalian</a></li>
                <li class="breadcrumb-item active" aria-current="page">Proses Pengembalian</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Proses Pengembalian Barang</h5>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Informasi Pemesanan</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless detail-table">
                            <tr>
                                <th>Kode Pemesanan</th>
                                <td><?= $pemesanan['kdpemesananbarang'] ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Pemesanan</th>
                                <td><?= date('d/m/Y', strtotime($pemesanan['tgl'])) ?></td>
                            </tr>
                            <?php if (!empty($pemesanan['tgl_kembali'])): ?>
                                <tr>
                                    <th>Tanggal Pengembalian</th>
                                    <td><?= date('d/m/Y', strtotime($pemesanan['tgl_kembali'])) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Pelanggan</th>
                                <td><?= $pemesanan['namapelanggan'] ?? 'Pelanggan Walk-in' ?></td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    <?php if ($pemesanan['statuspembayaran'] == 'success' || $pemesanan['statuspembayaran'] == 'confirmed'): ?>
                                        <span class="badge bg-success">Lunas</span>
                                    <?php elseif ($pemesanan['statuspembayaran'] == 'partial'): ?>
                                        <span class="badge bg-warning">DP</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Belum Bayar</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Total Pembayaran</th>
                                <td>Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Form Pengembalian</h6>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/pemesananbarang/simpanPengembalian/' . $pemesanan['kdpemesananbarang']) ?>" method="post">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label for="tgl_kembali" class="form-label">Tanggal Pengembalian</label>
                                <input type="date" class="form-control" id="tgl_kembali" name="tgl_kembali" value="<?= !empty($pemesanan['tgl_kembali']) ? date('d/m/Y', strtotime($pemesanan['tgl_kembali'])) : date('d/m/Y') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="catatan_pengembalian" class="form-label">Catatan Pengembalian</label>
                                <textarea class="form-control" id="catatan_pengembalian" name="catatan_pengembalian" rows="3"></textarea>
                            </div>

                            <?php if ($pemesanan['statuspembayaran'] == 'partial'): ?>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="pelunasan" name="pelunasan">
                                        <label class="form-check-label" for="pelunasan">
                                            Proses pelunasan saat pengembalian
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3 payment-method-section d-none">
                                    <label for="metodepembayaran" class="form-label">Metode Pembayaran</label>
                                    <select class="form-select" id="metodepembayaran" name="metodepembayaran">
                                        <option value="tunai">Tunai</option>
                                        <option value="transfer">Transfer</option>
                                        <option value="qris">QRIS</option>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Pengembalian</button>
                                <a href="<?= site_url('admin/pemesananbarang/pengembalian') ?>" class="btn btn-light">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">Detail Barang yang Dikembalikan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="table-light">
                                <th width="5%">No</th>
                                <th width="15%">Kode Barang</th>
                                <th width="30%">Nama Barang</th>
                                <th width="10%">Jumlah</th>
                                <th width="15%">Harga Sewa</th>
                                <th width="15%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($detailPemesanan as $detail): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $detail['kdbarang'] ?></td>
                                    <td><?= $detail['namabarang'] ?></td>
                                    <td class="text-center"><?= $detail['jumlah'] ?></td>
                                    <td class="text-end">Rp <?= number_format($detail['harga'], 0, ',', '.') ?></td>
                                    <td class="text-end">Rp <?= number_format($detail['subtotal'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
                                <th class="text-end">Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/plugins/datetimepicker/js/picker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datetimepicker/js/picker.date.js') ?>"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi datepicker
        $('.datepicker').pickadate({
            format: 'dd/mm/yyyy',
            formatSubmit: 'yyyy-mm-dd',
            hiddenName: true,
            today: 'Hari Ini',
            clear: 'Hapus',
            close: 'Tutup'
        });

        // Toggle metode pembayaran saat checkbox pelunasan diubah
        $('#pelunasan').change(function() {
            if ($(this).is(':checked')) {
                $('.payment-method-section').removeClass('d-none');
                $('#metodepembayaran').prop('required', true);
            } else {
                $('.payment-method-section').addClass('d-none');
                $('#metodepembayaran').prop('required', false);
            }
        });
    });
</script>
<?= $this->endSection() ?>
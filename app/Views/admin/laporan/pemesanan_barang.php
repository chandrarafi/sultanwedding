<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    @media (max-width: 767.98px) {
        .table-responsive-md {
            overflow-x: auto;
        }

        #dataTable th,
        #dataTable td {
            white-space: nowrap;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Pemesanan Barang</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Laporan Pemesanan Barang</h5>
            <div class="ms-auto">
                <div class="btn-group">
                    <button class="btn btn-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Jenis Laporan <i class="fadeIn animated bx bx-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="<?= site_url('admin/laporan/pemesananBarang') ?>">Laporan Harian</a>
                        <a class="dropdown-item" href="<?= site_url('admin/laporan/pemesananBarangBulanan') ?>">Laporan Bulanan</a>
                        <a class="dropdown-item" href="<?= site_url('admin/laporan/pemesananBarangTahunan') ?>">Laporan Tahunan</a>
                    </div>
                </div>
            </div>
        </div>

        <form action="<?= site_url('admin/laporan/pemesananBarang') ?>" method="get" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="<?= $tanggal_awal ?>">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?= $tanggal_akhir ?>">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="" <?= ($status === '') ? 'selected' : '' ?>>Semua Status</option>
                        <option value="pending" <?= ($status === 'pending') ? 'selected' : '' ?>>Pending</option>
                        <option value="partial" <?= ($status === 'partial') ? 'selected' : '' ?>>Partial</option>
                        <option value="success" <?= ($status === 'success') ? 'selected' : '' ?>>Success</option>
                        <option value="completed" <?= ($status === 'completed') ? 'selected' : '' ?>>Completed</option>
                        <option value="returned" <?= ($status === 'returned') ? 'selected' : '' ?>>Returned</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-end mb-3">
            <a href="<?= site_url('admin/laporan/cetakLaporanBarang?' . http_build_query([
                            'tanggal_awal' => $tanggal_awal,
                            'tanggal_akhir' => $tanggal_akhir,
                            'status' => $status
                        ])) ?>" class="btn btn-success">
                <i class="bx bx-printer"></i> Cetak Laporan
            </a>
        </div>

        <div class="table-responsive-md">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead class="table">
                    <tr>
                        <th>No</th>
                        <th>Kode Pemesanan</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Lama Pemesanan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pemesanan)) : ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pemesanan</td>
                        </tr>
                    <?php else : ?>
                        <?php $no = 1;
                        $totalPendapatanCalculated = 0;
                        foreach ($pemesanan as $p) :
                            $totalPendapatanCalculated += $p['grandtotal'];
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $p['kdpemesananbarang'] ?></td>
                                <td><?= date('d/m/Y', strtotime($p['tgl'])) ?></td>
                                <td><?= $p['namapelanggan'] ?></td>
                                <td><?= $p['lamapemesanan'] ?> hari</td>
                                <td class="text-end">Rp <?= number_format($p['grandtotal'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($p['status'] === 'pending') : ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($p['status'] === 'confirmed') : ?>
                                        <span class="badge bg-info">Confirmed</span>
                                    <?php elseif ($p['status'] === 'completed') : ?>
                                        <span class="badge bg-success">Completed</span>
                                    <?php elseif ($p['status'] === 'cancelled') : ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary"><?= ucfirst($p['status']) ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <th colspan="5" class="text-end">Total Pendapatan:</th>
                        <th colspan="2" class="text-start">Rp <?= number_format(isset($totalPendapatanCalculated) ? $totalPendapatanCalculated : 0, 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#dataTable').DataTable({
            responsive: true,
            language: {
                url: '<?= base_url('assets/plugins/datatable/i18n/id.json') ?>',
            },
            dom: '<"row"<"col-md-6"l><"col-md-6"f>>rtip',
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 2, 5, 6]
                },
                {
                    className: "text-end",
                    targets: [4]
                }
            ]
        });
    });
</script>
<?= $this->endSection() ?>
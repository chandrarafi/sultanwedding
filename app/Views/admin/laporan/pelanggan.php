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
                <li class="breadcrumb-item active" aria-current="page">Laporan Data Pelanggan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Laporan Data Pelanggan</h5>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="<?= site_url('admin/laporan/cetakLaporanPelanggan') ?>" class="btn btn-success" target="_blank">
                <i class="bx bx-printer"></i> Cetak Laporan
            </a>
        </div>

        <div class="table-responsive-md">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead class="table">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pelanggan)) : ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pelanggan</td>
                        </tr>
                    <?php else : ?>
                        <?php $no = 1;
                        foreach ($pelanggan as $p) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $p['namapelanggan'] ?></td>
                                <td><?= $p['alamat'] ?? '-' ?></td>
                                <td><?= $p['nohp'] ?></td>
                                <td><?= $p['username'] ?? '-' ?></td>
                                <td><?= $p['email'] ?? '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <th colspan="2" class="text-end">Total Pelanggan:</th>
                        <th colspan="4" class="text-start"><?= count($pelanggan) ?> orang</th>
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
                targets: [0, 3]
            }]
        });
    });
</script>
<?= $this->endSection() ?>
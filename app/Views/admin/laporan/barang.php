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
                <li class="breadcrumb-item active" aria-current="page">Laporan Data Barang</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Laporan Data Barang</h5>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="<?= site_url('admin/laporan/cetakLaporanDataBarang') ?>" class="btn btn-success" target="_blank">
                <i class="bx bx-printer"></i> Cetak Laporan
            </a>
        </div>

        <div class="table-responsive-md">
            <table id="dataTable" class="table table-striped table-bordered">
                <thead class="table">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Harga Sewa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($barang)) : ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data barang</td>
                        </tr>
                    <?php else : ?>
                        <?php
                        $no = 1;
                        $totalNilai = 0;
                        foreach ($barang as $b) :
                            $nilaiBarang = $b['jumlah'] * $b['hargasewa'];
                            $totalNilai += $nilaiBarang;
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $b['namabarang'] ?></td>
                                <td><?= $b['satuan'] ?></td>
                                <td class="text-center"><?= $b['jumlah'] ?></td>
                                <td class="text-end">Rp <?= number_format($b['hargasewa'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="table-primary">
                        <th colspan="3" class="text-end">Total Nilai Inventaris:</th>
                        <th colspan="2" class="text-start">Rp <?= number_format($totalNilai ?? 0, 0, ',', '.') ?></th>
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
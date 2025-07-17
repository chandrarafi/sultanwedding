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

        .dtr-modal {
            max-width: 95%;
            margin: 0 auto;
        }

        .dtr-modal .dtr-modal-display {
            width: 95%;
            max-width: 500px;
        }
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
                <li class="breadcrumb-item active" aria-current="page">Pengembalian Barang</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Data Pengembalian Barang</h5>
        </div>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive-md mt-3">
            <table class="table align-middle table-striped table-hover dt-responsive nowrap w-100" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%" data-priority="1">No</th>
                        <th width="15%" data-priority="2">Kode Pemesanan</th>
                        <th width="15%" data-priority="4">Tanggal Pemesanan</th>
                        <th width="20%" data-priority="3">Pelanggan</th>
                        <th width="15%" data-priority="5">Total</th>
                        <th width="15%" data-priority="6">Status</th>
                        <th width="15%" data-priority="1">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($pemesanan as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $item['kdpemesananbarang'] ?></td>
                            <td><?= date('d/m/Y', strtotime($item['tgl'])) ?></td>
                            <td><?= $item['namapelanggan'] ?? 'Pelanggan Walk-in' ?></td>
                            <td>Rp <?= number_format($item['grandtotal'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-success">Selesai</span>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/pemesananbarang/prosesPengembalian/' . $item['kdpemesananbarang']) ?>" class="btn btn-sm btn-primary">
                                    <i class="bx bx-check-circle"></i> Proses Pengembalian
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($pemesanan)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pemesanan yang siap dikembalikan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
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
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Detail Pengembalian: ' + data[1]; // Menggunakan kode pemesanan
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table table-striped'
                    })
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
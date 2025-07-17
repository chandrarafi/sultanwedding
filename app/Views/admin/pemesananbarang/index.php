<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Pemesanan Barang</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h5 class="mb-0">Data Pemesanan Barang</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/pemesananbarang/pengembalian') ?>" class="btn btn-success px-3 radius-30 me-2">
                    <i class="bx bx-check-circle"></i>Pengembalian Barang
                </a>
                <a href="<?= site_url('admin/pemesananbarang/create') ?>" class="btn btn-primary px-3 radius-30">
                    <i class="bx bx-plus"></i>Tambah Pemesanan
                </a>
            </div>
        </div>
        <div class="table-responsive-md">
            <table class="table align-middle table-striped table-hover w-100" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Pelanggan</th>
                        <th width="15%">Total</th>
                        <th width="5%">Status</th>
                        <th width="40%" class="action-column">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus pemesanan barang ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        const table = $('#dataTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= site_url('admin/pemesananbarang/getAll') ?>',
                dataSrc: function(json) {
                    return json.data || [];
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'kdpemesananbarang',
                },
                {
                    data: 'tgl',
                    render: function(data) {
                        return formatDate(data);
                    }
                },
                {
                    data: 'namapelanggan',
                    render: function(data, type, row) {
                        return data || '<span class="badge bg-warning text-dark">Walk-in</span>';
                    }
                },
                {
                    data: 'grandtotal',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        let badge = '';
                        switch (data) {
                            case 'pending':
                                badge = '<span class="badge bg-warning text-dark">Pending</span>';
                                break;
                            case 'process':
                                badge = '<span class="badge bg-info">Proses</span>';
                                break;
                            case 'completed':
                                badge = '<span class="badge bg-success">Selesai</span>';
                                break;
                            case 'cancelled':
                                badge = '<span class="badge bg-danger">Dibatalkan</span>';
                                break;
                            default:
                                badge = '<span class="badge bg-secondary">Unknown</span>';
                        }

                        // Tambahkan badge pembayaran jika ada
                        let paymentBadge = '';
                        if (row.statuspembayaran) {
                            switch (row.statuspembayaran) {
                                case 'pending':
                                    paymentBadge = '<span class="badge bg-warning text-dark ms-1">Belum Bayar</span>';
                                    break;
                                case 'partial':
                                    paymentBadge = '<span class="badge bg-info ms-1">DP</span>';
                                    break;
                                case 'confirmed':
                                    paymentBadge = '<span class="badge bg-success ms-1">Lunas</span>';
                                    break;
                                default:
                                    paymentBadge = '';
                            }
                        }

                        // Tambahkan badge status pengembalian jika ada
                        let returnBadge = '';
                        if (row.status_pengembalian) {
                            switch (row.status_pengembalian) {
                                case 'baik':
                                    returnBadge = '<span class="badge bg-success ms-1">Dikembalikan (Baik)</span>';
                                    break;
                                case 'rusak':
                                    returnBadge = '<span class="badge bg-warning text-dark ms-1">Dikembalikan (Rusak)</span>';
                                    break;
                                case 'hilang':
                                    returnBadge = '<span class="badge bg-danger ms-1">Barang Hilang</span>';
                                    break;
                                default:
                                    returnBadge = '';
                            }
                        }

                        return badge + ' ' + paymentBadge + ' ' + returnBadge;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="<?= site_url('admin/pemesananbarang/detail') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-info">
                                    <i class="bx bx-detail"></i> Detail
                                </a>
                                <a href="<?= site_url('admin/pemesananbarang/edit') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${row.kdpemesananbarang}">
                                    <i class="bx bx-trash"></i> Hapus
                                </button>
                                <a href="<?= site_url('admin/pemesananbarang/cetakFaktur') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="bx bx-printer"></i> Cetak
                                </a>
                                <a href="<?= site_url('admin/pemesananbarang/lihatFaktur') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-secondary" target="_blank">
                                    <i class="bx bx-file"></i> Lihat
                                </a>
                                ${row.status === 'process' ? `
                                <a href="<?= site_url('admin/pemesananbarang/completeStatus') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin mengubah status menjadi selesai?')">
                                    <i class="bx bx-check-circle"></i> Selesaikan
                                </a>
                                ` : ''}
                                ${row.status === 'completed' && !row.status_pengembalian ? `
                                <a href="<?= site_url('admin/pemesananbarang/prosesPengembalian') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-success">
                                    <i class="bx bx-refresh"></i> Proses Pengembalian
                                </a>
                                ` : ''}
                            </div>
                        `;
                    },
                    orderable: false
                }
            ],
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Detail Pemesanan: ' + data.kdpemesananbarang;
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                        tableClass: 'table table-striped'
                    })
                }
            },
            language: {
                url: '<?= base_url('assets/plugins/datatable/i18n/id.json') ?>',
            },
            columnDefs: [{
                    className: "text-center",
                    targets: [0, 1, 5]
                },
                {
                    className: "text-end",
                    targets: [4]
                },
                {
                    className: "action-column",
                    targets: [6]
                },
                {
                    responsivePriority: 1,
                    targets: [0, 1]
                }, // No dan Kode selalu terlihat
                {
                    responsivePriority: 2,
                    targets: [6]
                }, // Aksi prioritas kedua
                {
                    responsivePriority: 3,
                    targets: [4]
                }, // Total prioritas ketiga
                {
                    responsivePriority: 10000,
                    targets: [2, 3, 5]
                } // Kolom lain bisa disembunyikan
            ]
        });

        // Event untuk tombol hapus
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            $('#deleteModal').modal('show');

            $('#confirmDelete').off('click').on('click', function() {
                // CSRF token untuk keamanan
                const csrfName = '<?= csrf_token() ?>';
                const csrfHash = '<?= csrf_hash() ?>';

                $.ajax({
                    url: '<?= site_url('admin/pemesananbarang/delete') ?>/' + id,
                    type: 'POST',
                    data: {
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            table.ajax.reload();
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                        $('#deleteModal').modal('hide');
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus data',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        $('#deleteModal').modal('hide');
                    }
                });
            });
        });

        // Format tanggal
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        // Format rupiah
        function formatRupiah(angka) {
            if (!angka) return 'Rp 0';
            return 'Rp ' + parseFloat(angka).toLocaleString('id-ID');
        }
    });
</script>
<?= $this->endSection() ?>
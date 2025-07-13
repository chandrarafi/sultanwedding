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
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Data Pemesanan Barang</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/pemesananbarang/create') ?>" class="btn btn-primary px-3 radius-30">
                    <i class="bx bx-plus"></i>Tambah Pemesanan (Walk-in)
                </a>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table align-middle table-striped table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="20%">Pelanggan</th>
                        <th width="15%">Total</th>
                        <th width="15%">Status</th>
                        <th width="15%">Aksi</th>
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

                        return badge + ' ' + paymentBadge;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex gap-2">
                                <a href="<?= site_url('admin/pemesananbarang/detail') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-info"><i class="bx bx-detail"></i></a>
                                <a href="<?= site_url('admin/pemesananbarang/edit') ?>/${row.kdpemesananbarang}" class="btn btn-sm btn-warning"><i class="bx bx-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${row.kdpemesananbarang}"><i class="bx bx-trash"></i></button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
            },
        });

        // Handle tombol hapus
        let deleteId = null;
        $('#dataTable').on('click', '.btn-delete', function() {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Handle konfirmasi hapus
        $('#confirmDelete').on('click', function() {
            if (deleteId) {
                $.ajax({
                    url: `<?= site_url('admin/pemesananbarang/delete') ?>/${deleteId}`,
                    type: 'DELETE',
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
            }
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
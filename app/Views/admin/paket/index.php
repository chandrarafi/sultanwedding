<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Paket</li>
            </ol>
        </nav>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-success"><i class="bx bx-check-circle"></i></div>
            <div class="ms-3">
                <h6 class="mb-0 text-success">Berhasil</h6>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-danger"><i class="bx bx-x-circle"></i></div>
            <div class="ms-3">
                <h6 class="mb-0 text-danger">Error</h6>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Data Paket</h5>
            <div class="ms-auto">
                <a href="<?= base_url('admin/paket/create') ?>" class="btn btn-primary px-3 radius-30">
                    <i class="bx bx-plus"></i>Tambah Paket
                </a>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table align-middle table-striped table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Paket</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Harga</th>
                        <th width="10%">Foto</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            "processing": true,
            "language": {
                "processing": '<i class="bx bx-loader bx-spin font-medium-5"></i><span class="ms-1">Loading...</span>'
            },
            "order": [],
            "columnDefs": [{
                "targets": [0, 4, 5], // No, Foto, dan Aksi tidak diurutkan
                "orderable": false,
            }],
        });

        // Load data
        loadPaket();

        // Handle click on delete button (delegated event)
        $('#tableBody').on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            // Confirm deletion
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus paket "${nama}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    $.ajax({
                        url: '<?= base_url('admin/paket/delete') ?>/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                // Reload data
                                loadPaket();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
                        }
                    });
                }
            });
        });

        // Function to load paket data
        function loadPaket() {
            $.ajax({
                url: '<?= base_url('admin/paket/getAll') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // Clear table
                        table.clear();

                        // Add data to table
                        $.each(response.data, function(i, item) {
                            // Format harga
                            let harga = formatRupiah(item.harga);

                            // Format foto
                            let foto = item.foto ?
                                `<img src="<?= base_url('uploads/paket') ?>/${item.foto}" alt="${item.namapaket}" class="rounded" width="60">` :
                                '<span class="badge bg-light-secondary text-secondary">No image</span>';

                            // Format actions
                            let actions = `
                                <div class="d-flex align-items-center gap-3 fs-6">
                                    <a href="<?= base_url('admin/paket/detail') ?>/${item.kdpaket}" class="text-primary" title="Detail">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="<?= base_url('admin/paket/edit') ?>/${item.kdpaket}" class="text-warning" title="Edit">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="text-danger btn-delete" data-id="${item.kdpaket}" data-nama="${item.namapaket}" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>
                            `;

                            // Add row to table
                            table.row.add([
                                i + 1,
                                item.namapaket,
                                item.namakategori,
                                harga,
                                foto,
                                actions
                            ]).draw(false);
                        });
                    } else {
                        Swal.fire('Error', 'Gagal memuat data paket', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
                }
            });
        }

        // Function to format currency
        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }
    });
</script>
<?= $this->endSection() ?>
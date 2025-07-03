<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Paket</h1>
        <a href="<?= base_url('admin/paket/create') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Paket
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Paket</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
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
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#dataTable').DataTable({
            "processing": true,
            "language": {
                "processing": '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
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
                title: 'Apakah Anda yakin?',
                text: `Akan menghapus paket "${nama}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
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
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                // Reload data
                                loadPaket();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message,
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server',
                            });
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
                                `<img src="<?= base_url('uploads/paket') ?>/${item.foto}" alt="${item.namapaket}" class="img-thumbnail" width="80">` :
                                '<span class="badge bg-secondary">Tidak ada foto</span>';

                            // Format actions
                            let actions = `
                                <a href="<?= base_url('admin/paket/detail') ?>/${item.kdpaket}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="<?= base_url('admin/paket/edit') ?>/${item.kdpaket}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${item.kdpaket}" data-nama="${item.namapaket}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            `;

                            // Add row to table
                            table.row.add([
                                i + 1,
                                item.namapaket,
                                item.namakategori,
                                harga,
                                foto,
                                actions
                            ]);
                        });

                        // Draw table
                        table.draw();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data paket',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan pada server',
                    });
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
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
    <div class="ms-auto">
        <a href="<?= base_url('admin/paket/create') ?>" class="btn btn-primary px-3 radius-30">
            <i class="bx bx-plus"></i>Tambah Paket
        </a>
    </div>
</div>

<?php if (session()->has('success')) : ?>
    <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-success"><i class="bx bx-check-circle"></i></div>
            <div class="ms-3">
                <h6 class="mb-0 text-success">Sukses</h6>
                <p class="mb-0"><?= session('success') ?></p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')) : ?>
    <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-danger"><i class="bx bx-x-circle"></i></div>
            <div class="ms-3">
                <h6 class="mb-0 text-danger">Error</h6>
                <p class="mb-0"><?= session('error') ?></p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h5 class="mb-0">Daftar Paket</h5>
        </div>
        <hr />
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Paket</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Item</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi melalui AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus paket <span id="deleteName" class="fw-bold"></span>?</p>
                <p class="text-danger mb-0">Semua data terkait paket ini akan ikut terhapus.</p>
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
            ajax: {
                url: '<?= base_url('admin/paket/getAll') ?>',
                type: 'GET',
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let img = '';
                        if (row.foto) {
                            img = `<img src="<?= base_url('uploads/paket/') ?>/${row.foto}" class="me-2" width="40" height="40" style="object-fit: cover; border-radius: 5px;">`;
                        }
                        return `${img}${row.namapaket}`;
                    }
                },
                {
                    data: 'namakategori'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `Rp ${formatNumber(row.harga)}`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        // Jumlah item akan diisi melalui AJAX
                        return `<button class="btn btn-sm btn-info btn-view-items" data-id="${row.kdpaket}">
                            <i class="bx bx-package"></i> Lihat Items
                        </button>`;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('admin/paket/detail/') ?>/${row.kdpaket}" class="btn btn-sm btn-info">
                                    <i class="bx bx-detail"></i>
                                </a>
                                <a href="<?= base_url('admin/paket/edit/') ?>/${row.kdpaket}" class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${row.kdpaket}" data-name="${row.namapaket}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true
        });

        // Format number
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // Hapus paket
        let deleteId = null;
        $(document).on('click', '.btn-delete', function() {
            deleteId = $(this).data('id');
            const name = $(this).data('name');
            $('#deleteName').text(name);
            $('#deleteModal').modal('show');
        });

        // Konfirmasi hapus
        $('#confirmDelete').on('click', function() {
            if (!deleteId) return;

            $.ajax({
                url: `<?= base_url('admin/paket/delete/') ?>/${deleteId}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        table.ajax.reload();
                        showAlert('success', 'Sukses', response.message);
                    } else {
                        showAlert('danger', 'Error', 'Gagal menghapus paket');
                    }
                    $('#deleteModal').modal('hide');
                },
                error: function() {
                    showAlert('danger', 'Error', 'Terjadi kesalahan pada server');
                    $('#deleteModal').modal('hide');
                }
            });
        });

        // View detail items
        $(document).on('click', '.btn-view-items', function() {
            const paketId = $(this).data('id');

            $.ajax({
                url: `<?= base_url('admin/paket/getDetailPaket/') ?>/${paketId}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status && response.data.length > 0) {
                        let itemsText = '';
                        response.data.forEach((item, index) => {
                            itemsText += `${index + 1}. ${item.namabarang} - ${item.jumlah} ${item.satuan}\n`;
                        });

                        Swal.fire({
                            title: 'Detail Items',
                            html: `<pre class="text-start">${itemsText}</pre>`,
                            icon: 'info'
                        });
                    } else {
                        Swal.fire({
                            title: 'Informasi',
                            text: 'Tidak ada barang dalam paket ini',
                            icon: 'info'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal mengambil data items',
                        icon: 'error'
                    });
                }
            });
        });

        // Fungsi untuk menampilkan alert
        function showAlert(type, title, message) {
            Swal.fire({
                title: title,
                text: message,
                icon: type === 'success' ? 'success' : 'error',
                confirmButtonColor: '#3085d6'
            });
        }
    });
</script>
<?= $this->endSection() ?>
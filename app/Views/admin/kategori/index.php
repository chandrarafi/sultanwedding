<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Kategori</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h5 class="mb-0">Data Kategori</h5>
            <div class="ms-auto">
                <button type="button" class="btn btn-primary px-3 radius-30" id="btnAdd">
                    <i class="bx bx-plus"></i>Tambah Kategori
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover table-striped" id="tableKategori" width="100%">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="55%">Nama Kategori</th>
                        <th width="25%">Tanggal Dibuat</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data akan diisi oleh DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Kategori -->
<div class="modal fade" id="modalKategori" tabindex="-1" aria-labelledby="modalKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKategoriLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formKategori">
                <div class="modal-body">
                    <input type="hidden" name="kdkategori" id="kdkategori">
                    <div class="mb-3">
                        <label for="namakategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="namakategori" name="namakategori" required>
                        <div class="invalid-feedback" id="namakategori-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Kategori -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
                <input type="hidden" id="delete_kdkategori">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    /* Perbaikan tampilan DataTables */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_length {
        float: left;
    }

    .dataTables_wrapper .dataTables_filter {
        float: right;
    }

    .dataTables_wrapper .dataTables_info {
        clear: both;
        float: left;
        padding-top: 10px;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        padding-top: 10px;
    }

    /* Style untuk icon aksi */
    .action-icon {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .action-icon.edit {
        background-color: rgba(255, 193, 7, 0.15);
        color: #ffc107;
    }

    .action-icon.edit:hover {
        background-color: #ffc107;
        color: #fff;
    }

    .action-icon.delete {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .action-icon.delete:hover {
        background-color: #dc3545;
        color: #fff;
    }

    /* Perbaikan tampilan pada perangkat mobile */
    @media (max-width: 767px) {

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            float: none;
            text-align: center;
        }
    }
</style>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $('#tableKategori').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "<?= site_url('admin/kategori/getAll') ?>",
                type: "POST",
                data: function(d) {
                    return d;
                }
            },
            columns: [{
                    data: null,
                    "sortable": false,
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "namakategori"
                },
                {
                    data: "created_at",
                    render: function(data, type, row) {
                        // Format tanggal tanpa moment.js
                        if (!data) return "-";

                        // Parsing tanggal dari string
                        var date = new Date(data);

                        // Array nama bulan dalam bahasa Indonesia
                        var bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                        // Format tanggal: DD MMM YYYY HH:MM
                        var tanggal = date.getDate();
                        var namaBulan = bulan[date.getMonth()];
                        var tahun = date.getFullYear();
                        var jam = date.getHours().toString().padStart(2, '0');
                        var menit = date.getMinutes().toString().padStart(2, '0');

                        return tanggal + ' ' + namaBulan + ' ' + tahun + ' ' + jam + ':' + menit;
                    }
                },
                {
                    data: null,
                    className: "text-center",
                    "sortable": false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="javascript:;" class="action-icon edit btn-edit" data-id="${row.kdkategori}" title="Edit">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <a href="javascript:;" class="action-icon delete btn-delete" data-id="${row.kdkategori}" title="Hapus">
                                    <i class="bx bx-trash-alt"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [
                [2, 'desc']
            ],
            language: {
                processing: '<i class="bx bx-loader bx-spin font-medium-5"></i><span class="ms-1">Loading...</span>',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data yang cocok",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
        });

        // Tambah Kategori
        $('#btnAdd').click(function() {
            $('#formKategori')[0].reset();
            $('#kdkategori').val('');
            $('#modalKategoriLabel').text('Tambah Kategori');
            $('#modalKategori').modal('show');
            clearErrors();
        });

        // Edit Kategori
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            clearErrors();

            $.ajax({
                url: "<?= site_url('admin/kategori/edit') ?>",
                type: "POST",
                data: {
                    kdkategori: id
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        $('#kdkategori').val(response.data.kdkategori);
                        $('#namakategori').val(response.data.namakategori);
                        $('#modalKategoriLabel').text('Edit Kategori');
                        $('#modalKategori').modal('show');
                    } else {
                        Swal.fire('Error', response.message || 'Terjadi kesalahan', 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', errorThrown);
                    Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                }
            });
        });

        // Hapus Kategori
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            $('#delete_kdkategori').val(id);
            $('#deleteModal').modal('show');
        });

        $('#btnDelete').click(function() {
            const id = $('#delete_kdkategori').val();

            $.ajax({
                url: "<?= site_url('admin/kategori/delete') ?>",
                type: "POST",
                data: {
                    kdkategori: id
                },
                dataType: "JSON",
                success: function(response) {
                    $('#deleteModal').modal('hide');

                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                    } else {
                        Swal.fire('Error', response.message || 'Gagal menghapus data', 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#deleteModal').modal('hide');
                    console.error('Error:', errorThrown);
                    Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
                }
            });
        });

        // Simpan Kategori
        $('#formKategori').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const kdkategori = $('#kdkategori').val();
            const actionText = kdkategori ? 'memperbarui' : 'menyimpan';

            $.ajax({
                url: "<?= site_url('admin/kategori/save') ?>",
                type: "POST",
                data: formData,
                dataType: "JSON",
                beforeSend: function() {
                    $('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                    $('#btnSave').attr('disabled', true);
                    clearErrors();
                },
                complete: function() {
                    $('#btnSave').html('Simpan');
                    $('#btnSave').attr('disabled', false);
                },
                success: function(response) {
                    if (response.status) {
                        $('#modalKategori').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        table.ajax.reload();
                    } else {
                        if (response.errors) {
                            showErrors(response.errors);
                        } else {
                            Swal.fire('Error', response.message || 'Gagal menyimpan data', 'error');
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', errorThrown);
                    Swal.fire('Error', 'Terjadi kesalahan saat ' + actionText + ' data', 'error');
                }
            });
        });

        function clearErrors() {
            $('#namakategori-error').html('');
            $('#namakategori').removeClass('is-invalid');
        }

        function showErrors(errors) {
            if (errors.namakategori) {
                $('#namakategori').addClass('is-invalid');
                $('#namakategori-error').html(errors.namakategori);
            }
        }
    });
</script>
<?= $this->endSection() ?>
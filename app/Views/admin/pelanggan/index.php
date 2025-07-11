<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h5 class="mb-0">Data Pelanggan</h5>
            <div class="ms-auto">
                <button type="button" class="btn btn-primary px-3 radius-30" id="btnAdd">
                    <i class="bx bx-plus"></i>Tambah Pelanggan
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover table-striped" id="tablePelanggan" width="100%">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="20%">Nama Pelanggan</th>
                        <th width="20%">Alamat</th>
                        <th width="15%">No. HP</th>
                        <th width="15%">Akun User</th>
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

<!-- Modal Tambah/Edit Pelanggan -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" aria-labelledby="modalPelangganLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPelangganLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formPelanggan">
                <div class="modal-body">
                    <input type="hidden" name="kdpelanggan" id="kdpelanggan">
                    <input type="hidden" name="iduser" id="iduser">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="namapelanggan" class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="namapelanggan" name="namapelanggan" required>
                            <div class="invalid-feedback" id="namapelanggan-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nohp" class="form-label">No. HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nohp" name="nohp" required>
                            <div class="invalid-feedback" id="nohp-error"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                        <div class="invalid-feedback" id="alamat-error"></div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="create_user_switch" checked>
                            <label class="form-check-label" for="create_user_switch">Buat akun user untuk pelanggan ini</label>
                            <input type="hidden" name="create_user" id="create_user" value="yes">
                        </div>
                    </div>

                    <div id="user_account_section">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username">
                                <div class="invalid-feedback" id="username-error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email">
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger password-required">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text" id="passwordHelp">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah password (mode edit).</div>
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>
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

<!-- Modal Hapus Pelanggan -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data pelanggan ini?</p>
                <input type="hidden" id="delete_kdpelanggan">
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

    .action-icon.view {
        background-color: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .action-icon.view:hover {
        background-color: #0d6efd;
        color: #fff;
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
</style>

<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $('#tablePelanggan').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "<?= site_url('admin/pelanggan/getAll') ?>",
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
                    data: "namapelanggan"
                },
                {
                    data: "alamat",
                    render: function(data) {
                        return data || '-';
                    }
                },
                {
                    data: "nohp"
                },
                {
                    data: "username",
                    render: function(data, type, row) {
                        if (data) {
                            return data + '<br><small class="text-muted">' + (row.email || '-') + '</small>';
                        }
                        return '<span class="badge bg-secondary">Tidak Ada</span>';
                    }
                },

                {
                    data: null,
                    className: "text-center",
                    "sortable": false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <a href="javascript:;" class="action-icon edit btn-edit" data-id="${row.kdpelanggan}" title="Edit">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <a href="javascript:;" class="action-icon delete btn-delete" data-id="${row.kdpelanggan}" title="Hapus">
                                    <i class="bx bx-trash-alt"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            order: [
                [1, 'asc']
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

        // Toggle user account section
        $('#create_user_switch').change(function() {
            if ($(this).is(':checked')) {
                $('#user_account_section').show();
                $('#create_user').val('yes');
                if ($('#iduser').val() === '') {
                    $('.password-required').show();
                }
            } else {
                $('#user_account_section').hide();
                $('#create_user').val('no');
                $('.password-required').hide();
            }
        });

        // Tambah Pelanggan
        $('#btnAdd').click(function() {
            $('#formPelanggan')[0].reset();
            $('#kdpelanggan').val('');
            $('#iduser').val('');
            $('#modalPelangganLabel').text('Tambah Pelanggan');
            $('#create_user_switch').prop('checked', true).trigger('change');
            $('.password-required').show();
            $('#passwordHelp').text('Minimal 6 karakter.');
            $('#modalPelanggan').modal('show');
            clearErrors();
        });

        // Edit Pelanggan
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            clearErrors();

            $.ajax({
                url: "<?= site_url('admin/pelanggan/getById') ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(response) {
                    if (response.status) {
                        const data = response.data;

                        $('#kdpelanggan').val(data.kdpelanggan);
                        $('#namapelanggan').val(data.namapelanggan);
                        $('#alamat').val(data.alamat);
                        $('#nohp').val(data.nohp);

                        // User account data
                        if (data.iduser) {
                            $('#iduser').val(data.iduser);
                            $('#username').val(data.username);
                            $('#email').val(data.email);
                            $('#create_user_switch').prop('checked', true);
                            $('.password-required').hide();
                            $('#passwordHelp').text('Kosongkan jika tidak ingin mengubah password.');
                        } else {
                            $('#iduser').val('');
                            $('#username').val('');
                            $('#email').val('');
                            $('#create_user_switch').prop('checked', false);
                        }

                        $('#create_user_switch').trigger('change');
                        $('#modalPelangganLabel').text('Edit Pelanggan');
                        $('#modalPelanggan').modal('show');
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

        // Hapus Pelanggan
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            $('#delete_kdpelanggan').val(id);
            $('#deleteModal').modal('show');
        });

        $('#btnDelete').click(function() {
            const id = $('#delete_kdpelanggan').val();

            $.ajax({
                url: "<?= site_url('admin/pelanggan/delete') ?>",
                type: "POST",
                data: {
                    kdpelanggan: id
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

        // Simpan Pelanggan
        $('#formPelanggan').submit(function(e) {
            e.preventDefault();

            const formData = $(this).serialize();
            const kdpelanggan = $('#kdpelanggan').val();
            const actionText = kdpelanggan ? 'memperbarui' : 'menyimpan';

            $.ajax({
                url: "<?= site_url('admin/pelanggan/save') ?>",
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
                        $('#modalPelanggan').modal('hide');
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
                    console.error('Error:', jqXHR);
                    if (jqXHR.responseJSON && jqXHR.responseJSON.messages) {
                        const response = jqXHR.responseJSON.messages;
                        if (response.errors) {
                            showErrors(response.errors);
                        } else {
                            Swal.fire('Error', response.message || 'Terjadi kesalahan saat ' + actionText + ' data', 'error');
                        }
                    } else if (jqXHR.responseJSON) {
                        // Coba ambil langsung dari responseJSON jika tidak ada messages
                        const response = jqXHR.responseJSON;
                        if (response.errors) {
                            showErrors(response.errors);
                        } else {
                            Swal.fire('Error', response.message || 'Terjadi kesalahan saat ' + actionText + ' data', 'error');
                        }
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan saat ' + actionText + ' data', 'error');
                    }
                }
            });
        });

        function clearErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        function showErrors(errors) {
            console.log('Showing errors:', errors);
            $.each(errors, function(field, message) {
                // Ubah format field jika perlu
                const fieldId = field.replace(/\./g, '-');
                $('#' + fieldId).addClass('is-invalid');
                $('#' + fieldId + '-error').text(message);

                // Jika field adalah username, email, atau password, pastikan section user account ditampilkan
                if (['username', 'email', 'password'].includes(field)) {
                    $('#create_user_switch').prop('checked', true).trigger('change');
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
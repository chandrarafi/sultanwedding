<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Manajemen Sistem</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Pengguna</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <button type="button" class="btn btn-primary px-3 radius-30" id="btnAddUser">
            <i class="bx bx-user-plus"></i>Tambah Pengguna
        </button>
    </div>
</div>

<!-- Content Row -->
<div class="card">
    <div class="card-body">
        <!-- Filter Controls -->
        <div class="row mb-3 align-items-end">
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <label for="roleFilter" class="form-label">Filter berdasarkan Role</label>
                <select class="form-select" id="roleFilter">
                    <option value="">Semua Role</option>
                    <!-- Role options will be loaded dynamically -->
                </select>
            </div>
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <label for="statusFilter" class="form-label">Filter berdasarkan Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-secondary" id="resetFilter">
                    <i class="bx bx-reset"></i> Reset Filter
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table align-middle table-striped table-hover" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Login Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded by DataTables AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEditUserForm">
                <div class="modal-body">
                    <input type="hidden" id="userId" name="id">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div class="invalid-feedback" id="usernameError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <!-- Role options will be loaded dynamically -->
                            </select>
                            <div class="invalid-feedback" id="roleError"></div>
                        </div>
                    </div>

                    <div class="row mb-3" id="passwordGroup">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text" id="passwordHelp">Minimal 8 karakter</div>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                            <div class="invalid-feedback" id="password_confirmError"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="active" name="status" value="active" checked>
                            <label class="form-check-label" for="active">Status Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View User Details -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Username</th>
                            <td id="viewUsername"></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td id="viewName"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="viewEmail"></td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td id="viewRole"></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="viewStatus"></td>
                        </tr>
                        <tr>
                            <th>Login Terakhir</th>
                            <td id="viewLastLogin"></td>
                        </tr>
                        <tr>
                            <th>Dibuat Pada</th>
                            <td id="viewCreatedAt"></td>
                        </tr>
                        <tr>
                            <th>Diperbarui Pada</th>
                            <td id="viewUpdatedAt"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
        // Load roles for filter and form
        $.ajax({
            url: '<?= site_url('admin/getRoles') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Populate role filter dropdown
                var roleFilter = $('#roleFilter');
                $.each(data.data, function(index, role) {
                    roleFilter.append($('<option></option>').attr('value', role.name).text(role.name));
                });

                // Populate role selection in add/edit user form
                var roleSelect = $('#role');
                $.each(data.data, function(index, role) {
                    roleSelect.append($('<option></option>').attr('value', role.name).text(role.name));
                });
            }
        });

        // Initialize DataTables
        var usersTable = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/getAllUsers') ?>',
                type: 'POST',
                data: function(d) {
                    d.role = $('#roleFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'username'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role',
                    render: function(data) {
                        return `<span class="badge bg-primary">${data}</span>`;
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data == 'active') {
                            return '<span class="badge bg-success">Aktif</span>';
                        } else {
                            return '<span class="badge bg-danger">Tidak Aktif</span>';
                        }
                    }
                },
                {
                    data: 'last_login',
                    render: function(data) {
                        if (!data) return 'Belum Pernah Login';

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
                    render: function(data) {
                        return `
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="javascript:;" class="action-icon view" onclick="viewUser(${data.id})" title="Lihat Detail">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="javascript:;" class="action-icon edit" onclick="editUser(${data.id})" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <a href="javascript:;" class="action-icon delete" onclick="deleteUser(${data.id})" title="Hapus">
                                <i class="bx bx-trash-alt"></i>
                            </a>
                        </div>`;
                    },
                    orderable: false
                }
            ],
            order: [
                [0, 'asc']
            ],
            language: {
                processing: '<i class="bx bx-loader bx-spin font-medium-5"></i><span class="ms-1">Loading...</span>',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // Filter functionality
        $('#roleFilter, #statusFilter').change(function() {
            usersTable.ajax.reload();
        });

        // Reset filter button
        $('#resetFilter').click(function() {
            $('#roleFilter').val('');
            $('#statusFilter').val('');
            usersTable.ajax.reload();
        });

        // Add User button
        $('#btnAddUser').click(function() {
            $('#addEditUserForm')[0].reset();
            $('#userId').val('');
            $('#userModalTitle').text('Tambah Pengguna Baru');
            $('#passwordGroup').show();
            $('#passwordHelp').text('Minimal 8 karakter');
            $('#userModal').modal('show');
        });

        // Form validation and submission
        $('#addEditUserForm').submit(function(e) {
            e.preventDefault();

            // Reset validation errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Get form data
            var formData = new FormData(this);
            var userId = $('#userId').val();
            var url = userId ?
                '<?= site_url('admin/users/update') ?>/' + userId :
                '<?= site_url('admin/users/create') ?>';

            // Convert checkbox value to proper status
            if ($('#active').is(':checked')) {
                formData.set('status', 'active');
            } else {
                formData.set('status', 'inactive');
            }

            // Send AJAX request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#submitBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                    $('#submitBtn').attr('disabled', true);
                },
                complete: function() {
                    $('#submitBtn').html('Simpan');
                    $('#submitBtn').attr('disabled', false);
                },
                success: function(response) {
                    if (response.status) {
                        $('#userModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        usersTable.ajax.reload();
                    } else {
                        if (response.errors) {
                            // Display validation errors
                            $.each(response.errors, function(field, message) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + 'Error').text(message);
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                }
            });
        });

        // View user details function
        window.viewUser = function(id) {
            $.ajax({
                url: '<?= site_url('admin/users/getById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        var user = response.data;

                        $('#viewUsername').text(user.username);
                        $('#viewName').text(user.name);
                        $('#viewEmail').text(user.email);
                        $('#viewRole').text(user.role);
                        $('#viewStatus').text(user.status == 'active' ? 'Aktif' : 'Tidak Aktif');

                        // Format tanggal last_login
                        var lastLoginText = 'Belum Pernah Login';
                        if (user.last_login) {
                            var lastLogin = new Date(user.last_login);
                            var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            lastLoginText = lastLogin.getDate() + ' ' + bulan[lastLogin.getMonth()] + ' ' +
                                lastLogin.getFullYear() + ' ' +
                                lastLogin.getHours().toString().padStart(2, '0') + ':' +
                                lastLogin.getMinutes().toString().padStart(2, '0') + ':' +
                                lastLogin.getSeconds().toString().padStart(2, '0');
                        }
                        $('#viewLastLogin').text(lastLoginText);

                        // Format tanggal created_at
                        var createdAt = new Date(user.created_at);
                        var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        var createdAtText = createdAt.getDate() + ' ' + bulan[createdAt.getMonth()] + ' ' +
                            createdAt.getFullYear() + ' ' +
                            createdAt.getHours().toString().padStart(2, '0') + ':' +
                            createdAt.getMinutes().toString().padStart(2, '0') + ':' +
                            createdAt.getSeconds().toString().padStart(2, '0');
                        $('#viewCreatedAt').text(createdAtText);

                        // Format tanggal updated_at
                        var updatedAt = new Date(user.updated_at);
                        var updatedAtText = updatedAt.getDate() + ' ' + bulan[updatedAt.getMonth()] + ' ' +
                            updatedAt.getFullYear() + ' ' +
                            updatedAt.getHours().toString().padStart(2, '0') + ':' +
                            updatedAt.getMinutes().toString().padStart(2, '0') + ':' +
                            updatedAt.getSeconds().toString().padStart(2, '0');
                        $('#viewUpdatedAt').text(updatedAtText);

                        $('#viewUserModal').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                }
            });
        };

        // Edit user function
        window.editUser = function(id) {
            $.ajax({
                url: '<?= site_url('admin/users/getById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        var user = response.data;

                        $('#addEditUserForm')[0].reset();
                        $('#userId').val(user.id);
                        $('#username').val(user.username);
                        $('#name').val(user.name);
                        $('#email').val(user.email);
                        $('#role').val(user.role);
                        $('#active').prop('checked', user.status == 'active');

                        $('#userModalTitle').text('Edit Pengguna');
                        $('#passwordGroup').show();
                        $('#passwordHelp').text('Kosongkan jika tidak ingin mengubah password');
                        $('#userModal').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                }
            });
        };

        // Delete user function
        window.deleteUser = function(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus pengguna ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url('admin/users/delete') ?>/' + id,
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

                                usersTable.ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
                        }
                    });
                }
            });
        };
    });
</script>
<?= $this->endSection() ?>
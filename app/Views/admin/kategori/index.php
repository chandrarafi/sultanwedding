<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Kategori</h5>
                <button type="button" class="btn btn-primary" id="btnAdd">
                    <i class="bi bi-plus-lg"></i> Tambah Kategori
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tableKategori">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Kategori</th>
                                <th>Tanggal Dibuat</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi oleh DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
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
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        var table = $('#tableKategori').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?= site_url('admin/kategori/getAll') ?>",
                type: "POST",
            },
            columns: [{
                    data: null,
                    "sortable": false,
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
                        return moment(data).format('DD MMM YYYY HH:mm');
                    }
                },
                {
                    data: null,
                    "sortable": false,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-info btn-edit" data-id="${row.kdkategori}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.kdkategori}">
                                <i class="bi bi-trash"></i>
                            </button>
                        `;
                    }
                }
            ],
            order: [
                [2, 'desc']
            ],
            language: {
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
            }
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
                        Swal.fire('Error', response.messages, 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
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
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#deleteModal').modal('hide');
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
                        if (response.messages) {
                            let errors = response.messages;
                            if (errors.namakategori) {
                                $('#namakategori').addClass('is-invalid');
                                $('#namakategori-error').text(errors.namakategori);
                            }
                        } else {
                            Swal.fire('Error', `Gagal ${actionText} data: ${response.message}`, 'error');
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Error', `Terjadi kesalahan saat ${actionText} data`, 'error');
                }
            });
        });

        // Helper function to clear validation errors
        function clearErrors() {
            $('#namakategori').removeClass('is-invalid');
            $('#namakategori-error').text('');
        }
    });
</script>
<?= $this->endSection() ?>
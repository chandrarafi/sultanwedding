<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Barang</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="btnTambah">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama Barang</th>
                            <th width="10%">Satuan</th>
                            <th width="10%">Jumlah</th>
                            <th width="20%">Harga Sewa</th>
                            <th width="15%">Aksi</th>
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

<!-- Modal Barang -->
<div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarangLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangLabel">Tambah Barang</h5>
                <button type="button" class="close" id="btnClose" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formBarang">
                <div class="modal-body">
                    <input type="hidden" id="id" name="kdbarang">

                    <div class="form-group">
                        <label for="namabarang">Nama Barang</label>
                        <input type="text" class="form-control" id="namabarang" name="namabarang" required>
                        <div class="invalid-feedback" id="namabarang-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" required>
                        <div class="invalid-feedback" id="satuan-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="0" required>
                        <div class="invalid-feedback" id="jumlah-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="hargasewa">Harga Sewa</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" id="hargasewa" name="hargasewa" min="0" required>
                        </div>
                        <div class="invalid-feedback" id="hargasewa-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnTutup">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="detailImage" class="img-fluid img-thumbnail" style="max-height: 300px;">
                </div>
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Nama Barang</th>
                        <td width="5%">:</td>
                        <td id="detailNama"></td>
                    </tr>
                    <tr>
                        <th>Satuan</th>
                        <td>:</td>
                        <td id="detailSatuan"></td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>:</td>
                        <td id="detailJumlah"></td>
                    </tr>
                    <tr>
                        <th>Harga Sewa</th>
                        <td>:</td>
                        <td id="detailHarga"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
                "targets": [0, 5], // No dan Aksi tidak diurutkan
                "orderable": false,
            }],
        });

        // Load data
        loadBarang();

        // Tombol Tambah
        $('#btnTambah').click(function() {
            resetForm();
            $('#modalBarang').modal('show');
        });

        // Tombol tutup dan close (X)
        $('#btnTutup, #btnClose').click(function() {
            $('#modalBarang').modal('hide');
        });

        // Reset form when modal is closed
        $('#modalBarang').on('hidden.bs.modal', function() {
            resetForm();
        });

        // Handle form submission
        $('#formBarang').submit(function(e) {
            e.preventDefault();

            // Reset validation errors
            $('.is-invalid').removeClass('is-invalid');

            // Get form data
            let formData = new FormData(this);

            // Determine URL based on whether we're editing or adding
            let url = formData.get('kdbarang') ?
                '<?= base_url('admin/barang/update') ?>/' + formData.get('kdbarang') :
                '<?= base_url('admin/barang/create') ?>';

            // Send AJAX request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#btnSave').html('<i class="fa fa-spinner fa-spin"></i>');
                    $('#btnSave').attr('disabled', true);
                },
                complete: function() {
                    $('#btnSave').html('Simpan');
                    $('#btnSave').attr('disabled', false);
                },
                success: function(response) {
                    if (response.status) {
                        // Success
                        $('#modalBarang').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Reload data
                        loadBarang();
                    } else {
                        // Failed with validation errors
                        if (response.errors) {
                            // Display validation errors
                            $.each(response.errors, function(field, message) {
                                $('#' + field).addClass('is-invalid');
                                $('#' + field + '-error').text(message);
                            });
                        } else {
                            // Other error
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                            });
                        }
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
        });

        // Handle click on edit button (delegated event)
        $('#tableBody').on('click', '.btn-edit', function() {
            const id = $(this).data('id');

            // Get data by ID
            $.ajax({
                url: '<?= base_url('admin/barang/getById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // Set form title
                        $('#modalBarangLabel').text('Edit Barang');

                        // Fill form with data
                        $('#id').val(response.data.kdbarang);
                        $('#namabarang').val(response.data.namabarang);
                        $('#satuan').val(response.data.satuan);
                        $('#jumlah').val(response.data.jumlah);
                        $('#hargasewa').val(response.data.hargasewa);

                        // Show modal
                        $('#modalBarang').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
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
        });

        // Handle click on delete button (delegated event)
        $('#tableBody').on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            // Confirm deletion
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Akan menghapus barang "${nama}"`,
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
                        url: '<?= base_url('admin/barang/delete') ?>/' + id,
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
                                loadBarang();
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

        // Function to load barang data
        function loadBarang() {
            $.ajax({
                url: '<?= base_url('admin/barang/getAll') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // Clear table
                        table.clear();

                        // Add data to table
                        $.each(response.data, function(i, item) {
                            // Format harga
                            let harga = formatRupiah(item.hargasewa);

                            // Format actions
                            let actions = `
                                <button type="button" class="btn btn-sm btn-primary btn-edit" data-id="${item.kdbarang}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="${item.kdbarang}" data-nama="${item.namabarang}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            `;

                            // Add row to table
                            table.row.add([
                                i + 1,
                                item.namabarang,
                                item.satuan,
                                item.jumlah,
                                harga,
                                actions
                            ]);
                        });

                        // Draw table
                        table.draw();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data barang',
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

        // Function to reset form
        function resetForm() {
            $('#modalBarangLabel').text('Tambah Barang');
            $('#formBarang')[0].reset();
            $('#id').val('');
            $('.is-invalid').removeClass('is-invalid');
        }

        // Function to format currency
        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }
    });
</script>
<?= $this->endSection() ?>
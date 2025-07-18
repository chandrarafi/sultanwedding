<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Inline styles for this page -->
<style>
    .table .d-flex.gap-2 {
        display: flex !important;
        flex-direction: row !important;
        gap: 5px !important;
    }

    .table .btn {
        width: 34px !important;
        height: 34px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 4px !important;
    }

    .table .btn i {
        font-size: 16px !important;
        margin: 0 !important;
    }
</style>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Barang</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Data Barang</h5>
            <div class="ms-auto">
                <button type="button" class="btn btn-primary px-3 radius-30" id="btnTambah">
                    <i class="bx bx-plus"></i>Tambah Barang
                </button>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="table align-middle table-striped table-hover" id="dataTable">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Barang</th>
                        <th width="10%">Satuan</th>
                        <th width="10%">Jumlah</th>
                        <th width="20%">Harga Sewa</th>
                        <th width="30%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Barang -->
<div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangLabel">Tambah Barang</h5>
                <button type="button" class="btn-close" id="btnClose" aria-label="Close"></button>
            </div>
            <form id="formBarang" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="kdbarang" id="kdbarang">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="mb-3">
                        <label for="namabarang" class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="namabarang" name="namabarang" required>
                        <div class="invalid-feedback" id="namabarang-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="satuan" class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" required>
                        <div class="invalid-feedback" id="satuan-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" min="0" required>
                        <div class="invalid-feedback" id="jumlah-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="hargasewa" class="form-label">Harga Sewa</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="hargasewa" name="hargasewa" min="0" required>
                        </div>
                        <div class="invalid-feedback" id="hargasewa-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Barang</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <div class="form-text">Upload foto barang (opsional). Format: JPG, PNG, GIF. Maks: 2MB.</div>
                        <div class="invalid-feedback" id="foto-error"></div>
                        <div id="preview-container" class="mt-2 d-none">
                            <img id="preview-image" class="img-thumbnail" style="max-height: 200px;">
                        </div>
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
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailLabel">Detail Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="detailImage" class="img-fluid rounded" style="max-height: 300px;">
                </div>
                <div class="list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <span class="fw-bold">Nama Barang</span>
                        <span id="detailNama"></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <span class="fw-bold">Satuan</span>
                        <span id="detailSatuan"></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <span class="fw-bold">Jumlah</span>
                        <span id="detailJumlah"></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <span class="fw-bold">Harga Sewa</span>
                        <span id="detailHarga"></span>
                    </div>
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
                "targets": [0, 5], // No dan Aksi tidak diurutkan
                "orderable": false,
            }],
        });

        // Initialize tooltips function
        function initTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Call initialization
        initTooltips();

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

        // Update CSRF token function
        function updateCsrfToken(token) {
            // Update hidden field in form
            $('input[name="<?= csrf_token() ?>"]').val(token);
            // Update meta tag
            $('meta[name="csrf_token"]').attr('content', token);
        }

        // Submit form for add/edit barang
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
                    $('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                    $('#btnSave').attr('disabled', true);
                },
                complete: function() {
                    $('#btnSave').html('Simpan');
                    $('#btnSave').attr('disabled', false);
                },
                success: function(response) {
                    // Update CSRF token if available
                    if (response.csrf_token) {
                        updateCsrfToken(response.csrf_token);
                    }

                    if (response.status) {
                        $('#modalBarang').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadBarang(); // Reload data
                    } else {
                        if (response.errors) {
                            // Display validation errors
                            let errors = response.errors;
                            if (errors.namabarang) {
                                $('#namabarang').addClass('is-invalid');
                                $('#namabarang-error').text(errors.namabarang);
                            }
                            if (errors.satuan) {
                                $('#satuan').addClass('is-invalid');
                                $('#satuan-error').text(errors.satuan);
                            }
                            if (errors.jumlah) {
                                $('#jumlah').addClass('is-invalid');
                                $('#jumlah-error').text(errors.jumlah);
                            }
                            if (errors.hargasewa) {
                                $('#hargasewa').addClass('is-invalid');
                                $('#hargasewa-error').text(errors.hargasewa);
                            }
                        } else {
                            // Display general error
                            Swal.fire('Error', response.message, 'error');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
                }
            });
        });

        // Function to load barang data
        function loadBarang() {
            $.ajax({
                url: '<?= base_url('admin/barang/getAll') ?>',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                success: function(response) {
                    // Update CSRF token if available
                    if (response.csrf_token) {
                        updateCsrfToken(response.csrf_token);
                    }

                    if (response.status) {
                        // Clear table
                        table.clear();

                        // Add data to table
                        $.each(response.data, function(index, item) {
                            let no = index + 1;
                            let hargaFormatted = formatRupiah(item.hargasewa);
                            let actions = `
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-detail" data-id="${item.kdbarang}" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-edit" data-id="${item.kdbarang}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-delete" data-id="${item.kdbarang}" data-name="${item.namabarang}" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    <a href="<?= site_url('admin/barang/history') ?>/${item.kdbarang}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="History Stok">
                                        <i class="bx bx-history"></i>
                                    </a>
                                </div>
                            `;

                            table.row.add([
                                no,
                                item.namabarang,
                                item.satuan,
                                item.jumlah,
                                'Rp ' + hargaFormatted,
                                actions
                            ]);
                        });

                        // Draw table
                        table.draw();

                        // Reinitialize tooltips after table is redrawn
                        setTimeout(function() {
                            initTooltips();
                        }, 200);
                    } else {
                        Swal.fire('Error', 'Gagal memuat data barang', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
                }
            });
        }

        // Function to reset form
        function resetForm() {
            $('#kdbarang').val(''); // Changed from $('#id').val('')
            $('#formBarang')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('#modalBarangLabel').text('Tambah Barang');
            $('#preview-container').addClass('d-none');
        }

        // Format number to Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // No longer need global functions as we're using document event handlers instead

        // Preview image when file is selected
        $('#foto').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                    $('#preview-container').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview-container').addClass('d-none');
            }
        });

        // Delete button
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/barang/delete') ?>/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                        },
                        success: function(response) {
                            // Update CSRF token if available
                            if (response.csrf_token) {
                                updateCsrfToken(response.csrf_token);
                            }

                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                loadBarang(); // Reload data
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

        // Edit barang
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('admin/barang/getById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Update CSRF token if available
                    if (response.csrf_token) {
                        updateCsrfToken(response.csrf_token);
                    }

                    if (response.status) {
                        const barang = response.data;
                        $('#kdbarang').val(barang.kdbarang); // Changed from $('#id').val(barang.kdbarang)
                        $('#namabarang').val(barang.namabarang);
                        $('#satuan').val(barang.satuan);
                        $('#jumlah').val(barang.jumlah);
                        $('#hargasewa').val(barang.hargasewa);

                        // Show image preview if exists
                        if (barang.foto) {
                            $('#preview-image').attr('src', '<?= base_url('uploads/barang') ?>/' + barang.foto);
                            $('#preview-container').removeClass('d-none');
                        } else {
                            $('#preview-container').addClass('d-none');
                        }

                        $('#modalBarangLabel').text('Edit Barang');
                        $('#modalBarang').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                }
            });
        });

        // Show detail barang
        $(document).on('click', '.btn-detail', function() {
            const id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('admin/barang/getById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        const barang = response.data;
                        $('#detailNama').text(barang.namabarang);
                        $('#detailSatuan').text(barang.satuan);
                        $('#detailJumlah').text(barang.jumlah);
                        $('#detailHarga').text('Rp ' + formatRupiah(barang.hargasewa));

                        // Show image if exists
                        if (barang.foto) {
                            $('#detailImage').attr('src', '<?= base_url('uploads/barang') ?>/' + barang.foto);
                            $('#detailImage').parent().show();
                        } else {
                            // Show default image or hide image container
                            $('#detailImage').attr('src', '<?= base_url('assets/images/products/01.png') ?>');
                            $('#detailImage').parent().show();
                        }

                        $('#modalDetail').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
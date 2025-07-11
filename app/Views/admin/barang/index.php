<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
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

<!-- Modal Barang -->
<div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangLabel">Tambah Barang</h5>
                <button type="button" class="btn-close" id="btnClose" aria-label="Close"></button>
            </div>
            <form id="formBarang">
                <div class="modal-body">
                    <input type="hidden" id="id" name="kdbarang">

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
                    $('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
                    $('#btnSave').attr('disabled', true);
                },
                complete: function() {
                    $('#btnSave').html('Simpan');
                    $('#btnSave').attr('disabled', false);
                },
                success: function(response) {
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
                success: function(response) {
                    if (response.status) {
                        // Clear table first
                        table.clear();

                        // Add rows
                        $.each(response.data, function(i, item) {
                            table.row.add([
                                i + 1,
                                item.namabarang,
                                item.satuan,
                                item.jumlah,
                                formatRupiah(item.hargasewa),
                                `<div class="d-flex align-items-center gap-3 fs-6">
                                    <a href="javascript:;" class="text-primary" onclick="viewBarang(${item.kdbarang})" title="Lihat Detail">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="javascript:;" class="text-warning" onclick="editBarang(${item.kdbarang})" title="Edit">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <a href="javascript:;" class="text-danger" onclick="deleteBarang(${item.kdbarang})" title="Hapus">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </div>`
                            ]).draw(false);
                        });
                    } else {
                        Swal.fire('Error', 'Gagal memuat data barang', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
                }
            });
        }

        // Reset form
        function resetForm() {
            $('#formBarang')[0].reset();
            $('#id').val('');
            $('.is-invalid').removeClass('is-invalid');
            $('#modalBarangLabel').text('Tambah Barang');
        }

        // Format Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        // Make functions globally accessible
        window.viewBarang = function(id) {
            $.ajax({
                url: '<?= base_url('admin/barang/getById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        let barang = response.data;
                        $('#detailNama').text(barang.namabarang);
                        $('#detailSatuan').text(barang.satuan);
                        $('#detailJumlah').text(barang.jumlah);
                        $('#detailHarga').text(formatRupiah(barang.hargasewa));

                        // Handle image
                        if (barang.gambar) {
                            $('#detailImage').attr('src', '<?= base_url('uploads/barang') ?>/' + barang.gambar);
                            $('#detailImage').show();
                        } else {
                            $('#detailImage').hide();
                        }

                        $('#modalDetail').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
                }
            });
        };

        window.editBarang = function(id) {
            $.ajax({
                url: '<?= base_url('admin/barang/getById') ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        let barang = response.data;
                        $('#id').val(barang.kdbarang);
                        $('#namabarang').val(barang.namabarang);
                        $('#satuan').val(barang.satuan);
                        $('#jumlah').val(barang.jumlah);
                        $('#hargasewa').val(barang.hargasewa);

                        $('#modalBarangLabel').text('Edit Barang');
                        $('#modalBarang').modal('show');
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat memuat data', 'error');
                }
            });
        };

        window.deleteBarang = function(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus barang ini?",
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
                        success: function(response) {
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
        };
    });
</script>
<?= $this->endSection() ?>
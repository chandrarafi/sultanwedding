<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/paket') ?>">Paket</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="<?= base_url('admin/paket') ?>" class="btn btn-light px-3 radius-30">
            <i class="bx bx-arrow-back"></i>Kembali
        </a>
    </div>
</div>

<?php if (session()->has('errors')) : ?>
    <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-danger"><i class="bx bx-x-circle"></i></div>
            <div class="ms-3">
                <h6 class="mb-0 text-danger">Error</h6>
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
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
        <div class="border p-4 rounded">
            <div class="card-title d-flex align-items-center">
                <h5 class="mb-0">Form Tambah Paket</h5>
            </div>
            <hr />
            <form action="<?= base_url('admin/paket/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="namapaket" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="namapaket" name="namapaket" value="<?= old('namapaket') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="kdkategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="kdkategori" name="kdkategori" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($kategori as $k) : ?>
                                <option value="<?= $k['kdkategori'] ?>" <?= old('kdkategori') == $k['kdkategori'] ? 'selected' : '' ?>>
                                    <?= $k['namakategori'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?= old('harga') ?>" min="0" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="detailpaket" class="form-label">Detail Paket</label>
                    <textarea class="form-control" id="detailpaket" name="detailpaket" rows="5"><?= old('detailpaket') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Paket</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    <div class="form-text">Format: JPG, JPEG, PNG. Maks: 2MB.</div>
                </div>

                <div class="preview-container mt-3 mb-3 d-none">
                    <label class="form-label">Preview</label>
                    <div class="text-center">
                        <img id="imagePreview" class="rounded" style="max-height: 200px;">
                    </div>
                </div>

                <!-- Detail Barang Section -->
                <div class="card mt-4 mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Detail Barang dalam Paket</h6>
                            <button type="button" class="btn btn-sm btn-primary" id="btnOpenBarangModal">
                                <i class="bx bx-plus"></i> Tambah Barang
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="detailPaketTable">
                                <thead>
                                    <tr>
                                        <th width="35%">Nama Barang</th>
                                        <th width="15%">Jumlah</th>
                                        <th width="20%">Harga</th>
                                        <th width="20%">Keterangan</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="detailItems">
                                    <tr class="empty-row">
                                        <td colspan="5" class="text-center">Belum ada item barang</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th colspan="2" class="text-end">Total Harga Barang</th>
                                        <th colspan="3" id="totalHargaBarang">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <button type="reset" class="btn btn-secondary px-4">Reset</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="barangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="searchBarang" placeholder="Cari barang...">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="barangTable">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barangList">
                            <!-- Data barang akan diisi melalui AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="loadingBarang" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div id="noBarangFound" class="alert alert-info d-none">
                    Tidak ada barang yang ditemukan.
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
        // Image preview
        $('#foto').change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#imagePreview').attr('src', event.target.result);
                    $('.preview-container').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                $('.preview-container').addClass('d-none');
            }
        });

        // Initialize barang modal
        const barangModal = new bootstrap.Modal(document.getElementById('barangModal'));

        // Load barang when opening modal
        $('#btnOpenBarangModal').click(function() {
            loadBarang();
            barangModal.show();
        });

        // Search barang
        let searchTimer;
        $('#searchBarang').on('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(loadBarang, 500);
        });

        // Load barang list
        function loadBarang() {
            const search = $('#searchBarang').val();
            $('#barangList').html('');
            $('#loadingBarang').removeClass('d-none');
            $('#noBarangFound').addClass('d-none');

            $.ajax({
                url: '<?= base_url('admin/paket/getBarang') ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    search: search
                },
                success: function(response) {
                    $('#loadingBarang').addClass('d-none');

                    if (response.status && response.data.length > 0) {
                        $.each(response.data, function(i, barang) {
                            const row = `
                                <tr>
                                    <td>${barang.namabarang}</td>
                                    <td>${barang.satuan}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success btn-pilih-barang" 
                                            data-id="${barang.kdbarang}" 
                                            data-nama="${barang.namabarang}" 
                                            data-satuan="${barang.satuan}" 
                                            data-harga="${barang.hargasewa}">
                                            <i class="bx bx-check"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            `;
                            $('#barangList').append(row);
                        });
                    } else {
                        $('#noBarangFound').removeClass('d-none');
                    }
                },
                error: function() {
                    $('#loadingBarang').addClass('d-none');
                    $('#noBarangFound').removeClass('d-none');
                    $('#noBarangFound').text('Terjadi kesalahan saat memuat data barang.');
                }
            });
        }

        // Handle pilih barang
        $(document).on('click', '.btn-pilih-barang', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const satuan = $(this).data('satuan');
            const harga = $(this).data('harga');

            addBarangToTable(id, nama, satuan, harga);
            barangModal.hide();
        });

        // Add barang to table
        function addBarangToTable(id, nama, satuan, harga) {
            $('.empty-row').remove();

            // Periksa apakah barang sudah ada di tabel
            let barangExists = false;
            $('input[name="detail_barang[]"]').each(function() {
                if ($(this).val() == id) {
                    barangExists = true;
                    const row = $(this).closest('tr');
                    const jumlah = parseInt(row.find('.detail-jumlah').val()) + 1;
                    row.find('.detail-jumlah').val(jumlah);
                    return false;
                }
            });

            if (barangExists) {
                updateTotalHarga();
                return;
            }

            const rowHtml = `
                <tr class="detail-item">
                    <td>
                        ${nama} (${satuan})
                        <input type="hidden" name="detail_barang[]" value="${id}">
                    </td>
                    <td>
                        <input type="number" name="detail_jumlah[]" class="form-control detail-jumlah" value="1" min="1" required onchange="updateTotalHarga()">
                    </td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="detail_harga[]" class="form-control detail-harga" value="${harga}" min="0" required onchange="updateTotalHarga()">
                        </div>
                    </td>
                    <td>
                        <input type="text" name="detail_keterangan[]" class="form-control detail-keterangan">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-item">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#detailItems').append(rowHtml);
            updateTotalHarga();
        }

        // Handle hapus item detail paket
        $(document).on('click', '.btn-remove-item', function() {
            $(this).closest('tr').remove();
            if ($('#detailItems tr').length === 0) {
                $('#detailItems').html(`
                    <tr class="empty-row">
                        <td colspan="5" class="text-center">Belum ada item barang</td>
                    </tr>
                `);
            }
            updateTotalHarga();
        });

        // Format number
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // Update total harga
        window.updateTotalHarga = function() {
            let total = 0;
            $('.detail-item').each(function() {
                const harga = parseFloat($(this).find('.detail-harga').val()) || 0;
                const jumlah = parseInt($(this).find('.detail-jumlah').val()) || 0;
                total += (harga * jumlah);
            });

            $('#totalHargaBarang').text('Rp ' + formatNumber(total));
        }
    });
</script>
<?= $this->endSection() ?>
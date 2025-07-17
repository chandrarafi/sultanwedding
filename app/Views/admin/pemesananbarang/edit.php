<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananbarang') ?>">Pemesanan Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Pemesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Form Edit Pemesanan Barang</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/pemesananbarang') ?>" class="btn btn-secondary px-3 radius-30">
                    <i class="bx bx-arrow-back"></i>Kembali
                </a>
            </div>
        </div>

        <form action="<?= site_url('admin/pemesananbarang/update/' . $pemesanan['kdpemesananbarang']) ?>" method="post" id="formPemesanan">
            <?= csrf_field() ?>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal Pemesanan</label>
                        <input type="date" class="form-control" id="tgl" name="tgl" value="<?= isset($pemesanan['tgl']) && !empty($pemesanan['tgl']) ? date('Y-m-d', strtotime($pemesanan['tgl'])) : date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kdpelanggan" class="form-label">Pelanggan</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="namapelanggan" value="<?= $pelanggan['namapelanggan'] ?? 'Walk-in Customer' ?>" readonly>
                            <input type="hidden" id="kdpelanggan" name="kdpelanggan" value="<?= $pemesanan['kdpelanggan'] ?>">
                        </div>
                        <small class="text-muted">Pelanggan tidak dapat diubah</small>
                    </div>
                    <div class="mb-3">
                        <label for="alamatpesanan" class="form-label">Alamat Pengiriman</label>
                        <textarea class="form-control" id="alamatpesanan" name="alamatpesanan" rows="3"><?= $pemesanan['alamatpesanan'] ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lamapemesanan" class="form-label">Lama Pemesanan (hari)</label>
                        <input type="number" class="form-control" id="lamapemesanan" name="lamapemesanan" min="1" value="<?= $pemesanan['lamapemesanan'] ?>" required>
                    </div>
                    <?php if (isset($pemesanan['kdpembayaran'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <div class="form-control bg-light">
                                <?php
                                if ($pemesanan['tipepembayaran'] == 'dp') {
                                    echo 'DP (50%)';
                                } elseif ($pemesanan['tipepembayaran'] == 'lunas') {
                                    echo 'Lunas';
                                } else {
                                    echo 'Belum Bayar';
                                }
                                ?>
                            </div>
                            <small class="text-muted">Status pembayaran tidak dapat diubah melalui form ini</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <div class="form-control bg-light"><?= isset($pemesanan['metodepembayaran']) ? ucfirst($pemesanan['metodepembayaran']) : '-' ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4 mb-3">
                <h5>Detail Barang</h5>
                <button type="button" class="btn btn-sm btn-success mb-3" id="btnTambahBarang">
                    <i class="bx bx-plus"></i> Tambah Barang
                </button>
                <div class="table-responsive">
                    <table class="table table-bordered" id="tableBarang">
                        <thead>
                            <tr>
                                <th width="35%">Nama Barang</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Jumlah</th>
                                <th width="20%">Subtotal</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail as $item): ?>
                                <tr>
                                    <td>
                                        <?= $item['namabarang'] ?>
                                        <input type="hidden" name="kdbarang[]" value="<?= $item['kdbarang'] ?>" class="kdbarang">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm harga" name="harga[]" value="<?= $item['harga'] ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm jumlah" name="jumlah[]" value="<?= $item['jumlah'] ?>" min="1" data-stok="<?= $item['stok'] ?? 100 ?>">
                                        <input type="hidden" class="stoktersedia" value="<?= $item['stok'] ?? 100 ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm subtotal" name="subtotal[]" value="<?= $item['subtotal'] ?>" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus-barang">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th id="grandTotal">Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <input type="hidden" name="grandtotal" id="grandtotal_input" value="<?= $pemesanan['grandtotal'] ?>">
            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-5" id="btnSimpanPemesanan">
                    <span id="btnText">Simpan Perubahan</span>
                    <span id="loadingIndicator" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="modalBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 px-0">
                <div class="px-3 py-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Tampilkan</span>
                        <select class="form-select form-select-sm" id="itemsPerPageBarang" style="width: auto">
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="ms-2">entri</span>
                    </div>
                    <div>
                        <input type="text" class="form-control form-control-sm" placeholder="Cari barang..." id="searchBarangSimple">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover" style="min-width: 100%;">
                        <thead>
                            <tr class="border-top border-bottom bg-light">
                                <th class="py-2 px-3">No</th>
                                <th class="py-2">Nama Barang</th>
                                <th class="py-2">Satuan</th>
                                <th class="py-2">Stok</th>
                                <th class="py-2">Harga Sewa</th>
                                <th class="py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top p-3">
                    <small class="text-muted" id="barangTableInfo">Menampilkan 1 sampai 1 dari 1 entri</small>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" id="prevPageBarang">Sebelumnya</button>
                        <button class="btn btn-primary" id="currentPageBarang">1</button>
                        <button class="btn btn-outline-secondary" id="nextPageBarang">Selanjutnya</button>
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
        // Initial setup
        updateGrandTotal();

        // Variabel untuk menyimpan data barang
        let allBarang = [];
        let filteredBarang = [];
        let currentPageBarang = 1;
        let itemsPerPageBarang = 10;

        // Fungsi untuk memuat data barang
        function loadBarang() {
            $.ajax({
                url: '<?= site_url('admin/pemesananbarang/getBarang') ?>',
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#barangTableBody').html('<tr><td colspan="6" class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                },
                success: function(response) {
                    if (response && response.data) {
                        allBarang = response.data;
                        filteredBarang = [...allBarang];
                        renderBarang();
                    }
                },
                error: function(xhr, status, error) {
                    $('#barangTableBody').html('<tr><td colspan="6" class="text-center py-3 text-danger">Gagal memuat data barang</td></tr>');
                    console.error('Error loading data:', error);
                }
            });
        }

        // Fungsi untuk render data barang
        function renderBarang() {
            const startIndex = (currentPageBarang - 1) * itemsPerPageBarang;
            const endIndex = Math.min(startIndex + itemsPerPageBarang, filteredBarang.length);
            const displayedBarang = filteredBarang.slice(startIndex, endIndex);

            $('#barangTableBody').empty();

            if (displayedBarang.length === 0) {
                $('#barangTableBody').append('<tr><td colspan="6" class="text-center py-3">Tidak ada data</td></tr>');
                $('#barangTableInfo').text('Menampilkan 0 entri');
            } else {
                displayedBarang.forEach((item, index) => {
                    $('#barangTableBody').append(`
                        <tr>
                            <td class="px-3">${startIndex + index + 1}</td>
                            <td>${item.namabarang || '-'}</td>
                            <td>${item.satuan || '-'}</td>
                            <td>${item.jumlah || '0'}</td>
                            <td>${formatRupiah(item.hargasewa)}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary simple-pilih-barang"
                                    data-id="${item.kdbarang}"
                                    data-nama="${item.namabarang || ''}"
                                    data-harga="${item.hargasewa}"
                                    data-satuan="${item.satuan || ''}"
                                    data-stok="${item.jumlah || 0}">
                                    <i class="bx bx-check"></i> Pilih
                                </button>
                            </td>
                        </tr>
                    `);
                });

                $('#barangTableInfo').text(`Menampilkan ${startIndex + 1} sampai ${endIndex} dari ${filteredBarang.length} entri`);
            }

            // Update pagination
            updatePaginationBarang();
        }

        // Fungsi untuk update pagination barang
        function updatePaginationBarang() {
            const totalPages = Math.ceil(filteredBarang.length / itemsPerPageBarang);

            $('#currentPageBarang').text(currentPageBarang);
            $('#prevPageBarang').prop('disabled', currentPageBarang === 1);
            $('#nextPageBarang').prop('disabled', currentPageBarang >= totalPages);

            if (currentPageBarang === 1) {
                $('#prevPageBarang').addClass('disabled');
            } else {
                $('#prevPageBarang').removeClass('disabled');
            }

            if (currentPageBarang >= totalPages) {
                $('#nextPageBarang').addClass('disabled');
            } else {
                $('#nextPageBarang').removeClass('disabled');
            }
        }

        // Event handler untuk pencarian barang
        $('#searchBarangSimple').on('keyup', function() {
            const keyword = $(this).val().toLowerCase();

            if (keyword === '') {
                filteredBarang = [...allBarang];
            } else {
                filteredBarang = allBarang.filter(item => {
                    return (item.namabarang && item.namabarang.toLowerCase().includes(keyword)) ||
                        (item.satuan && item.satuan.toLowerCase().includes(keyword));
                });
            }

            currentPageBarang = 1;
            renderBarang();
        });

        // Event handler untuk pagination barang
        $('#prevPageBarang').on('click', function() {
            if (currentPageBarang > 1) {
                currentPageBarang--;
                renderBarang();
            }
        });

        $('#nextPageBarang').on('click', function() {
            const totalPages = Math.ceil(filteredBarang.length / itemsPerPageBarang);
            if (currentPageBarang < totalPages) {
                currentPageBarang++;
                renderBarang();
            }
        });

        // Event handler untuk itemsPerPage barang
        $('#itemsPerPageBarang').on('change', function() {
            itemsPerPageBarang = parseInt($(this).val());
            currentPageBarang = 1;
            renderBarang();
        });

        // Event handler untuk pilih barang
        $(document).on('click', '.simple-pilih-barang', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');
            const satuan = $(this).data('satuan');
            const stok = $(this).data('stok');

            // Periksa apakah barang sudah ada di tabel
            let barangAda = false;
            $('#tableBarang tbody tr').each(function() {
                const kdbarang = $(this).find('.kdbarang').val();
                if (kdbarang == id) {
                    barangAda = true;
                    // Tambahkan jumlah jika barang sudah ada
                    const jumlahInput = $(this).find('.jumlah');
                    const currentJumlah = parseInt(jumlahInput.val());
                    jumlahInput.val(currentJumlah + 1);
                    jumlahInput.trigger('change');
                    return false; // Break the loop
                }
            });

            if (!barangAda) {
                // Hapus placeholder row jika ada
                if ($('#tableBarang tbody tr td[colspan="5"]').length > 0) {
                    $('#tableBarang tbody').empty();
                }

                // Tambahkan barang baru
                $('#tableBarang tbody').append(`
                    <tr>
                        <td>
                            ${nama}
                            <input type="hidden" name="kdbarang[]" value="${id}" class="kdbarang">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm harga" name="harga[]" value="${harga}" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm jumlah" name="jumlah[]" value="1" min="1" data-stok="${stok}">
                            <input type="hidden" class="stoktersedia" value="${stok}">
                        </td>
                        <td>
                            <input type="text" class="form-control form-control-sm subtotal" name="subtotal[]" value="${harga}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger btn-hapus-barang">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);

                // Trigger update grand total
                updateGrandTotal();
            }

            $('#modalBarang').modal('hide');
        });

        // Event untuk ubah jumlah
        $(document).on('change keyup', '.jumlah', function() {
            const row = $(this).closest('tr');
            const harga = parseFloat(row.find('.harga').val());
            const jumlah = parseInt($(this).val());
            const stok = parseInt($(this).data('stok'));

            // Validasi jumlah tidak boleh kurang dari 1
            if (jumlah < 1) {
                $(this).val(1);
            }

            // Validasi jumlah tidak boleh melebihi stok
            if (jumlah > stok) {
                $(this).val(stok);
                alert(`Stok barang tidak mencukupi. Stok tersedia: ${stok}`);
            }

            // Hitung subtotal
            const newJumlah = parseInt($(this).val());
            const subtotal = harga * newJumlah;
            row.find('.subtotal').val(subtotal);

            // Update grand total
            updateGrandTotal();
        });

        // Event untuk hapus barang
        $(document).on('click', '.btn-hapus-barang', function() {
            $(this).closest('tr').remove();
            updateGrandTotal();
        });

        // Tampilkan modal barang saat tombol diklik
        $('#btnTambahBarang').on('click', function() {
            loadBarang();
            $('#modalBarang').modal('show');
        });

        // Validasi form sebelum submit
        $('#formPemesanan').on('submit', function(e) {
            e.preventDefault(); // Mencegah form submit secara normal

            // Validasi minimal harus ada barang
            if ($('#tableBarang tbody tr').length === 0 || $('#tableBarang tbody tr td[colspan="5"]').length > 0) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Tambahkan minimal 1 barang ke daftar pemesanan',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Validasi stok sebelum submit
            let stokValid = true;
            let barangTidakCukup = [];

            $('#tableBarang tbody tr').each(function() {
                const namaBarang = $(this).find('td:first').text().trim();
                const jumlah = parseInt($(this).find('.jumlah').val());
                const stok = parseInt($(this).find('.stoktersedia').val());

                if (jumlah > stok) {
                    stokValid = false;
                    barangTidakCukup.push(`${namaBarang} (diminta: ${jumlah}, tersedia: ${stok})`);
                }
            });

            if (!stokValid) {
                Swal.fire({
                    title: 'Stok Tidak Cukup!',
                    html: `Beberapa barang tidak memiliki stok yang cukup:<br><ul><li>${barangTidakCukup.join('</li><li>')}</li></ul>`,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            // Tambahkan CSRF token ke data yang akan dikirim
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            let formData = $(this).serialize();
            formData += '&' + csrfName + '=' + csrfHash;

            // Tampilkan indikator loading dan nonaktifkan tombol
            $('#btnText').addClass('d-none');
            $('#loadingIndicator').removeClass('d-none');
            $('#btnSimpanPemesanan').prop('disabled', true);

            // Submit form
            this.submit();
        });

        // Fungsi untuk update grand total
        function updateGrandTotal() {
            // Remove the "Belum ada barang yang dipilih" message if it exists
            if ($('#tableBarang tbody tr td[colspan="5"]').length > 0) {
                $('#tableBarang tbody').empty();
                return; // Exit early as we've just cleared the placeholder row
            }

            // Calculate total from actual item rows
            let grandTotal = 0;
            $('.subtotal').each(function() {
                grandTotal += parseFloat($(this).val());
            });

            $('#grandTotal').html(formatRupiah(grandTotal));
            $('#grandtotal_input').val(grandTotal);

            // Add placeholder row if there are no items
            if ($('#tableBarang tbody tr').length === 0) {
                $('#tableBarang tbody').html('<tr><td colspan="5" class="text-center">Belum ada barang yang dipilih</td></tr>');
            }
        }

        // Format rupiah dengan parameter untuk menghilangkan 'Rp '
        function formatRupiah(angka, withPrefix = true) {
            if (!angka) return withPrefix ? 'Rp 0' : '0';
            const formatted = parseFloat(angka).toLocaleString('id-ID');
            return withPrefix ? 'Rp ' + formatted : formatted;
        }
    });
</script>

<!-- Debug info - hidden by default -->
<!-- <?php if (ENVIRONMENT === 'development'): ?>
    <div class="mt-5 card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Debug Info (Development Only)</h5>
        </div>
        <div class="card-body">
            <h6>Payment Data:</h6>
            <pre><?php
                    echo "kdpembayaran: " . (isset($pemesanan['kdpembayaran']) ? $pemesanan['kdpembayaran'] : 'NULL') . "\n";
                    echo "statuspembayaran: " . (isset($pemesanan['statuspembayaran']) ? $pemesanan['statuspembayaran'] : 'NULL') . "\n";
                    echo "tipepembayaran: " . (isset($pemesanan['tipepembayaran']) ? $pemesanan['tipepembayaran'] : 'NULL') . "\n";
                    echo "metodepembayaran: " . (isset($pemesanan['metodepembayaran']) ? $pemesanan['metodepembayaran'] : 'NULL') . "\n";
                    echo "jumlahbayar: " . (isset($pemesanan['jumlahbayar']) ? $pemesanan['jumlahbayar'] : 'NULL') . "\n";
                    ?></pre>
        </div>
    </div>
<?php endif; ?> -->
<?= $this->endSection() ?>
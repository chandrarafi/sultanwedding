<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananbarang') ?>">Pemesanan Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Pemesanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Form Pemesanan Barang</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/pemesananbarang') ?>" class="btn btn-secondary px-3 radius-30">
                    <i class="bx bx-arrow-back"></i>Kembali
                </a>
            </div>
        </div>

        <form action="<?= site_url('admin/pemesananbarang/store') ?>" method="post" id="formPemesanan">
            <?= csrf_field() ?>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal Pemesanan</label>
                        <input type="date" class="form-control" id="tgl" name="tgl" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kdpelanggan" class="form-label">Pelanggan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="namapelanggan" placeholder="Pilih Pelanggan" readonly required>
                            <input type="hidden" id="kdpelanggan" name="kdpelanggan" required>
                            <button class="btn btn-primary" type="button" id="btnPilihPelanggan">Pilih</button>
                        </div>
                        <small class="text-danger">Pelanggan wajib dipilih</small>
                    </div>
                    <div class="mb-3">
                        <label for="alamatpesanan" class="form-label">Alamat Pengiriman</label>
                        <textarea class="form-control" id="alamatpesanan" name="alamatpesanan" rows="3"></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lamapemesanan" class="form-label">Lama Pemesanan (hari)</label>
                        <input type="number" class="form-control" id="lamapemesanan" name="lamapemesanan" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_paid" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="is_paid" name="is_paid">
                            <option value="1">Langsung Lunas</option>
                            <option value="2">Bayar DP (50%)</option>
                            <option value="0">Belum Bayar</option>
                        </select>
                    </div>
                    <div class="mb-3" id="metodePembayaranContainer">
                        <label for="metodepembayaran" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="metodepembayaran" name="metodepembayaran">
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div class="mb-3" id="dpAmountContainer" style="display: none;">
                        <label for="dp_amount" class="form-label">Jumlah DP (50%)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="dp_amount" name="dp_amount" readonly>
                        </div>
                    </div>
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
                            <!-- Detail barang akan ditambahkan di sini -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th id="grandTotal">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <input type="hidden" name="grandtotal" id="grandtotal_input">
            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-5" id="btnSimpanPemesanan">
                    <span id="btnText">Simpan Pemesanan</span>
                    <span id="loadingIndicator" class="d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Status -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusTitle">Status Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="statusMessage">
                <!-- Status message will be shown here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="<?= site_url('admin/pemesananbarang') ?>" class="btn btn-primary" id="btnKembali">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Pelanggan -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title">Pilih Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 px-0">
                <div class="px-3 py-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari nama pelanggan..." id="searchPelangganSimple">
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover" style="min-width: 100%;">
                        <thead>
                            <tr class="border-top border-bottom bg-light">
                                <th class="py-2 px-3">No</th>
                                <th class="py-2">Nama</th>
                                <th class="py-2">Alamat</th>
                                <th class="py-2">No HP</th>
                                <th class="py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pelangganTableBody">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top p-3">
                    <small class="text-muted" id="pelangganTableInfo">Menampilkan 1 sampai 1 dari 1 entri</small>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" id="prevPage">Sebelumnya</button>
                        <button class="btn btn-primary" id="currentPage">1</button>
                        <button class="btn btn-outline-secondary" id="nextPage">Selanjutnya</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
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
        // Add placeholder row if table is empty
        if ($('#tableBarang tbody tr').length === 0) {
            $('#tableBarang tbody').html('<tr><td colspan="5" class="text-center">Belum ada barang yang dipilih</td></tr>');
        }

        // Initial setup
        updateGrandTotal();

        // Trigger the change event to setup initial state
        $('#is_paid').trigger('change');

        // Variabel untuk menyimpan data pelanggan
        let allPelanggan = [];
        let filteredPelanggan = [];
        let currentPage = 1;
        let itemsPerPage = 10;

        // Fungsi untuk memuat data pelanggan
        function loadPelanggan() {
            $.ajax({
                url: '<?= site_url('admin/pemesananbarang/getPelanggan') ?>',
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#pelangganTableBody').html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
                },
                success: function(response) {
                    if (response && response.data) {
                        allPelanggan = response.data;
                        filteredPelanggan = [...allPelanggan];
                        renderPelanggan();
                    }
                },
                error: function(xhr, status, error) {
                    $('#pelangganTableBody').html('<tr><td colspan="5" class="text-center py-3 text-danger">Gagal memuat data pelanggan</td></tr>');
                    console.error('Error loading data:', error);
                }
            });
        }

        // Fungsi untuk render data pelanggan
        function renderPelanggan() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredPelanggan.length);
            const displayedPelanggan = filteredPelanggan.slice(startIndex, endIndex);

            $('#pelangganTableBody').empty();

            if (displayedPelanggan.length === 0) {
                $('#pelangganTableBody').append('<tr><td colspan="5" class="text-center py-3">Tidak ada data</td></tr>');
                $('#pelangganTableInfo').text('Menampilkan 0 entri');
            } else {
                displayedPelanggan.forEach((item, index) => {
                    $('#pelangganTableBody').append(`
                        <tr>
                            <td class="px-3">${startIndex + index + 1}</td>
                            <td>${item.namapelanggan || '-'}</td>
                            <td>${item.alamat || '-'}</td>
                            <td>${item.nohp || '-'}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary simple-pilih-pelanggan"
                                    data-id="${item.kdpelanggan}"
                                    data-nama="${item.namapelanggan || ''}"
                                    data-alamat="${item.alamat || ''}">
                                    <i class="bx bx-check"></i> Pilih
                                </button>
                            </td>
                        </tr>
                    `);
                });

                $('#pelangganTableInfo').text(`Menampilkan ${startIndex + 1} sampai ${endIndex} dari ${filteredPelanggan.length} entri`);
            }

            // Update pagination
            updatePagination();
        }

        // Fungsi untuk update pagination
        function updatePagination() {
            const totalPages = Math.ceil(filteredPelanggan.length / itemsPerPage);

            $('#currentPage').text(currentPage);
            $('#prevPage').prop('disabled', currentPage === 1);
            $('#nextPage').prop('disabled', currentPage >= totalPages);

            // Pastikan tombol disabled memiliki style yang sesuai
            if (currentPage === 1) {
                $('#prevPage').addClass('disabled');
            } else {
                $('#prevPage').removeClass('disabled');
            }

            if (currentPage >= totalPages) {
                $('#nextPage').addClass('disabled');
            } else {
                $('#nextPage').removeClass('disabled');
            }
        }

        // Event handler untuk pencarian
        $('#searchPelangganSimple').on('keyup', function() {
            const keyword = $(this).val().toLowerCase();

            if (keyword === '') {
                filteredPelanggan = [...allPelanggan];
            } else {
                filteredPelanggan = allPelanggan.filter(item => {
                    return (item.namapelanggan && item.namapelanggan.toLowerCase().includes(keyword)) ||
                        (item.alamat && item.alamat.toLowerCase().includes(keyword)) ||
                        (item.nohp && item.nohp.toLowerCase().includes(keyword));
                });
            }

            currentPage = 1;
            renderPelanggan();
        });

        // Event handler untuk pagination
        $('#prevPage').on('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderPelanggan();
            }
        });

        $('#nextPage').on('click', function() {
            const totalPages = Math.ceil(filteredPelanggan.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderPelanggan();
            }
        });

        // Event handler untuk pilih pelanggan
        $(document).on('click', '.simple-pilih-pelanggan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const alamat = $(this).data('alamat');

            $('#kdpelanggan').val(id);
            $('#namapelanggan').val(nama);

            if (alamat && $('#alamatpesanan').val() === '') {
                $('#alamatpesanan').val(alamat);
            }

            $('#modalPelanggan').modal('hide');
        });

        // Tampilkan modal pelanggan saat tombol diklik
        $('#btnPilihPelanggan').on('click', function() {
            loadPelanggan();
            $('#modalPelanggan').modal('show');
        });

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

        // Event handler untuk jumlah item per halaman
        $('#itemsPerPageBarang').on('change', function() {
            itemsPerPageBarang = parseInt($(this).val());
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

        // Event handler untuk lama pemesanan
        $('#lamapemesanan').on('change', function() {
            // Update semua subtotal ketika lama pemesanan berubah
            updateAllSubtotals();
        });

        // Event handler untuk pilih barang
        $(document).on('click', '.simple-pilih-barang', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');
            const satuan = $(this).data('satuan');
            const stok = $(this).data('stok');

            console.log('Selected item:', {
                id,
                nama,
                harga,
                satuan,
                stok
            });

            // Validasi stok tersedia
            if (stok <= 0) {
                Swal.fire({
                    title: 'Stok Kosong!',
                    text: `Barang ${nama} tidak tersedia (stok 0)`,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Cek apakah barang sudah ada di tabel
            let isExists = false;
            $('#tableBarang tbody tr').each(function() {
                const existingId = $(this).find('.kdbarang').val();
                if (existingId == id) {
                    isExists = true;
                    return false; // break the loop
                }
            });

            if (isExists) {
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Barang ini sudah ditambahkan ke daftar',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Dapatkan lama pemesanan
            const lamaPemesanan = parseInt($('#lamapemesanan').val()) || 1;

            // Hitung subtotal (harga * lama pemesanan)
            const subtotal = parseFloat(harga) * lamaPemesanan;

            // Check if there's a placeholder row and remove it
            if ($('#tableBarang tbody tr td[colspan="5"]').length > 0) {
                $('#tableBarang tbody').empty();
            }

            // Tambahkan barang ke tabel
            const newRow = `
                <tr>
                    <td>
                        ${nama} ${satuan ? `(${satuan})` : ''}
                        <input type="hidden" name="kdbarang[]" class="kdbarang" value="${id}">
                        <input type="hidden" class="stoktersedia" value="${stok}">
                    </td>
                    <td>
                        ${formatRupiah(harga)}/hari
                        <input type="hidden" name="harga[]" class="harga" value="${harga}">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" name="jumlah[]" class="form-control jumlah" min="1" max="${stok}" value="1" required>
                            <span class="input-group-text">${satuan || 'Unit'}</span>
                        </div>
                        <small class="text-muted">Stok tersedia: ${stok}</small>
                    </td>
                    <td>
                        ${formatRupiah(subtotal)}
                        <input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-hapus-barang w-100">
                            <i class="bx bx-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            `;
            $('#tableBarang tbody').append(newRow);
            $('#modalBarang').modal('hide');

            // Update grand total
            updateGrandTotal();
        });

        // Update subtotal saat jumlah diubah
        $('#tableBarang').on('change', '.jumlah', function() {
            updateRowSubtotal($(this).closest('tr'));
        });

        // Fungsi untuk update subtotal satu baris
        function updateRowSubtotal(row) {
            const harga = parseFloat(row.find('.harga').val());
            const stokTersedia = parseInt(row.find('.stoktersedia').val());
            let jumlah = parseInt(row.find('.jumlah').val());

            // Dapatkan lama pemesanan
            const lamaPemesanan = parseInt($('#lamapemesanan').val()) || 1;

            // Validasi jumlah tidak boleh lebih dari stok
            if (jumlah > stokTersedia) {
                jumlah = stokTersedia;
                row.find('.jumlah').val(stokTersedia);

                Swal.fire({
                    title: 'Stok Tidak Cukup!',
                    text: `Jumlah yang dimasukkan melebihi stok tersedia (${stokTersedia})`,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            } else if (jumlah < 1) {
                jumlah = 1;
                row.find('.jumlah').val(1);
            }

            // Hitung subtotal (harga * jumlah * lama pemesanan)
            const subtotal = harga * jumlah * lamaPemesanan;

            row.find('.subtotal').val(subtotal);
            row.find('td:eq(3)').html(`${formatRupiah(subtotal)}<input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}">`);

            updateGrandTotal();
        }

        // Fungsi untuk update semua subtotal (ketika lama pemesanan berubah)
        function updateAllSubtotals() {
            $('#tableBarang tbody tr').each(function() {
                updateRowSubtotal($(this));
            });
        }

        // Tampilkan modal barang saat tombol diklik
        $('#btnTambahBarang').on('click', function() {
            loadBarang();
            $('#modalBarang').modal('show');
        });

        // Hapus barang dari tabel
        $('#tableBarang').on('click', '.btn-hapus-barang', function(e) {
            e.preventDefault(); // Prevent default action
            e.stopPropagation(); // Stop event propagation

            const row = $(this).closest('tr');
            const itemName = row.find('td:first').text().trim();

            // Confirm deletion
            Swal.fire({
                title: 'Hapus Barang?',
                text: `Apakah Anda yakin ingin menghapus barang "${itemName}" dari daftar?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Remove the row
                    row.remove();

                    // Update grand total
                    updateGrandTotal();

                    // Show message
                    Swal.fire(
                        'Terhapus!',
                        `Barang "${itemName}" telah dihapus dari daftar.`,
                        'success'
                    );
                }
            });
        });

        // Toggle metode pembayaran berdasarkan status pembayaran
        $('#is_paid').on('change', function() {
            const selectedValue = $(this).val();
            const dpAmountContainer = $('#dpAmountContainer');
            const metodePembayaranContainer = $('#metodePembayaranContainer');

            if (selectedValue === '1') {
                dpAmountContainer.hide();
                metodePembayaranContainer.show();
                $('#dp_amount').val(''); // Clear DP amount
            } else if (selectedValue === '2') {
                dpAmountContainer.show();
                metodePembayaranContainer.show(); // Show payment method for DP too
                // Update DP amount
                const grandTotal = parseFloat($('#grandtotal_input').val()) || 0;
                const dpAmount = grandTotal * 0.5;
                $('#dp_amount').val(formatRupiah(dpAmount, false));
            } else { // selectedValue === '0'
                dpAmountContainer.hide();
                metodePembayaranContainer.hide();
                $('#dp_amount').val(''); // Clear DP amount
            }
        });

        // Validasi form sebelum submit
        $('#formPemesanan').on('submit', function(e) {
            e.preventDefault(); // Mencegah form submit secara normal

            // Debug: log semua data form
            console.log('Form data:', $(this).serialize());

            // Tambahkan CSRF token ke data yang akan dikirim
            const csrfName = '<?= csrf_token() ?>';
            const csrfHash = '<?= csrf_hash() ?>';
            let formData = $(this).serialize();
            formData += '&' + csrfName + '=' + csrfHash;

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

            // Validasi pembayaran DP
            if ($('#is_paid').val() === '2') {
                // Ensure metodepembayaran is selected for DP
                if (!$('#metodepembayaran').val()) {
                    Swal.fire({
                        title: 'Perhatian!',
                        text: 'Pilih metode pembayaran untuk DP',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                // Add hidden field for DP payment type
                const dpAmountValue = parseFloat($('#grandtotal_input').val()) * 0.5;
                $('<input>').attr({
                    type: 'hidden',
                    name: 'payment_type',
                    value: 'dp'
                }).appendTo('#formPemesanan');

                $('<input>').attr({
                    type: 'hidden',
                    name: 'dp_amount',
                    value: dpAmountValue
                }).appendTo('#formPemesanan');
            }

            // Tampilkan indikator loading dan nonaktifkan tombol
            $('#btnText').addClass('d-none');
            $('#loadingIndicator').removeClass('d-none');
            $('#btnSimpanPemesanan').prop('disabled', true);

            // Kirim data menggunakan AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData, // Use the modified formData
                dataType: 'json',
                beforeSend: function() {
                    console.log('Mengirim request ke:', $(this).attr('action'));
                    console.log('Data yang dikirim:', formData); // Log the modified formData
                },
                success: function(response) {
                    console.log('Response:', response);
                    if (response.status === 'success') {
                        // Tampilkan pesan sukses
                        $('#statusTitle').text('Pemesanan Berhasil');
                        $('#statusMessage').html(`
                            <div class="alert alert-success mb-0">
                                <h4><i class="bx bx-check-circle"></i> Berhasil!</h4>
                                <p>${response.message}</p>
                                <p>No. Pemesanan: <strong>${response.kdpemesanan || '-'}</strong></p>
                                <p class="mt-3">Anda akan diarahkan ke halaman faktur dalam 3 detik...</p>
                            </div>
                        `);
                        $('#statusModal').modal('show');

                        // Redirect ke halaman lihat faktur setelah 3 detik
                        if (response.redirect_url) {
                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 3000);
                        }
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan saat menyimpan pemesanan',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });

                        // Kembalikan tombol ke keadaan semula
                        $('#btnText').removeClass('d-none');
                        $('#loadingIndicator').addClass('d-none');
                        $('#btnSimpanPemesanan').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    // Tampilkan pesan error
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan pemesanan: ' + error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });

                    // Kembalikan tombol ke keadaan semula
                    $('#btnText').removeClass('d-none');
                    $('#loadingIndicator').addClass('d-none');
                    $('#btnSimpanPemesanan').prop('disabled', false);
                }
            });
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

            // Update DP amount (50% of grand total)
            const dpAmount = grandTotal * 0.5;
            $('#dp_amount').val(formatRupiah(dpAmount, false));

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
<?= $this->endSection() ?>
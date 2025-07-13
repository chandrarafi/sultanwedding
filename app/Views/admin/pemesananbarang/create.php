<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pemesanan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/pemesananbarang') ?>">Pemesanan Barang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Pemesanan (Walk-in)</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Form Pemesanan Barang (Walk-in)</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin/pemesananbarang') ?>" class="btn btn-secondary px-3 radius-30">
                    <i class="bx bx-arrow-back"></i>Kembali
                </a>
            </div>
        </div>

        <form action="<?= site_url('admin/pemesananbarang/store') ?>" method="post" id="formPemesanan">
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal Pemesanan</label>
                        <input type="date" class="form-control" id="tgl" name="tgl" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="kdpelanggan" class="form-label">Pelanggan</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="namapelanggan" placeholder="Pilih Pelanggan" readonly>
                            <input type="hidden" id="kdpelanggan" name="kdpelanggan">
                            <button class="btn btn-primary" type="button" id="btnPilihPelanggan">Pilih</button>
                        </div>
                        <small class="text-muted">Kosongkan jika pelanggan walk-in</small>
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
                                <th width="40%">Nama Barang</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Jumlah</th>
                                <th width="20%">Subtotal</th>
                                <th width="10%">Aksi</th>
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

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-5">Simpan Pemesanan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pilih Pelanggan -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tablePelanggan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>No HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data pelanggan akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="modalBarang" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableModalBarang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Harga Sewa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data barang akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable untuk pelanggan
        const tablePelanggan = $('#tablePelanggan').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= site_url('admin/pemesananbarang/getPelanggan') ?>',
                dataSrc: function(json) {
                    return json.data || [];
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'namapelanggan'
                },
                {
                    data: 'alamat'
                },
                {
                    data: 'nohp'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button type="button" class="btn btn-sm btn-primary btn-pilih-pelanggan" 
                                data-id="${row.kdpelanggan}" 
                                data-nama="${row.namapelanggan}">
                                Pilih</button>`;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
            },
        });

        // Inisialisasi DataTable untuk barang
        const tableModalBarang = $('#tableModalBarang').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: '<?= site_url('admin/pemesananbarang/getBarang') ?>',
                dataSrc: function(json) {
                    return json.data || [];
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'namabarang'
                },
                {
                    data: 'satuan'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'hargasewa',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button type="button" class="btn btn-sm btn-primary btn-pilih-barang" 
                                data-id="${row.kdbarang}" 
                                data-nama="${row.namabarang}"
                                data-harga="${row.hargasewa}"
                                data-satuan="${row.satuan}"
                                data-stok="${row.jumlah}">
                                Pilih</button>`;
                    }
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
            },
        });

        // Tampilkan modal pelanggan saat tombol diklik
        $('#btnPilihPelanggan').on('click', function() {
            $('#modalPelanggan').modal('show');
        });

        // Pilih pelanggan dari modal
        $('#tablePelanggan').on('click', '.btn-pilih-pelanggan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#kdpelanggan').val(id);
            $('#namapelanggan').val(nama);
            $('#modalPelanggan').modal('hide');
        });

        // Tampilkan modal barang saat tombol diklik
        $('#btnTambahBarang').on('click', function() {
            $('#modalBarang').modal('show');
        });

        // Pilih barang dari modal
        $('#tableModalBarang').on('click', '.btn-pilih-barang', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');
            const satuan = $(this).data('satuan');
            const stok = $(this).data('stok');

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

            // Tambahkan barang ke tabel
            const rowCount = $('#tableBarang tbody tr').length;
            const newRow = `
                <tr>
                    <td>
                        ${nama}
                        <input type="hidden" name="kdbarang[]" class="kdbarang" value="${id}">
                    </td>
                    <td>
                        ${formatRupiah(harga)}
                        <input type="hidden" name="harga[]" class="harga" value="${harga}">
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control jumlah" min="1" max="${stok}" value="1" required>
                    </td>
                    <td>
                        ${formatRupiah(harga)}
                        <input type="hidden" name="subtotal[]" class="subtotal" value="${harga}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus-barang">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#tableBarang tbody').append(newRow);
            $('#modalBarang').modal('hide');

            // Update grand total
            updateGrandTotal();
        });

        // Hapus barang dari tabel
        $('#tableBarang').on('click', '.btn-hapus-barang', function() {
            $(this).closest('tr').remove();
            updateGrandTotal();
        });

        // Update subtotal saat jumlah diubah
        $('#tableBarang').on('change', '.jumlah', function() {
            const row = $(this).closest('tr');
            const harga = parseFloat(row.find('.harga').val());
            const jumlah = parseInt($(this).val());
            const subtotal = harga * jumlah;

            row.find('.subtotal').val(subtotal);
            row.find('td:eq(3)').html(`${formatRupiah(subtotal)}<input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}">`);

            updateGrandTotal();
        });

        // Toggle metode pembayaran berdasarkan status pembayaran
        $('#is_paid').on('change', function() {
            if ($(this).val() === '1') {
                $('#metodePembayaranContainer').show();
            } else {
                $('#metodePembayaranContainer').hide();
            }
        });

        // Validasi form sebelum submit
        $('#formPemesanan').on('submit', function(e) {
            if ($('#tableBarang tbody tr').length === 0) {
                e.preventDefault();
                Swal.fire({
                    title: 'Perhatian!',
                    text: 'Tambahkan minimal 1 barang ke daftar pemesanan',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        });

        // Fungsi untuk update grand total
        function updateGrandTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).val());
            });

            $('#grandTotal').text(formatRupiah(total));
        }

        // Format rupiah
        function formatRupiah(angka) {
            if (!angka) return 'Rp 0';
            return 'Rp ' + parseFloat(angka).toLocaleString('id-ID');
        }
    });
</script>
<?= $this->endSection() ?>
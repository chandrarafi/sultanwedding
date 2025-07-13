<?= $this->extend('pelanggan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pemesanan Barang</h1>
                <p class="mt-2 text-gray-600">Silahkan isi formulir pemesanan barang di bawah ini</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="<?= site_url('pelanggan/pemesananbarang/daftar') ?>" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Pemesanan
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form action="<?= site_url('pelanggan/pemesananbarang/store') ?>" method="post" id="formPemesanan">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tgl" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pemesanan</label>
                        <input type="date" id="tgl" name="tgl" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div>
                        <label for="lamapemesanan" class="block text-sm font-medium text-gray-700 mb-1">Lama Pemesanan (hari)</label>
                        <input type="number" id="lamapemesanan" name="lamapemesanan" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" min="1" value="1" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamatpesanan" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pengiriman</label>
                        <textarea id="alamatpesanan" name="alamatpesanan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" required></textarea>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Detail Barang</h3>
                        <button type="button" id="btnTambahBarang" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Barang
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="tableBarang">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (isset($selected_barang)): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= $selected_barang['namabarang'] ?>
                                            <input type="hidden" name="kdbarang[]" value="<?= $selected_barang['kdbarang'] ?>">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp <?= number_format($selected_barang['hargasewa'], 0, ',', '.') ?>
                                            <input type="hidden" name="harga[]" class="harga" value="<?= $selected_barang['hargasewa'] ?>">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <input type="number" name="jumlah[]" class="jumlah w-20 px-2 py-1 border border-gray-300 rounded-md" min="1" max="<?= $selected_barang['jumlah'] ?>" value="1" required>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 subtotal-display">
                                            Rp <?= number_format($selected_barang['hargasewa'], 0, ',', '.') ?>
                                            <input type="hidden" name="subtotal[]" class="subtotal" value="<?= $selected_barang['hargasewa'] ?>">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <button type="button" class="btn-hapus-barang text-red-600 hover:text-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50">
                                    <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total</th>
                                    <th id="grandTotal" class="px-6 py-3 text-left text-sm font-medium text-gray-900">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md font-medium">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div id="modalBarang" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Pilih Barang
                        </h3>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="tableModalBarang">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Satuan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Sewa</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($barang as $index => $item): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['namabarang'] ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['satuan'] ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['jumlah'] ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($item['hargasewa'], 0, ',', '.') ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <button type="button" class="btn-pilih-barang bg-primary-600 hover:bg-primary-700 text-white py-1 px-3 rounded-md text-sm"
                                                    data-id="<?= $item['kdbarang'] ?>"
                                                    data-nama="<?= $item['namabarang'] ?>"
                                                    data-harga="<?= $item['hargasewa'] ?>"
                                                    data-satuan="<?= $item['satuan'] ?>"
                                                    data-stok="<?= $item['jumlah'] ?>">
                                                    Pilih
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="btnTutupModal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi DataTable untuk modal barang
        const tableModalBarang = new DataTable('#tableModalBarang', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
            },
        });

        // Tampilkan modal barang saat tombol diklik
        document.getElementById('btnTambahBarang').addEventListener('click', function() {
            document.getElementById('modalBarang').classList.remove('hidden');
        });

        // Tutup modal barang
        document.getElementById('btnTutupModal').addEventListener('click', function() {
            document.getElementById('modalBarang').classList.add('hidden');
        });

        // Pilih barang dari modal
        document.querySelectorAll('.btn-pilih-barang').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const harga = this.getAttribute('data-harga');
                const satuan = this.getAttribute('data-satuan');
                const stok = this.getAttribute('data-stok');

                // Cek apakah barang sudah ada di tabel
                let isExists = false;
                document.querySelectorAll('#tableBarang tbody tr').forEach(row => {
                    const existingId = row.querySelector('input[name="kdbarang[]"]').value;
                    if (existingId == id) {
                        isExists = true;
                        return;
                    }
                });

                if (isExists) {
                    alert('Barang ini sudah ditambahkan ke daftar');
                    return;
                }

                // Tambahkan barang ke tabel
                const tbody = document.querySelector('#tableBarang tbody');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${nama}
                        <input type="hidden" name="kdbarang[]" value="${id}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp ${formatRupiah(harga)}
                        <input type="hidden" name="harga[]" class="harga" value="${harga}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <input type="number" name="jumlah[]" class="jumlah w-20 px-2 py-1 border border-gray-300 rounded-md" min="1" max="${stok}" value="1" required>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 subtotal-display">
                        Rp ${formatRupiah(harga)}
                        <input type="hidden" name="subtotal[]" class="subtotal" value="${harga}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <button type="button" class="btn-hapus-barang text-red-600 hover:text-red-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </td>
                `;
                tbody.appendChild(newRow);
                document.getElementById('modalBarang').classList.add('hidden');

                // Update grand total
                updateGrandTotal();

                // Tambahkan event listener untuk jumlah
                newRow.querySelector('.jumlah').addEventListener('change', updateSubtotal);

                // Tambahkan event listener untuk hapus
                newRow.querySelector('.btn-hapus-barang').addEventListener('click', function() {
                    newRow.remove();
                    updateGrandTotal();
                });
            });
        });

        // Update subtotal saat jumlah diubah
        document.querySelectorAll('.jumlah').forEach(input => {
            input.addEventListener('change', updateSubtotal);
        });

        // Hapus barang dari tabel
        document.querySelectorAll('.btn-hapus-barang').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('tr').remove();
                updateGrandTotal();
            });
        });

        // Validasi form sebelum submit
        document.getElementById('formPemesanan').addEventListener('submit', function(e) {
            if (document.querySelectorAll('#tableBarang tbody tr').length === 0) {
                e.preventDefault();
                alert('Tambahkan minimal 1 barang ke daftar pemesanan');
                return false;
            }
            return true;
        });

        // Fungsi untuk update subtotal
        function updateSubtotal() {
            const row = this.closest('tr');
            const harga = parseFloat(row.querySelector('.harga').value);
            const jumlah = parseInt(this.value);
            const subtotal = harga * jumlah;

            row.querySelector('.subtotal').value = subtotal;
            row.querySelector('.subtotal-display').innerHTML = `Rp ${formatRupiah(subtotal)}<input type="hidden" name="subtotal[]" class="subtotal" value="${subtotal}">`;

            updateGrandTotal();
        }

        // Fungsi untuk update grand total
        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(input => {
                total += parseFloat(input.value);
            });

            document.getElementById('grandTotal').textContent = `Rp ${formatRupiah(total)}`;
        }

        // Format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Update grand total pada load
        updateGrandTotal();
    });
</script>
<?= $this->endSection() ?>
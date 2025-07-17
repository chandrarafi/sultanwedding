<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-serif font-bold text-secondary-800">Pemesanan Paket <?= $paket['namapaket'] ?></h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-serif font-bold text-secondary-800 mb-4">Detail Paket</h2>
                    <div class="space-y-4">
                        <?php if (!empty($paket['foto'])) : ?>
                            <img src="<?= base_url('uploads/paket/' . $paket['foto']) ?>" alt="<?= $paket['namapaket'] ?>" class="w-full h-48 object-cover rounded-lg mb-3">
                        <?php endif; ?>

                        <div class="space-y-3 divide-y divide-gray-200">
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Nama Paket</span>
                                <span><?= $paket['namapaket'] ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Harga Paket</span>
                                <span>Rp <?= number_format($paket['harga'], 0, ',', '.') ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Deskripsi</span>
                                <span class="text-right"><?= $paket['deskripsi'] ?? '-' ?></span>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 mt-4">
                            <h3 class="font-bold text-blue-800 mb-2">Ketentuan Pembayaran:</h3>
                            <ul class="space-y-1 text-blue-700 list-disc pl-5">
                                <li>DP 10% saat booking tanggal</li>
                                <li>10% lagi H-1 acara</li>
                                <li>Sisa pelunasan setelah acara selesai</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-serif font-bold text-secondary-800 mb-6">Form Pemesanan</h2>

                    <noscript>
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-md mb-4">
                            <p class="font-medium">Perhatian: JavaScript Tidak Aktif</p>
                            <p>Mohon aktifkan JavaScript di browser Anda untuk menggunakan form pemesanan ini dengan benar.</p>
                        </div>
                    </noscript>

                    <form id="formPemesanan" method="post" action="<?= site_url('pelanggan/pemesanan/process') ?>" class="space-y-6">
                        <input type="hidden" name="kdpaket" value="<?= $paket['kdpaket'] ?>">
                        <?= csrf_field() ?>

                        <div>
                            <label for="tgl" class="block text-sm font-medium text-secondary-700 mb-2">Tanggal Acara</label>
                            <div class="relative">
                                <input type="date" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 appearance-none p-2.5 text-gray-700"
                                    id="tgl" name="tgl" required min="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-red-500 text-xs mt-1 hidden" id="tgl-error"></div>
                            <p class="text-xs text-gray-500 mt-1">Minimal pemesanan 3 hari dari sekarang</p>
                        </div>

                        <div>
                            <label for="alamatpesanan" class="block text-sm font-medium text-secondary-700 mb-2">Alamat Lokasi Acara</label>
                            <textarea class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2.5 text-gray-700"
                                id="alamatpesanan" name="alamatpesanan" rows="3" required></textarea>
                            <div class="text-red-500 text-xs mt-1 hidden" id="alamatpesanan-error"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="jumlahhari" class="block text-sm font-medium text-secondary-700 mb-2">Jumlah Hari</label>
                                <input type="number" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2.5 text-gray-700"
                                    id="jumlahhari" name="jumlahhari" min="1" value="1" required>
                                <div class="text-red-500 text-xs mt-1 hidden" id="jumlahhari-error"></div>
                                <p class="text-xs text-blue-500 mt-1">Jika jumlah hari melebihi 4 hari, akan dikenakan biaya tambahan 10% dari harga paket.</p>
                            </div>
                            <div>
                                <label for="luaslokasi" class="block text-sm font-medium text-secondary-700 mb-2">Luas Lokasi</label>
                                <input type="text" class="w-full rounded-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 p-2.5 text-gray-700"
                                    id="luaslokasi" name="luaslokasi" placeholder="contoh: 10x15 meter" required>
                                <div class="text-red-500 text-xs mt-1 hidden" id="luaslokasi-error"></div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-secondary-700 mb-2">Metode Pembayaran DP</label>
                            <div class="flex gap-6 mt-1">
                                <label class="flex items-center">
                                    <input class="form-radio mr-2 h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="metodepembayaran" id="metodeTransfer" value="transfer" checked>
                                    <span class="text-gray-700">Transfer Bank</span>
                                </label>
                                <!-- <label class="flex items-center">
                                    <input class="form-radio mr-2 h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" type="radio" name="metodepembayaran" id="metodeCash" value="cash">
                                    <span class="text-gray-700">Cash</span>
                                </label> -->
                            </div>
                            <div class="text-red-500 text-xs mt-1 hidden" id="metodepembayaran-error"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">Total Harga</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">Rp</span>
                                    <input type="text" class="w-full rounded-r-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 p-2.5 text-gray-700"
                                        id="totalHarga" readonly value="<?= number_format($paket['harga'], 0, ',', '.') ?>">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-secondary-700 mb-2">DP (10%)</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">Rp</span>
                                    <input type="text" class="w-full rounded-r-md border border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 p-2.5 text-gray-700"
                                        id="dpAmount" readonly value="<?= number_format($paket['harga'] * 0.1, 0, ',', '.') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-4 border border-yellow-200 rounded-md">
                            <p class="text-yellow-800 text-sm">Dengan mengklik tombol "Pesan Sekarang", Anda setuju untuk melakukan pembayaran DP sebesar 10% dari total harga.</p>
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-pink-500 hover:bg-pink-600 text-white font-medium py-3 px-4 rounded-md shadow-sm transition duration-200 flex justify-center items-center"
                                id="btnSubmit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Pesan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Setup AJAX CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="<?= csrf_token() ?>"]').val(),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Hitung total harga dan DP saat jumlah hari berubah
        $('#jumlahhari').on('change input', function() {
            calculateTotal();
        });

        // Fungsi untuk menghitung total dan DP
        function calculateTotal() {
            const basePrice = <?= $paket['harga'] ?>;
            const jumlahHari = $('#jumlahhari').val() || 1;

            let total = basePrice;

            // Tambahkan biaya tambahan jika jumlah hari melebihi 4 hari
            if (jumlahHari > 4) {
                // Tambahkan 10% dari harga paket sebagai biaya tambahan
                const biayaTambahan = basePrice * 0.1;
                total = basePrice + biayaTambahan;
            }

            const dp = total * 0.1;

            $('#totalHarga').val(formatRupiah(total));
            $('#dpAmount').val(formatRupiah(dp));
        }

        // Format angka ke format rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Handle form submission
        $('#formPemesanan').submit(function(e) {
            e.preventDefault();

            // Reset validation errors
            $('.is-invalid').removeClass('is-invalid');
            $('.text-red-500').addClass('hidden');
            $('input, textarea').removeClass('border-red-500').addClass('border-gray-300');

            // Disable submit button
            $('#btnSubmit').prop('disabled', true);
            $('#btnSubmit').html('<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...');
            $('#btnSubmit').removeClass('bg-pink-500 hover:bg-pink-600').addClass('bg-pink-400');

            // Submit form via AJAX
            $.ajax({
                url: '<?= site_url('pelanggan/pemesanan/process') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        // Success
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            console.log('Redirecting to:', response.redirect);
                            window.location.href = response.redirect;
                        });
                    } else {
                        // Error
                        $('#btnSubmit').prop('disabled', false);
                        $('#btnSubmit').html('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> Pesan Sekarang');
                        $('#btnSubmit').removeClass('bg-pink-400').addClass('bg-pink-500 hover:bg-pink-600');

                        if (response.errors) {
                            // Display validation errors
                            $.each(response.errors, function(key, value) {
                                $('#' + key).removeClass('border-gray-300').addClass('border-red-500');
                                $('#' + key + '-error').removeClass('hidden').text(value);
                            });
                        } else {
                            // Display general error
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan',
                                text: response.message
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Enable submit button on error
                    $('#btnSubmit').prop('disabled', false);
                    $('#btnSubmit').html('<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> Pesan Sekarang');
                    $('#btnSubmit').removeClass('bg-pink-400').addClass('bg-pink-500 hover:bg-pink-600');

                    // Display error message
                    let errorMessage = 'Terjadi kesalahan saat memproses pemesanan';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: errorMessage,
                        confirmButtonText: 'Coba Lagi',
                        showCancelButton: true,
                        cancelButtonText: 'Submit Form Biasa'
                    }).then((result) => {
                        if (result.isDismissed) {
                            // Submit form normally if AJAX fails
                            $('#formPemesanan').off('submit').submit();
                        }
                    });

                    console.error('AJAX Error:', status, error);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
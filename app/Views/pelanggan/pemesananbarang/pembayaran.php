<?= $this->extend('pelanggan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pembayaran Pemesanan Barang</h1>
                <p class="mt-2 text-gray-600">ID Pemesanan: <?= $pemesanan['kdpemesananbarang'] ?></p>
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

        <!-- Status Pembayaran -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Status Pembayaran</h2>

            <div class="flex flex-wrap items-center mb-6">
                <div class="flex items-center mr-8 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center <?= (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] == 0) ? 'bg-blue-500' : 'bg-green-500' ?> text-white">
                        <?php if (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] == 0): ?>
                            <span class="font-bold">1</span>
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        <?php endif; ?>
                    </div>
                    <span class="ml-2 text-gray-700">DP (50%)</span>
                </div>
                <div class="flex items-center mr-8 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center <?= (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] == 0)) ? 'bg-blue-500' : ((isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1) ? 'bg-green-500' : 'bg-gray-300') ?> text-white">
                        <?php if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] == 0)): ?>
                            <span class="font-bold">2</span>
                        <?php elseif (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        <?php else: ?>
                            <span class="font-bold">2</span>
                        <?php endif; ?>
                    </div>
                    <span class="ml-2 text-gray-700">H-1 (25%)</span>
                </div>
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center <?= (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && (!isset($pemesanan['full_confirmed']) || $pemesanan['full_confirmed'] == 0)) ? 'bg-blue-500' : ((isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1) ? 'bg-green-500' : 'bg-gray-300') ?> text-white">
                        <?php if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && (!isset($pemesanan['full_confirmed']) || $pemesanan['full_confirmed'] == 0)): ?>
                            <span class="font-bold">3</span>
                        <?php elseif (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        <?php else: ?>
                            <span class="font-bold">3</span>
                        <?php endif; ?>
                    </div>
                    <span class="ml-2 text-gray-700">Pelunasan (25%)</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Pemesanan</h3>
                    <table class="w-full">
                        <tr>
                            <td class="py-2 text-gray-600">Tanggal Pemesanan</td>
                            <td class="py-2 text-gray-900">: <?= date('d F Y', strtotime($pemesanan['tgl'])) ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600">Lama Pemesanan</td>
                            <td class="py-2 text-gray-900">: <?= $pemesanan['lamapemesanan'] ?> hari</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600">Alamat Pengiriman</td>
                            <td class="py-2 text-gray-900">: <?= $pemesanan['alamatpesanan'] ?></td>
                        </tr>
                    </table>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Pembayaran</h3>
                    <table class="w-full">
                        <tr>
                            <td class="py-2 text-gray-600">Total Pembayaran</td>
                            <td class="py-2 text-gray-900">: Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600">Jumlah Dibayar</td>
                            <td class="py-2 text-gray-900">: Rp <?= isset($pemesanan['jumlahbayar']) ? number_format($pemesanan['jumlahbayar'], 0, ',', '.') : '0' ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600">Sisa Pembayaran</td>
                            <td class="py-2 text-gray-900">: Rp <?= isset($pemesanan['sisa']) ? number_format($pemesanan['sisa'], 0, ',', '.') : number_format($pemesanan['grandtotal'], 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detail Barang -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Detail Barang</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $no = 1;
                        foreach ($detail as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $no++ ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['namabarang'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $item['jumlah'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-900">Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Form Pembayaran -->
        <?php if (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] == 0): ?>
            <!-- Form Pembayaran DP -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pembayaran DP (50%)</h2>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Silahkan transfer DP sebesar <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.5, 0, ',', '.') ?></strong> ke rekening berikut:
                            </p>
                            <p class="text-sm text-yellow-700 mt-2">
                                <strong>Bank BCA</strong><br>
                                No. Rekening: 1234567890<br>
                                Atas Nama: Sultan Wedding
                            </p>
                        </div>
                    </div>
                </div>

                <form action="<?= site_url('pelanggan/pemesananbarang/bayar-dp/' . $pemesanan['kdpemesananbarang']) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran</label>
                        <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" accept="image/*" required>
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md font-medium">
                            Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>

        <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] == 0)): ?>
            <!-- Form Pembayaran H-1 -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pembayaran H-1 (25%)</h2>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Silahkan transfer pembayaran H-1 sebesar <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.25, 0, ',', '.') ?></strong> ke rekening berikut:
                            </p>
                            <p class="text-sm text-yellow-700 mt-2">
                                <strong>Bank BCA</strong><br>
                                No. Rekening: 1234567890<br>
                                Atas Nama: Sultan Wedding
                            </p>
                        </div>
                    </div>
                </div>

                <form action="<?= site_url('pelanggan/pemesananbarang/bayar-h1/' . $pemesanan['kdpemesananbarang']) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran</label>
                        <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" accept="image/*" required>
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md font-medium">
                            Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>

        <?php elseif (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && (!isset($pemesanan['full_confirmed']) || $pemesanan['full_confirmed'] == 0)): ?>
            <!-- Form Pelunasan -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Pelunasan (25%)</h2>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Silahkan transfer pelunasan sebesar <strong>Rp <?= number_format($pemesanan['sisa'], 0, ',', '.') ?></strong> ke rekening berikut:
                            </p>
                            <p class="text-sm text-yellow-700 mt-2">
                                <strong>Bank BCA</strong><br>
                                No. Rekening: 1234567890<br>
                                Atas Nama: Sultan Wedding
                            </p>
                        </div>
                    </div>
                </div>

                <form action="<?= site_url('pelanggan/pemesananbarang/bayar-pelunasan/' . $pemesanan['kdpemesananbarang']) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Pembayaran</label>
                        <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" accept="image/*" required>
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md font-medium">
                            Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>

        <?php else: ?>
            <!-- Pembayaran Selesai -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-900 mt-4">Pembayaran Selesai</h2>
                    <p class="text-gray-600 mt-2">Terima kasih! Pembayaran Anda telah selesai dan dikonfirmasi.</p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason'])): ?>
            <!-- Notifikasi Pembayaran Ditolak -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Pembayaran Ditolak</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Alasan: <?= $pemesanan['rejected_reason'] ?></p>
                                <p class="mt-1">Tanggal: <?= date('d F Y H:i', strtotime($pemesanan['rejected_at'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
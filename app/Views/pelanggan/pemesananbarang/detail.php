<?= $this->extend('pelanggan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pemesanan Barang</h1>
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

        <!-- Informasi Pemesanan -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Pemesanan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
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
                    <table class="w-full">
                        <tr>
                            <td class="py-2 text-gray-600">Status Pemesanan</td>
                            <td class="py-2">:
                                <?php if ($pemesanan['status'] == 'pending'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                <?php elseif ($pemesanan['status'] == 'process'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Diproses
                                    </span>
                                <?php elseif ($pemesanan['status'] == 'completed'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                <?php elseif ($pemesanan['status'] == 'cancelled'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Dibatalkan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-600">Status Pembayaran</td>
                            <td class="py-2">:
                                <?php if (isset($pemesanan['statuspembayaran'])): ?>
                                    <?php if ($pemesanan['statuspembayaran'] == 'pending'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Belum Bayar
                                        </span>
                                    <?php elseif ($pemesanan['statuspembayaran'] == 'partial'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            DP
                                        </span>
                                    <?php elseif ($pemesanan['statuspembayaran'] == 'confirmed'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Lunas
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Belum Ada Pembayaran
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
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

        <!-- Tombol Aksi -->
        <div class="flex justify-end">
            <a href="<?= site_url('pelanggan/pemesananbarang/pembayaran/' . $pemesanan['kdpemesananbarang']) ?>" class="bg-primary-600 hover:bg-primary-700 text-white py-2 px-6 rounded-md font-medium">
                Lihat Pembayaran
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-serif font-bold text-secondary-800">Pembayaran Paket <?= $pemesanan['namapaket'] ?></h1>

            <?php if (isset($pemesanan['status']) && $pemesanan['status'] === 'pending' && (empty($pemesanan['buktipembayaran']))): ?>
                <div id="timer-container" class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                    <p class="text-blue-700 font-medium mb-1">Batas Waktu Pembayaran:</p>
                    <div class="text-2xl font-bold text-blue-800" id="payment-timer" data-remaining-seconds="<?= isset($pemesanan['remaining_seconds']) ? $pemesanan['remaining_seconds'] : 300 ?>">05:00</div>
                    <p class="text-xs text-blue-600 mt-1">Pembayaran akan dibatalkan jika melewati batas waktu</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason'])): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-3">
                        <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-red-800">Pembayaran Ditolak</h3>
                        <p class="text-red-700 mb-2">Alasan: <?= $pemesanan['rejected_reason'] ?></p>

                        <?php if (!isset($pemesanan['dp_confirmed'])): ?>
                            <p class="text-red-700">Pemesanan Anda telah dibatalkan. Silakan lakukan pemesanan baru.</p>
                        <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] == 0)): ?>
                            <p class="text-red-700">Silakan upload ulang bukti pembayaran H-1.</p>
                        <?php elseif (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1): ?>
                            <p class="text-red-700">Silakan upload ulang bukti pelunasan.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-serif font-bold text-secondary-800 mb-4">Detail Pemesanan</h2>
                    <div class="space-y-4">
                        <?php if (!empty($pemesanan['fotopaket'])) : ?>
                            <img src="<?= base_url('uploads/paket/' . $pemesanan['fotopaket']) ?>" alt="<?= $pemesanan['namapaket'] ?>" class="w-full h-48 object-cover rounded-lg mb-3">
                        <?php endif; ?>

                        <div class="space-y-3 divide-y divide-gray-200">
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Kode Pemesanan</span>
                                <span class="font-bold text-primary-600">#<?= $pemesanan['kdpemesananpaket'] ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Tanggal Acara</span>
                                <span><?= date('d-m-Y', strtotime($pemesanan['tgl'])) ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Nama Paket</span>
                                <span><?= $pemesanan['namapaket'] ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Harga Paket</span>
                                <span>Rp <?= number_format($pemesanan['hargapaket'], 0, ',', '.') ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Jumlah Hari</span>
                                <span>
                                    <?= $pemesanan['jumlahhari'] ?> hari
                                    <?php if ($pemesanan['jumlahhari'] > 4) : ?>
                                        <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">Biaya tambahan 10%</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Luas Lokasi</span>
                                <span><?= $pemesanan['luaslokasi'] ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Alamat</span>
                                <span class="text-right"><?= $pemesanan['alamatpesanan'] ?></span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="font-medium text-secondary-700">Grand Total</span>
                                <span class="font-bold text-lg text-primary-600">
                                    Rp <?= number_format($pemesanan['grandtotal'], 0, ',', '.') ?>
                                    <?php if ($pemesanan['jumlahhari'] > 4) : ?>
                                        <div class="text-xs text-gray-500 mt-1 text-right">
                                            Harga Paket: Rp <?= number_format($pemesanan['hargapaket'], 0, ',', '.') ?><br>
                                            Biaya Tambahan (10%): Rp <?= number_format($pemesanan['hargapaket'] * 0.1, 0, ',', '.') ?>
                                        </div>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-serif font-bold text-secondary-800 mb-4">Informasi Pembayaran</h2>

                    <!-- Timeline Pembayaran -->
                    <div class="mb-6">
                        <h3 class="font-bold text-secondary-800 mb-3">Timeline Pembayaran</h3>
                        <div class="relative">
                            <!-- Timeline line -->
                            <div class="absolute h-full w-0.5 bg-gray-200 left-4"></div>

                            <!-- DP Booking -->
                            <div class="relative flex items-start mb-6 pl-12">
                                <div class="absolute left-0 rounded-full <?= ($pemesanan['statuspembayaran'] == 'pending' && empty($pemesanan['buktipembayaran'])) ? 'bg-yellow-500' : ((($pemesanan['statuspembayaran'] == 'pending' || $pemesanan['tipepembayaran'] == 'lunas') && !empty($pemesanan['buktipembayaran']) && !isset($pemesanan['dp_confirmed'])) ? 'bg-blue-500' : ((isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1) ? 'bg-green-500' : ((isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && !isset($pemesanan['dp_confirmed'])) ? 'bg-red-500' : 'bg-gray-300'))) ?> w-8 h-8 flex items-center justify-center text-white">
                                    <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && !isset($pemesanan['dp_confirmed'])): ?>
                                        <i class="fas fa-times"></i>
                                    <?php elseif ($pemesanan['statuspembayaran'] == 'pending' && empty($pemesanan['buktipembayaran'])): ?>
                                        <i class="fas fa-clock"></i>
                                    <?php elseif (($pemesanan['statuspembayaran'] == 'pending' || $pemesanan['tipepembayaran'] == 'lunas') && !empty($pemesanan['buktipembayaran']) && !isset($pemesanan['dp_confirmed'])): ?>
                                        <i class="fas fa-upload"></i>
                                    <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1): ?>
                                        <i class="fas fa-check"></i>
                                    <?php else: ?>
                                        <i class="fas fa-clock"></i>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-secondary-800">DP Booking (10%)</h4>
                                    <p class="text-sm text-secondary-600">
                                        Rp <?= number_format($pemesanan['grandtotal'] * 0.1, 0, ',', '.') ?>
                                    </p>
                                    <p class="text-xs text-secondary-500 mt-1">
                                        <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && !isset($pemesanan['dp_confirmed'])): ?>
                                            <span class="text-red-600">Pembayaran ditolak: <?= $pemesanan['rejected_reason'] ?></span>
                                        <?php elseif ($pemesanan['statuspembayaran'] == 'pending' && empty($pemesanan['buktipembayaran'])): ?>
                                            <span class="text-yellow-600">Menunggu pembayaran</span>
                                        <?php elseif (($pemesanan['statuspembayaran'] == 'pending' || $pemesanan['tipepembayaran'] == 'lunas') && !empty($pemesanan['buktipembayaran']) && (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] == 0)): ?>
                                            <span class="text-blue-600">Bukti pembayaran diunggah, menunggu konfirmasi admin</span>
                                        <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1): ?>
                                            <span class="text-green-600">Pembayaran telah dikonfirmasi</span>
                                        <?php else: ?>
                                            <span class="text-red-600">Pembayaran dibatalkan</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- H-1 (10%) -->
                            <div class="relative flex items-start mb-6 pl-12">
                                <div class="absolute left-0 rounded-full <?= $pemesanan['statuspembayaran'] == 'pending' ? 'bg-gray-300' : ((isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1) ? (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 ? 'bg-green-500' : ((isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && !isset($pemesanan['h1_confirmed'])) ? 'bg-red-500' : 'bg-blue-500')) : ((isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1) ? 'bg-yellow-500' : 'bg-gray-300')) ?> w-8 h-8 flex items-center justify-center text-white">
                                    <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 0): ?>
                                        <i class="fas fa-times"></i>
                                    <?php elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1 && isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1): ?>
                                        <i class="fas fa-check"></i>
                                    <?php elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1): ?>
                                        <i class="fas fa-upload"></i>
                                    <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1): ?>
                                        <i class="fas fa-clock"></i>
                                    <?php else: ?>
                                        <span class="text-xs">2</span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-secondary-800">Pembayaran H-1 (10%)</h4>
                                    <p class="text-sm text-secondary-600">
                                        Rp <?= number_format($pemesanan['grandtotal'] * 0.1, 0, ',', '.') ?>
                                    </p>
                                    <p class="text-xs text-secondary-500 mt-1">
                                        <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1 && isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 0): ?>
                                            <span class="text-red-600">Pembayaran ditolak: <?= $pemesanan['rejected_reason'] ?></span>
                                        <?php elseif ($pemesanan['statuspembayaran'] == 'pending'): ?>
                                            <span class="text-gray-500">Selesaikan pembayaran DP terlebih dahulu</span>
                                        <?php elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1 && isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1): ?>
                                            <span class="text-green-600">Pembayaran telah dikonfirmasi</span>
                                        <?php elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1): ?>
                                            <span class="text-blue-600">Bukti pembayaran diunggah, menunggu konfirmasi admin</span>
                                        <?php elseif (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1): ?>
                                            <span class="text-yellow-600">Menunggu pembayaran</span>
                                        <?php else: ?>
                                            <span class="text-gray-500">Belum tersedia</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Pelunasan (80%) -->
                            <div class="relative flex items-start pl-12">
                                <div class="absolute left-0 rounded-full <?= isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1 ? 'bg-green-500' : ((isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') || (isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === '1') ? 'bg-blue-500' : ((isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === '') ? 'bg-red-500' : ((isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1) ? 'bg-yellow-500' : 'bg-gray-300'))) ?> w-8 h-8 flex items-center justify-center text-white">
                                    <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === ''): ?>
                                        <i class="fas fa-times"></i>
                                    <?php elseif (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1): ?>
                                        <i class="fas fa-check"></i>
                                    <?php elseif ((isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') || (isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === '1')): ?>
                                        <i class="fas fa-upload"></i>
                                    <?php elseif (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1): ?>
                                        <i class="fas fa-clock"></i>
                                    <?php else: ?>
                                        <span class="text-xs">3</span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-bold text-secondary-800">Pelunasan (80%)</h4>
                                    <p class="text-sm text-secondary-600">
                                        Rp <?= number_format($pemesanan['grandtotal'] * 0.8, 0, ',', '.') ?>
                                    </p>
                                    <p class="text-xs text-secondary-500 mt-1">
                                        <?php if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === ''): ?>
                                            <span class="text-red-600">Pembayaran ditolak: <?= $pemesanan['rejected_reason'] ?></span>
                                        <?php elseif (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1): ?>
                                            <span class="text-green-600">Pembayaran telah dikonfirmasi</span>
                                        <?php elseif ((isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') || (isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === '1')): ?>
                                            <span class="text-blue-600">Bukti pembayaran diunggah, menunggu konfirmasi admin</span>
                                        <?php elseif (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1): ?>
                                            <span class="text-yellow-600">Menunggu pembayaran</span>
                                        <?php else: ?>
                                            <span class="text-gray-500">Belum tersedia</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <h3 class="font-bold text-blue-800 mb-2">Jadwal Pembayaran:</h3>
                            <ul class="space-y-2 text-blue-700">
                                <li class="flex justify-between">
                                    <span>DP 10% saat booking:</span>
                                    <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.1, 0, ',', '.') ?></strong>
                                </li>
                                <li class="flex justify-between">
                                    <span>10% lagi H-1 acara:</span>
                                    <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.1, 0, ',', '.') ?></strong>
                                </li>
                                <li class="flex justify-between">
                                    <span>Sisa pelunasan:</span>
                                    <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.8, 0, ',', '.') ?></strong>
                                </li>
                            </ul>
                        </div>

                        <!-- Status Pembayaran -->
                        <div class="border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 py-3 px-4 border-b border-gray-200">
                                <h3 class="font-bold text-secondary-800">Status Pembayaran</h3>
                            </div>
                            <div class="p-4">
                                <?php
                                $badgeClass = 'bg-yellow-500';
                                $statusText = 'Menunggu Pembayaran DP';

                                if (isset($pemesanan['statuspembayaran'])) {
                                    // First check for rejected payments
                                    if (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason'])) {
                                        if (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] == 0) {
                                            $badgeClass = 'bg-red-500';
                                            $statusText = 'Pembayaran DP Ditolak';
                                        } elseif (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1 && (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] == 0)) {
                                            $badgeClass = 'bg-red-500';
                                            $statusText = 'Pembayaran H-1 Ditolak';
                                        } elseif (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === '') {
                                            $badgeClass = 'bg-red-500';
                                            $statusText = 'Pelunasan Ditolak';
                                        }
                                    }
                                    // Then check for full payment confirmation
                                    else if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1) {
                                        $badgeClass = 'bg-green-500';
                                        $statusText = 'Lunas';
                                    }
                                    // Then check for full payment pending confirmation
                                    else if ((isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') ||
                                        (isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === '1')
                                    ) {
                                        $badgeClass = 'bg-blue-500';
                                        $statusText = 'Pelunasan Menunggu Konfirmasi Admin';
                                    }
                                    // Then check for H1 payment status
                                    else if (isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1) {
                                        if (isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1) {
                                            $badgeClass = 'bg-green-500';
                                            $statusText = 'Pembayaran H-1 Dikonfirmasi';
                                        } else {
                                            $badgeClass = 'bg-blue-500';
                                            $statusText = 'Pembayaran H-1 Menunggu Konfirmasi Admin';
                                        }
                                    }
                                    // Then check for DP status
                                    else if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1) {
                                        $badgeClass = 'bg-green-500';
                                        $statusText = 'DP Dibayar';
                                    }
                                    // Finally handle pending status
                                    else if ($pemesanan['statuspembayaran'] == 'pending') {
                                        if (!empty($pemesanan['buktipembayaran'])) {
                                            $badgeClass = 'bg-blue-500';
                                            $statusText = 'Menunggu Konfirmasi Admin';
                                        } else {
                                            $badgeClass = 'bg-yellow-500';
                                            $statusText = 'Menunggu Pembayaran DP';
                                        }
                                    } else {
                                        switch ($pemesanan['statuspembayaran']) {
                                            case 'success':
                                                $badgeClass = 'bg-green-500';
                                                $statusText = 'Lunas';
                                                break;
                                            case 'failed':
                                                $badgeClass = 'bg-red-500';
                                                $statusText = 'Gagal';
                                                break;
                                        }
                                    }
                                }

                                // Override status for full payment confirmation
                                if (isset($pemesanan['full_confirmed']) && $pemesanan['full_confirmed'] == 1) {
                                    $badgeClass = 'bg-green-500';
                                    $statusText = 'Lunas';
                                }
                                ?>
                                <div class="flex flex-wrap items-center gap-4 mb-4">
                                    <span class="px-3 py-1 text-white text-sm rounded-full <?= $badgeClass ?>"><?= $statusText ?></span>
                                    <span class="text-secondary-700">
                                        Total Dibayar: <strong>Rp <?= isset($pemesanan['totalpembayaran']) ? number_format($pemesanan['totalpembayaran'], 0, ',', '.') : '0' ?></strong>
                                    </span>
                                </div>

                                <div class="mb-4">
                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <?php
                                        $percent = 0;
                                        if (isset($pemesanan['totalpembayaran']) && isset($pemesanan['grandtotal']) && $pemesanan['grandtotal'] > 0) {
                                            $percent = ($pemesanan['totalpembayaran'] / $pemesanan['grandtotal']) * 100;
                                        }
                                        ?>
                                        <div class="bg-primary-600 h-4 rounded-full" style="width: <?= $percent ?>%"></div>
                                    </div>
                                    <div class="text-right text-xs mt-1"><?= number_format($percent, 0) ?>%</div>
                                </div>

                                <?php if (isset($pemesanan['sisa']) && $pemesanan['sisa'] > 0) : ?>
                                    <div class="font-medium">Sisa Pembayaran: <strong class="text-primary-600">Rp <?= number_format($pemesanan['sisa'], 0, ',', '.') ?></strong></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg">
                            <div class="bg-gray-50 py-3 px-4 border-b border-gray-200">
                                <h3 class="font-bold text-secondary-800">Rekening Pembayaran</h3>
                            </div>
                            <div class="p-4 space-y-4">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 text-blue-800 font-bold text-xl px-3 py-2 rounded mr-4">BCA</div>
                                    <div>
                                        <p class="text-sm text-secondary-600">Nomor Rekening:</p>
                                        <p class="font-bold text-secondary-800">1234567890</p>
                                        <p class="text-sm text-secondary-600 mt-1">Atas Nama:</p>
                                        <p class="font-bold text-secondary-800">Sultan Wedding</p>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="bg-green-100 text-green-800 font-bold text-xl px-3 py-2 rounded mr-4">BNI</div>
                                    <div>
                                        <p class="text-sm text-secondary-600">Nomor Rekening:</p>
                                        <p class="font-bold text-secondary-800">0987654321</p>
                                        <p class="text-sm text-secondary-600 mt-1">Atas Nama:</p>
                                        <p class="font-bold text-secondary-800">Sultan Wedding</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($pemesanan['statuspembayaran'])) : ?>
                            <?php if ($pemesanan['statuspembayaran'] == 'pending') : ?>
                                <!-- Form untuk pembayaran DP -->
                                <div class="border border-primary-200 rounded-lg overflow-hidden">
                                    <div class="bg-primary-600 py-3 px-4 text-white">
                                        <h3 class="font-bold">Pembayaran DP</h3>
                                    </div>
                                    <div class="p-4">
                                        <form id="formPaymentDP" action="<?= site_url('pelanggan/pemesanan/upload-bukti') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
                                            <input type="hidden" name="kdpemesanan" id="kdpemesanan" value="<?= $pemesanan['kdpemesananpaket'] ?>">
                                            <input type="hidden" name="metodepembayaran" value="<?= $pemesanan['metodepembayaran'] ?>">
                                            <?= csrf_field() ?>

                                            <div>
                                                <label class="font-medium mb-2 block">Metode Pembayaran</label>
                                                <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                                    <span class="font-medium"><?= ($pemesanan['metodepembayaran'] == 'transfer') ? 'Transfer Bank' : 'Cash' ?></span>
                                                </div>
                                            </div>

                                            <?php if ($pemesanan['metodepembayaran'] == 'transfer'): ?>
                                                <div id="bukti-pembayaran-container">
                                                    <label class="font-medium mb-2 block">Bukti Pembayaran</label>
                                                    <div class="border border-gray-300 rounded p-2">
                                                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="w-full" accept="image/*" required onchange="previewImage(this, 'preview-bukti-dp')">
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                                                    <div class="mt-2">
                                                        <div id="preview-bukti-dp" class="hidden mt-2">
                                                            <p class="text-sm font-medium mb-1">Preview:</p>
                                                            <div class="relative">
                                                                <img src="#" alt="Preview bukti pembayaran" class="max-h-60 rounded border border-gray-300">
                                                                <button type="button" onclick="removeImage('bukti_pembayaran', 'preview-bukti-dp')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="bg-yellow-50 p-4 border border-yellow-200 rounded">
                                                <p class="text-yellow-800">Silakan transfer DP sebesar <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.1, 0, ',', '.') ?></strong> ke rekening yang tertera di atas.</p>
                                            </div>

                                            <div>
                                                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition duration-200" id="btnPayDP">
                                                    <?php if ($pemesanan['metodepembayaran'] == 'transfer'): ?>
                                                        <i class="bx bx-upload mr-1"></i> Upload Bukti Pembayaran DP
                                                    <?php else: ?>
                                                        <i class="bx bx-check mr-1"></i> Konfirmasi Pembayaran Cash
                                                    <?php endif; ?>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <?php elseif ($pemesanan['statuspembayaran'] == 'partial') : ?>
                                <!-- Form untuk pembayaran H-1 -->
                                <?php if ((!isset($pemesanan['h1_paid']) || $pemesanan['h1_paid'] == 0) && isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1): ?>
                                    <div class="border border-blue-200 rounded-lg overflow-hidden mb-4">
                                        <div class="bg-blue-600 py-3 px-4 text-white">
                                            <h3 class="font-bold">Pembayaran H-1</h3>
                                        </div>
                                        <div class="p-4">
                                            <form id="formPaymentH1" action="<?= site_url('pelanggan/pemesanan/pembayaran/h1') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
                                                <input type="hidden" name="kdpemesanan" value="<?= $pemesanan['kdpemesananpaket'] ?>">
                                                <input type="hidden" name="metodepembayaran" value="<?= $pemesanan['metodepembayaran'] ?>">
                                                <?= csrf_field() ?>

                                                <div>
                                                    <label class="font-medium mb-2 block">Metode Pembayaran</label>
                                                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                                        <span class="font-medium"><?= ($pemesanan['metodepembayaran'] == 'transfer') ? 'Transfer Bank' : 'Cash' ?></span>
                                                    </div>
                                                </div>

                                                <?php if ($pemesanan['metodepembayaran'] == 'transfer'): ?>
                                                    <div id="bukti-pembayaran-h1-container">
                                                        <label class="font-medium mb-2 block">Bukti Pembayaran</label>
                                                        <div class="border border-gray-300 rounded p-2">
                                                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran_h1" class="w-full" accept="image/*" required onchange="previewImage(this, 'preview-bukti-h1')">
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                                                        <div class="mt-2">
                                                            <div id="preview-bukti-h1" class="hidden mt-2">
                                                                <p class="text-sm font-medium mb-1">Preview:</p>
                                                                <div class="relative">
                                                                    <img src="#" alt="Preview bukti pembayaran" class="max-h-60 rounded border border-gray-300">
                                                                    <button type="button" onclick="removeImage('bukti_pembayaran_h1', 'preview-bukti-h1')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="bg-yellow-50 p-4 border border-yellow-200 rounded">
                                                    <p class="text-yellow-800">Silakan transfer pembayaran kedua sebesar <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.1, 0, ',', '.') ?></strong> ke rekening yang tertera di atas.</p>
                                                </div>

                                                <div>
                                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-200" id="btnPayH1">
                                                        <?php if ($pemesanan['metodepembayaran'] == 'transfer'): ?>
                                                            <i class="bx bx-upload mr-1"></i> Upload Bukti Pembayaran H-1
                                                        <?php else: ?>
                                                            <i class="bx bx-check mr-1"></i> Konfirmasi Pembayaran Cash H-1
                                                        <?php endif; ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- H-1 payment status message -->
                                <?php if ($pemesanan['statuspembayaran'] == 'partial' && isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1 && (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] == 0)): ?>
                                    <div class="border border-blue-200 rounded-lg overflow-hidden mb-4">
                                        <div class="bg-blue-100 p-4">
                                            <div class="flex items-center">
                                                <div class="mr-3 bg-blue-500 text-white rounded-full p-2">
                                                    <i class="bx bx-info-circle"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-blue-800">Pembayaran H-1 Sedang Diproses</h4>
                                                    <p class="text-blue-700">Bukti pembayaran H-1 Anda sedang menunggu konfirmasi dari admin. Form pembayaran pelunasan akan tersedia setelah pembayaran H-1 dikonfirmasi.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Pelunasan payment status message -->
                                <?php if ((isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') || (isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === '1') && (!isset($pemesanan['full_confirmed']) || $pemesanan['full_confirmed'] == 0)): ?>
                                    <div class="border border-blue-200 rounded-lg overflow-hidden mb-4">
                                        <div class="bg-blue-100 p-4">
                                            <div class="flex items-center">
                                                <div class="mr-3 bg-blue-500 text-white rounded-full p-2">
                                                    <i class="bx bx-info-circle"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-blue-800">Pelunasan Sedang Diproses</h4>
                                                    <p class="text-blue-700">Bukti pelunasan Anda sedang menunggu konfirmasi dari admin.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']) && isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 && isset($pemesanan['full_paid']) && $pemesanan['full_paid'] === ''): ?>
                                    <div class="border border-red-200 rounded-lg overflow-hidden mb-4">
                                        <div class="bg-red-100 p-4">
                                            <div class="flex items-center">
                                                <div class="mr-3 bg-red-500 text-white rounded-full p-2">
                                                    <i class="bx bx-x-circle"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-red-800">Pelunasan Ditolak</h4>
                                                    <p class="text-red-700">Bukti pelunasan Anda ditolak. Alasan: <?= $pemesanan['rejected_reason'] ?>. Silakan upload ulang bukti pelunasan.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Form untuk pembayaran pelunasan -->
                                <?php
                                // Debug values
                                $h1_confirmed = isset($pemesanan['h1_confirmed']) ? $pemesanan['h1_confirmed'] : 'not set';
                                $full_confirmed = isset($pemesanan['full_confirmed']) ? $pemesanan['full_confirmed'] : 'not set';
                                $full_paid = isset($pemesanan['full_paid']) ? $pemesanan['full_paid'] : 'not set';
                                $rejected_reason = isset($pemesanan['rejected_reason']) ? $pemesanan['rejected_reason'] : 'not set';

                                // Kondisi sederhana: Tampilkan form jika H1 sudah dikonfirmasi dan belum ada pelunasan
                                $show_form = isset($pemesanan['h1_confirmed']) && $pemesanan['h1_confirmed'] == 1 &&
                                    (
                                        // Belum ada pelunasan
                                        ((!isset($pemesanan['full_confirmed']) || $pemesanan['full_confirmed'] == 0) &&
                                            (!isset($pemesanan['full_paid']) || $pemesanan['full_paid'] === '' || $pemesanan['full_paid'] === null || $pemesanan['full_paid'] === '0'))
                                        ||
                                        // Atau pembayaran ditolak
                                        (isset($pemesanan['rejected_reason']) && !empty($pemesanan['rejected_reason']))
                                    );
                                ?>
                                <!-- Debug info -->
                                <div class="bg-gray-100 p-2 mb-4 text-xs">
                                    <p>Debug info (admin only):</p>
                                    <ul>
                                        <li>h1_confirmed: <?= $h1_confirmed ?></li>
                                        <li>full_confirmed: <?= $full_confirmed ?></li>
                                        <li>full_paid: <?= $full_paid ?></li>
                                        <li>rejected_reason: <?= $rejected_reason ?></li>
                                        <li>show_form: <?= $show_form ? 'true' : 'false' ?></li>
                                    </ul>
                                </div>

                                <?php if ($show_form): ?>
                                    <div class="border border-green-200 rounded-lg overflow-hidden">
                                        <div class="bg-green-600 py-3 px-4 text-white">
                                            <h3 class="font-bold">Pembayaran Pelunasan</h3>
                                        </div>
                                        <div class="p-4">
                                            <form id="formPaymentFull" action="<?= site_url('pelanggan/pemesanan/pembayaran/full') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
                                                <input type="hidden" name="kdpemesanan" value="<?= $pemesanan['kdpemesananpaket'] ?>">
                                                <input type="hidden" name="metodepembayaran" value="<?= $pemesanan['metodepembayaran'] ?>">
                                                <?= csrf_field() ?>

                                                <div>
                                                    <label class="font-medium mb-2 block">Metode Pembayaran</label>
                                                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                                                        <span class="font-medium"><?= ($pemesanan['metodepembayaran'] == 'transfer') ? 'Transfer Bank' : 'Cash' ?></span>
                                                    </div>
                                                </div>

                                                <?php if ($pemesanan['metodepembayaran'] == 'transfer'): ?>
                                                    <div id="bukti-pembayaran-full-container">
                                                        <label class="font-medium mb-2 block">Bukti Pembayaran</label>
                                                        <div class="border border-gray-300 rounded p-2">
                                                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran_full" class="w-full" accept="image/*" required onchange="previewImage(this, 'preview-bukti-full')">
                                                        </div>
                                                        <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG. Maks: 2MB</p>
                                                        <div class="mt-2">
                                                            <div id="preview-bukti-full" class="hidden mt-2">
                                                                <p class="text-sm font-medium mb-1">Preview:</p>
                                                                <div class="relative">
                                                                    <img src="#" alt="Preview bukti pembayaran" class="max-h-60 rounded border border-gray-300">
                                                                    <button type="button" onclick="removeImage('bukti_pembayaran_full', 'preview-bukti-full')" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="bg-yellow-50 p-4 border border-yellow-200 rounded">
                                                    <p class="text-yellow-800">Silakan transfer pelunasan sebesar <strong>Rp <?= number_format($pemesanan['grandtotal'] * 0.8, 0, ',', '.') ?></strong> ke rekening yang tertera di atas.</p>
                                                </div>

                                                <div>
                                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition duration-200" id="btnPayFull">
                                                        <?php if ($pemesanan['metodepembayaran'] == 'transfer'): ?>
                                                            <i class="bx bx-upload mr-1"></i> Upload Bukti Pelunasan
                                                        <?php else: ?>
                                                            <i class="bx bx-check mr-1"></i> Konfirmasi Pelunasan Cash
                                                        <?php endif; ?>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Define base URL for JavaScript
    const BASE_URL = '<?= base_url() ?>';
</script>

<?php if (isset($pemesanan['status']) && $pemesanan['status'] === 'pending' && (empty($pemesanan['buktipembayaran']))): ?>
    <!-- Load payment timer script -->
    <script src="<?= base_url('assets/js/payment-timer.js') ?>"></script>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle bukti pembayaran field based on metode pembayaran
        const toggleBuktiPembayaran = function(formId, containerId) {
            const metodeTransfer = document.getElementById(formId);
            const buktiContainer = document.getElementById(containerId);

            if (metodeTransfer && buktiContainer) {
                const showHideBukti = function() {
                    if (metodeTransfer.checked) {
                        buktiContainer.style.display = 'block';
                    } else {
                        buktiContainer.style.display = 'none';
                    }
                };

                // Initial state
                showHideBukti();

                // Add event listeners to radio buttons
                document.querySelectorAll(`input[name="${metodeTransfer.name}"]`).forEach(radio => {
                    radio.addEventListener('change', showHideBukti);
                });
            }
        };

        // Call the function for each form
        toggleBuktiPembayaran('metodeTransfer', 'bukti-pembayaran-container');
        toggleBuktiPembayaran('metodeTransfer_h1', 'bukti-pembayaran-h1-container');
        toggleBuktiPembayaran('metodeTransfer_full', 'bukti-pembayaran-full-container');

        // Preview image function
        window.previewImage = function(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewImg = preview.querySelector('img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        };

        // Remove image function
        window.removeImage = function(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            input.value = '';
            preview.classList.add('hidden');
        };

        // Form validation
        const validateForm = function(formId) {
            const form = document.getElementById(formId);

            if (form) {
                form.addEventListener('submit', function(e) {
                    const fileInput = form.querySelector('input[type="file"]');

                    if (fileInput && fileInput.required && fileInput.value === '') {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Silakan upload bukti pembayaran terlebih dahulu'
                        });
                    }
                });
            }
        };

        // Validate each form
        validateForm('formPaymentDP');
        validateForm('formPaymentH1');
        validateForm('formPaymentFull');
    });
</script>
<?= $this->endSection() ?>
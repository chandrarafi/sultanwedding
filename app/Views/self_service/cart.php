<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php if (ENVIRONMENT === 'development'): ?>
    <!-- Debug Information (only visible in development) -->
    <div class="container mt-2">
        <div class="alert alert-info small">
            <p class="mb-0"><strong>Debug Info:</strong> CSRF Token: <?= csrf_hash() ?></p>
            <p class="mb-0">Token Name: <?= csrf_token() ?></p>
        </div>
    </div>
<?php endif; ?>

<!-- Page Header -->
<section class="bg-primary-600 text-white py-4 mb-4">
    <div class="container mx-auto px-4">
        <div class="flex flex-col">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Keranjang Sewa</h1>
            <nav aria-label="breadcrumb">
                <ol class="flex text-sm">
                    <li><a href="<?= site_url('sewa') ?>" class="text-white hover:text-white/80">Beranda</a></li>
                    <li class="mx-2">/</li>
                    <li class="text-white/80" aria-current="page">Keranjang</li>
                </ol>
            </nav>
        </div>
    </div>
</section>

<!-- Cart Section -->
<section class="py-6">
    <div class="container mx-auto px-4">
        <?php if (empty($items)): ?>
            <!-- Empty Cart -->
            <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                <div class="text-6xl text-gray-300 mb-4">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2 class="text-2xl font-semibold mb-3">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 mb-6">Anda belum menambahkan barang apapun ke keranjang.</p>
                <a href="<?= site_url('barang') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Katalog
                </a>
            </div>
        <?php else: ?>
            <!-- Cart Items -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="border-b px-6 py-4">
                            <h5 class="font-semibold text-lg">Barang dalam Keranjang (<?= count($items) ?>)</h5>
                        </div>
                        <div class="p-6">
                            <form id="updateCartForm" class="cart-items">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <?php foreach ($items as $index => $item): ?>
                                    <div class="flex flex-col md:flex-row items-center md:items-start gap-4 py-4 <?= $index > 0 ? 'border-t border-gray-200' : '' ?>" data-item-id="<?= $item['id'] ?>">
                                        <div class="w-24 h-24 flex-shrink-0">
                                            <img src="<?= base_url('uploads/barang/' . $item['id'] . '.jpg') ?>"
                                                alt="<?= $item['namabarang'] ?>"
                                                class="w-full h-full object-cover rounded-md"
                                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCIgZmlsbD0ibm9uZSI+PHJlY3Qgd2lkdGg9IjE1MCIgaGVpZ2h0PSIxNTAiIGZpbGw9IiNFRUVFRUUiLz48cGF0aCBkPSJNNjkuNzYxNyA3NS4yNUg4MC4yMzgzQzgxLjIzMzMgNzUuMjUgODIuMDUgNzQuNDMzMyA4Mi4wNSA3My40Mzg0VjYyLjk2MTdDODIuMDUgNjEuOTY2NyA4MS4yMzMzIDYxLjE1IDgwLjIzODMgNjEuMTVINjkuNzYxN0M2OC43NjY3IDYxLjE1IDY3Ljk1IDYxLjk2NjcgNjcuOTUgNjIuOTYxN1Y3My40Mzg0QzY3Ljk1IDc0LjQzMzMgNjguNzY2NyA3NS4yNSA2OS43NjE3IDc1LjI1Wk03MS41NzUgNjQuNzc1SDc4LjQyNVY3MS42MjVINzEuNTc1VjY0Ljc3NVpNOTQuMDUgNjEuMTVIODUuNjc1VjU3LjUyNUM4NS42NzUgNTYuNTMgODQuODU4MyA1NS43MTM0IDgzLjg2MzQgNTUuNzEzNEg2Ni4xMzY3QzY1LjE0MTcgNTUuNzEzNCA2NC4zMjUgNTYuNTMgNjQuMzI1IDU3LjUyNVY2MS4xNUg1NS45NUM1NC45NTUgNjEuMTUgNTQuMTM4NCA2MS45NjY3IDU0LjEzODQgNjIuOTYxN1Y4Ny4wMzg0QzU0LjEzODQgODguMDMzMyA1NC45NTUgODguODUgNTUuOTUgODguODVIOTQuMDVDOTUuMDQ1IDg4Ljg1IDk1Ljg2MTcgODguMDMzMyA5NS44NjE3IDg3LjAzODRWNjIuOTYxN0M5NS44NjE3IDYxLjk2NjcgOTUuMDQ1IDYxLjE1IDk0LjA1IDYxLjE1Wk05Mi4yMzg0IDg1LjIyNUg1Ny43NjE3VjY0Ljc3NUg2NC4zMjVWNjYuNTg2N0M2NC4zMjUgNjcuNTgxNyA2NS4xNDE3IDY4LjM5ODQgNjYuMTM2NyA2OC4zOTg0QzY3LjEzMTcgNjguMzk4NCA2Ny45NDg0IDY3LjU4MTcgNjcuOTQ4NCA2Ni41ODY3VjY0Ljc3NUg4Mi4wNVY2Ni41ODY3QzgyLjA1IDY3LjU4MTcgODIuODY2NyA2OC4zOTg0IDgzLjg2MTcgNjguMzk4NEM4NC44NTY3IDY4LjM5ODQgODUuNjczNCA2Ny41ODE3IDg1LjY3MzQgNjYuNTg2N1Y2NC43NzVIOTIuMjM2N1Y4NS4yMjVIOTIuMjM4NFoiIGZpbGw9IiM5QUEwQTYiLz48dGV4dCB4PSI1MCIgeT0iMTEwIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM2NjY2NjYiPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                                        </div>
                                        <div class="flex-grow">
                                            <h5 class="font-semibold mb-1"><?= $item['namabarang'] ?></h5>
                                            <p class="text-gray-500 text-sm mb-1">Rp <?= number_format($item['hargasewa'], 0, ',', '.') ?> / <?= $item['satuan'] ?></p>
                                            <p class="text-sm mb-1">
                                                <span class="text-gray-500">Tanggal Sewa:</span>
                                                <?= date('d M Y', strtotime($item['tanggal_mulai'])) ?> -
                                                <?= date('d M Y', strtotime($item['tanggal_selesai'])) ?>
                                            </p>
                                            <p class="text-sm mb-3">
                                                <span class="text-gray-500">Durasi:</span>
                                                <?php
                                                $start = new DateTime($item['tanggal_mulai']);
                                                $end = new DateTime($item['tanggal_selesai']);
                                                $days = $end->diff($start)->days;
                                                echo $days . ' hari';
                                                ?>
                                            </p>
                                            <div class="flex flex-wrap items-center gap-4">
                                                <div class="flex items-center border rounded-md">
                                                    <a href="<?= site_url('sewa/cart/update-item/' . $item['id'] . '/' . max(1, $item['qty'] - 1)) ?>" class="decrease-qty px-3 py-1 text-gray-600 hover:bg-gray-100 focus:outline-none">
                                                        <i class="fas fa-minus"></i>
                                                    </a>
                                                    <input type="text" class="item-qty w-12 text-center border-0 focus:outline-none focus:ring-0"
                                                        name="items[<?= $item['id'] ?>]"
                                                        data-item-id="<?= $item['id'] ?>"
                                                        value="<?= $item['qty'] ?>" readonly>
                                                    <a href="<?= site_url('sewa/cart/update-item/' . $item['id'] . '/' . min($item['stok_tersedia'], $item['qty'] + 1)) ?>" class="increase-qty px-3 py-1 text-gray-600 hover:bg-gray-100 focus:outline-none">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                </div>
                                                <div class="font-semibold item-subtotal" data-price="<?= $item['hargasewa'] ?>" data-item-id="<?= $item['id'] ?>">
                                                    Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                                </div>
                                                <form action="<?= site_url('sewa/cart/remove/' . $item['id']) ?>" method="post" style="display: inline;">
                                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                                    <button type="submit" class="remove-item text-red-500 hover:text-red-700 focus:outline-none">
                                                        <i class="fas fa-trash-alt"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="flex flex-wrap justify-between gap-3 mt-6">
                                    <a href="<?= site_url('barang') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-arrow-left mr-2"></i> Lanjutkan Belanja
                                    </a>
                                    <a href="<?= site_url('sewa/cart/clear') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?');">
                                        <i class="fas fa-trash-alt mr-2"></i> Kosongkan Keranjang
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                        <div class="border-b px-6 py-4">
                            <h5 class="font-semibold text-lg">Ringkasan Pesanan</h5>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between mb-3">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-semibold">Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </div>
                            <div class="flex justify-between mb-3">
                                <span class="text-gray-600">Biaya Layanan:</span>
                                <span class="font-semibold">Rp 0</span>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between mb-6">
                                <span class="text-lg font-semibold">Total:</span>
                                <span class="text-lg font-semibold text-primary-600" id="total">Rp <?= number_format($total, 0, ',', '.') ?></span>
                            </div>

                            <form id="checkoutForm" action="<?= site_url('sewa/checkout') ?>" method="post">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-medium mb-2">Metode Pembayaran</label>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" type="radio" name="payment_method" id="transfer" value="transfer" checked>
                                            <label class="ml-2 block text-sm text-gray-700" for="transfer">
                                                Transfer Bank
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" type="radio" name="payment_method" id="cash" value="cash">
                                            <label class="ml-2 block text-sm text-gray-700" for="cash">
                                                Tunai (Bayar di Tempat)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <?php if (session()->get('logged_in')): ?>
                                    <button type="submit" id="checkoutButton" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        Lanjutkan ke Pembayaran <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                    <p class="text-center text-xs text-gray-500 mt-2">Jika tombol di atas tidak berfungsi, <a href="<?= site_url('sewa/checkout-alt?method=' . urlencode('transfer')) ?>" class="text-primary-600 hover:underline">klik di sini</a></p>
                                <?php else: ?>
                                    <a href="<?= site_url('auth/login') ?>" class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        Login untuk Melanjutkan <i class="fas fa-sign-in-alt ml-2"></i>
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="bg-blue-50 rounded-lg p-5 border border-blue-100">
                        <h6 class="font-semibold mb-3 text-blue-800">Informasi Pembayaran</h6>
                        <ul class="space-y-3 text-sm">
                            <li class="flex">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                <span class="text-gray-700">Pembayaran uang muka (DP) sebesar 50% dari total.</span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                <span class="text-gray-700">Pelunasan dapat dilakukan saat pengambilan barang.</span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                <span class="text-gray-700">Pembatalan pemesanan dapat dikenakan biaya.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Helper function for number formatting
    function number_format(number, decimals, dec_point, thousands_sep) {
        // Format a number with grouped thousands
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
<?= $this->endSection() ?>
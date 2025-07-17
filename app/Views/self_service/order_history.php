<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<section class="bg-primary text-white py-4 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Riwayat Pesanan</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= site_url('sewa') ?>" class="text-white">Beranda</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Riwayat Pesanan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Orders Section -->
<section class="py-4">
    <div class="container">
        <?php if (empty($orders)): ?>
            <!-- No Orders -->
            <div class="text-center py-5">
                <div class="display-1 text-muted mb-4">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h2 class="mb-3">Belum Ada Pesanan</h2>
                <p class="text-muted mb-4">Anda belum memiliki pesanan apapun.</p>
                <a href="<?= site_url('sewa/barang') ?>" class="btn btn-primary">
                    <i class="fas fa-shopping-cart me-2"></i> Mulai Menyewa
                </a>
            </div>
        <?php else: ?>
            <!-- Order List -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Pesanan</h5>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari pesanan...">
                            <button class="btn btn-outline-primary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status Pesanan</th>
                                    <th>Status Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTable">
                                <?php foreach ($orders as $order): ?>
                                    <tr class="order-row" data-order-number="<?= $order['nopesanan'] ?>">
                                        <td><?= $order['nopesanan'] ?></td>
                                        <td><?= date('d M Y', strtotime($order['tanggalpemesanan'])) ?></td>
                                        <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php
                                            $statusBadge = 'bg-secondary';
                                            switch ($order['status']) {
                                                case 'pending':
                                                    $statusBadge = 'bg-warning text-dark';
                                                    break;
                                                case 'processed':
                                                    $statusBadge = 'bg-info text-dark';
                                                    break;
                                                case 'completed':
                                                    $statusBadge = 'bg-success';
                                                    break;
                                                case 'cancelled':
                                                    $statusBadge = 'bg-danger';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?= $statusBadge ?>"><?= ucfirst($order['status']) ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $paymentBadge = 'bg-secondary';
                                            switch ($order['statuspembayaran']) {
                                                case 'belum':
                                                    $paymentBadge = 'bg-danger';
                                                    $paymentStatus = 'Belum Bayar';
                                                    break;
                                                case 'verifikasi':
                                                    $paymentBadge = 'bg-warning text-dark';
                                                    $paymentStatus = 'Verifikasi';
                                                    break;
                                                case 'dp':
                                                    $paymentBadge = 'bg-info text-dark';
                                                    $paymentStatus = 'DP';
                                                    break;
                                                case 'lunas':
                                                    $paymentBadge = 'bg-success';
                                                    $paymentStatus = 'Lunas';
                                                    break;
                                                default:
                                                    $paymentStatus = ucfirst($order['statuspembayaran']);
                                            }
                                            ?>
                                            <span class="badge <?= $paymentBadge ?>"><?= $paymentStatus ?></span>
                                        </td>
                                        <td>
                                            <a href="<?= site_url('sewa/orders/' . $order['nopesanan']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- No Results -->
            <div class="text-center py-5 d-none" id="noResults">
                <h3>Tidak ada pesanan yang sesuai dengan pencarian Anda.</h3>
                <p>Silakan coba kata kunci lain atau reset pencarian.</p>
                <button class="btn btn-primary" id="resetSearch">Reset Pencarian</button>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Search functionality
        $('#searchButton, #searchInput').on('click keyup', function(e) {
            if (e.type === 'click' || e.keyCode === 13) {
                const searchTerm = $('#searchInput').val().toLowerCase();
                filterOrders(searchTerm);
            }
        });

        // Reset search
        $('#resetSearch').on('click', function() {
            $('#searchInput').val('');
            filterOrders('');
        });

        // Filter orders based on search term
        function filterOrders(searchTerm) {
            let hasResults = false;

            $('.order-row').each(function() {
                const orderNumber = $(this).data('order-number').toString().toLowerCase();

                if (orderNumber.includes(searchTerm)) {
                    $(this).removeClass('d-none');
                    hasResults = true;
                } else {
                    $(this).addClass('d-none');
                }
            });

            if (hasResults) {
                $('#noResults').addClass('d-none');
                $('#ordersTable').closest('.card').removeClass('d-none');
            } else {
                $('#noResults').removeClass('d-none');
                $('#ordersTable').closest('.card').addClass('d-none');
            }
        }
    });
</script>
<?= $this->endSection() ?>
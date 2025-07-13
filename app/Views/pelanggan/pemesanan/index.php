<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-serif font-bold text-secondary-800">Daftar Pemesanan Paket</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                    <?php if (empty($pemesanan)) : ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">Tidak ada data pemesanan</td>
                        </tr>
                    <?php else : ?>
                        <?php $no = 1;
                        foreach ($pemesanan as $p) : ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $p['kdpemesananpaket'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($p['tgl'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $p['namapaket'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp <?= number_format($p['grandtotal'], 0, ',', '.') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $badgeClass = 'bg-yellow-100 text-yellow-800';
                                    $statusText = 'Menunggu DP';

                                    if (isset($p['status'])) {
                                        switch ($p['status']) {
                                            case 'pending':
                                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'Menunggu DP';
                                                break;
                                            case 'process':
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                                $statusText = 'Proses';
                                                break;
                                            case 'completed':
                                                $badgeClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Selesai';
                                                break;
                                            case 'cancelled':
                                                $badgeClass = 'bg-red-100 text-red-800';
                                                $statusText = 'Dibatalkan';
                                                break;
                                            default:
                                                $badgeClass = 'bg-gray-100 text-gray-800';
                                                $statusText = 'Unknown';
                                        }
                                    }

                                    // Override based on payment status if available
                                    if (isset($p['statuspembayaran'])) {
                                        switch ($p['statuspembayaran']) {
                                            case 'pending':
                                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                                                $statusText = 'Menunggu Konfirmasi';
                                                break;
                                            case 'partial':
                                                $badgeClass = 'bg-blue-100 text-blue-800';
                                                $statusText = 'DP Dibayar';
                                                break;
                                            case 'success':
                                                $badgeClass = 'bg-green-100 text-green-800';
                                                $statusText = 'Lunas';
                                                break;
                                            case 'rejected':
                                                $badgeClass = 'bg-red-100 text-red-800';
                                                $statusText = 'Ditolak';
                                                break;
                                        }
                                    }
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badgeClass ?>"><?= $statusText ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?= site_url('pelanggan/pemesanan/pembayaran/' . $p['kdpemesananpaket']) ?>" class="text-primary-600 hover:text-primary-900 bg-primary-50 hover:bg-primary-100 px-3 py-1 rounded-md">
                                        <i class="fas fa-money-bill-wave mr-1"></i> Pembayaran
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "pageLength": 10,
            "ordering": false
        });
    });
</script>
<?= $this->endSection() ?>
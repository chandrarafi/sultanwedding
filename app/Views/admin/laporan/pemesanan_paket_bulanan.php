<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-extended.css') ?>">
<style>
    @media (max-width: 767.98px) {
        .table-responsive-md {
            overflow-x: auto;
        }

        #dataTable th,
        #dataTable td {
            white-space: nowrap;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    .stat-card {
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, .12);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, .2);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        z-index: -1;
        transform: translateX(-100%);
        transition: transform 0.5s ease;
    }

    .stat-card:hover::before {
        transform: translateX(0);
    }

    .stat-card h4 {
        margin-top: 0;
        margin-bottom: 10px;
        font-weight: 600;
        position: relative;
        padding-left: 35px;
        font-size: 1rem;
    }

    .stat-card h4 i {
        position: absolute;
        left: 0;
        top: 0;
        font-size: 1.5rem;
    }

    .stat-card p {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 0;
    }

    .stat-icon {
        position: absolute;
        right: 20px;
        bottom: 10px;
        font-size: 3.5rem;
        opacity: 0.15;
        z-index: -1;
    }

    .stat-card.primary {
        background: linear-gradient(135deg, #3461ff, #6e8eff);
        color: white;
    }

    .stat-card.success {
        background: linear-gradient(135deg, #14a44d, #3dd078);
        color: white;
    }

    .stat-card.warning {
        background: linear-gradient(135deg, #e4a11b, #ffc107);
        color: white;
    }

    .stat-card.info {
        background: linear-gradient(135deg, #54b4d3, #7cdbf5);
        color: white;
    }

    .stat-card.danger {
        background: linear-gradient(135deg, #dc3545, #ff6b7d);
        color: white;
    }

    .stat-card.purple {
        background: linear-gradient(135deg, #6f42c1, #a47dfa);
        color: white;
    }

    .card {
        border: none;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding: 15px 20px;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        border-color: #e9ecef;
        font-weight: 600;
    }

    .badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .btn-primary {
        background: linear-gradient(to right, #3461ff, #5e82ff);
        border: none;
    }

    .btn-success {
        background: linear-gradient(to right, #14a44d, #3dd078);
        border: none;
    }

    .report-title {
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .report-title:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background: #3461ff;
        bottom: 0;
        left: 0;
    }

    .page-breadcrumb {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Laporan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/laporan/pemesananPaket') ?>">Laporan Pemesanan Paket</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Bulanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 report-title">Laporan Pemesanan Paket Bulanan: <?= $nama_bulan ?> <?= $tahun ?></h5>
        <div class="btn-group">
            <button class="btn btn-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fadeIn animated bx bx-file me-1"></i> Jenis Laporan <i class="fadeIn animated bx bx-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="<?= site_url('admin/laporan/pemesananPaket') ?>">Laporan Harian</a>
                <a class="dropdown-item" href="<?= site_url('admin/laporan/pemesananPaketBulanan') ?>">Laporan Bulanan</a>
                <a class="dropdown-item" href="<?= site_url('admin/laporan/pemesananPaketTahunan') ?>">Laporan Tahunan</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= site_url('admin/laporan/pemesananPaketBulanan') ?>" method="get" class="mb-4">
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select class="form-select" id="bulan" name="bulan">
                        <option value="01" <?= ($bulan === '01') ? 'selected' : '' ?>>Januari</option>
                        <option value="02" <?= ($bulan === '02') ? 'selected' : '' ?>>Februari</option>
                        <option value="03" <?= ($bulan === '03') ? 'selected' : '' ?>>Maret</option>
                        <option value="04" <?= ($bulan === '04') ? 'selected' : '' ?>>April</option>
                        <option value="05" <?= ($bulan === '05') ? 'selected' : '' ?>>Mei</option>
                        <option value="06" <?= ($bulan === '06') ? 'selected' : '' ?>>Juni</option>
                        <option value="07" <?= ($bulan === '07') ? 'selected' : '' ?>>Juli</option>
                        <option value="08" <?= ($bulan === '08') ? 'selected' : '' ?>>Agustus</option>
                        <option value="09" <?= ($bulan === '09') ? 'selected' : '' ?>>September</option>
                        <option value="10" <?= ($bulan === '10') ? 'selected' : '' ?>>Oktober</option>
                        <option value="11" <?= ($bulan === '11') ? 'selected' : '' ?>>November</option>
                        <option value="12" <?= ($bulan === '12') ? 'selected' : '' ?>>Desember</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select class="form-select" id="tahun" name="tahun">
                        <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                            <option value="<?= $i ?>" <?= ($tahun == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="bx bx-filter-alt me-1"></i> Tampilkan</button>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-3">Filter Laporan</h5>
            <div>
                <a href="<?= site_url('admin/laporan/cetakLaporanPaketBulanan?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-primary" target="_blank">
                    <i class="bx bx-printer"></i> Cetak Laporan
                </a>
            </div>
        </div>


        <!-- Tabel Data -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bx bx-table me-2"></i>Data Pemesanan Paket - <?= $nama_bulan ?> <?= $tahun ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pemesanan</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($laporan['data'])) : ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data pemesanan</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1;
                                foreach ($laporan['data'] as $p) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $p['kdpemesananpaket'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                                        <td><?= $p['namapelanggan'] ?></td>
                                        <td><?= $p['namapaket'] ?></td>
                                        <td class="text-end">Rp <?= number_format($p['hargapaket'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php if ($p['status'] === 'pending') : ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php elseif ($p['status'] === 'confirmed') : ?>
                                                <span class="badge bg-info">Confirmed</span>
                                            <?php elseif ($p['status'] === 'completed') : ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php elseif ($p['status'] === 'cancelled') : ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary"><?= ucfirst($p['status']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($p['statuspembayaran'] === 'pending') : ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php elseif ($p['statuspembayaran'] === 'partial') : ?>
                                                <span class="badge bg-info">Partial</span>
                                            <?php elseif ($p['statuspembayaran'] === 'success') : ?>
                                                <span class="badge bg-success">Success</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary"><?= ucfirst($p['statuspembayaran'] ?? 'N/A') ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="5" class="text-end">Total Pendapatan:</th>
                                <th colspan="3" class="text-start">Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
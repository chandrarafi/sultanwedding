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

    .method-stat {
        background-color: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, .08);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
        height: 100%;
    }

    .method-stat:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, .15);
    }

    .method-stat::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        z-index: -1;
        transition: transform 0.6s ease;
    }

    .method-stat:hover::before {
        transform: rotate(45deg) translateY(-100px);
    }

    .method-stat i {
        font-size: 3rem;
        margin-bottom: 15px;
        display: inline-block;
        background: linear-gradient(135deg, #3461ff, #6e8eff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: transform 0.3s ease;
    }

    .method-stat:hover i {
        transform: scale(1.2);
    }

    .method-stat.transfer i {
        background: linear-gradient(135deg, #3461ff, #6e8eff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .method-stat.cash i {
        background: linear-gradient(135deg, #14a44d, #3dd078);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .method-stat.other i {
        background: linear-gradient(135deg, #54b4d3, #7cdbf5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .method-stat h6 {
        color: #444;
        margin-bottom: 10px;
        font-weight: 600;
        font-size: 1rem;
    }

    .method-stat p {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-bottom: 0;
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
                <li class="breadcrumb-item"><a href="<?= site_url('admin/laporan/pembayaran') ?>">Laporan Pembayaran</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Bulanan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 report-title">Laporan Pembayaran Bulanan: <?= $nama_bulan ?> <?= $tahun ?></h5>
        <div class="btn-group">
            <button class="btn btn-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fadeIn animated bx bx-file me-1"></i> Jenis Laporan <i class="fadeIn animated bx bx-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="<?= site_url('admin/laporan/pembayaran') ?>">Laporan Harian</a>
                <a class="dropdown-item" href="<?= site_url('admin/laporan/pembayaranBulanan') ?>">Laporan Bulanan</a>
                <a class="dropdown-item" href="<?= site_url('admin/laporan/pembayaranTahunan') ?>">Laporan Tahunan</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="<?= site_url('admin/laporan/pembayaranBulanan') ?>" method="get" class="mb-4">
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
                <a href="<?= site_url('admin/laporan/cetakLaporanPembayaranBulanan?bulan=' . $bulan . '&tahun=' . $tahun) ?>" class="btn btn-primary" target="_blank">
                    <i class="bx bx-printer"></i> Cetak Laporan
                </a>
            </div>
        </div>


        <!-- Data Pembayaran -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bx bx-table me-2"></i> Data Pembayaran - <?= $nama_bulan ?> <?= $tahun ?></h6>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pembayaran</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Tipe</th>
                                <th>Jenis</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($laporan['data'])) : ?>
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data pembayaran</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1;
                                foreach ($laporan['data'] as $p) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $p['kdpembayaran'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($p['tgl'])) ?></td>
                                        <td><?= $p['namapelanggan'] ?? '-' ?></td>
                                        <td><?= ucfirst($p['tipepembayaran'] ?? '-') ?></td>
                                        <td><?= $p['jenis_pemesanan'] ?? '-' ?></td>
                                        <td><?= ucfirst($p['metodepembayaran'] ?? '-') ?></td>
                                        <td class="text-end">Rp <?= number_format($p['jumlahbayar'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php if ($p['status'] === 'pending') : ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php elseif ($p['status'] === 'partial') : ?>
                                                <span class="badge bg-info">Partial</span>
                                            <?php elseif ($p['status'] === 'success') : ?>
                                                <span class="badge bg-success">Success</span>
                                            <?php elseif ($p['status'] === 'confirmed') : ?>
                                                <span class="badge bg-success">Confirmed</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary"><?= ucfirst($p['status'] ?? 'N/A') ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-primary">
                                <th colspan="7" class="text-end">Total Pendapatan:</th>
                                <th colspan="2" class="text-start">Rp <?= number_format($laporan['total_pendapatan'], 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
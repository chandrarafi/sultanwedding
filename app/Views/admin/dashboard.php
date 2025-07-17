<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php if (isset($pemesananForReturn) && count($pemesananForReturn) > 0): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h5><i class="bx bx-info-circle me-2"></i>Perhatian! Ada <?= count($pemesananForReturn) ?> barang yang perlu diproses pengembaliannya</h5>
        <p>Segera proses pengembalian barang untuk menjaga inventaris.</p>
        <a href="<?= site_url('admin/pemesananbarang/pengembalian') ?>" class="btn btn-sm btn-success mt-2">
            <i class="bx bx-check-circle"></i> Proses Pengembalian
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card primary h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Pemesanan Barang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">25</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success mr-2"><i class="bi bi-arrow-up"></i> 12%</span>
                            <span>Sejak bulan lalu</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cart icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card success h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Pemesanan Paket</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success mr-2"><i class="bi bi-arrow-up"></i> 8%</span>
                            <span>Sejak bulan lalu</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-bag-check icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card warning h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Pendapatan Bulan Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp 15.000.000</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success mr-2"><i class="bi bi-arrow-up"></i> 15%</span>
                            <span>Sejak bulan lalu</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-cash-coin icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card danger h-100 py-2">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Pelanggan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">42</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success mr-2"><i class="bi bi-arrow-up"></i> 20%</span>
                            <span>Sejak bulan lalu</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Grafik Pemesanan</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Pilih Periode
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Minggu Ini</a></li>
                        <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
                        <li><a class="dropdown-item" href="#">Tahun Ini</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="chart-pemesanan" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Distribusi Pemesanan</h6>
            </div>
            <div class="card-body">
                <div id="chart-donut" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Pemesanan Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>P-001</td>
                                <td>Ahmad Fauzi</td>
                                <td>20/06/2024</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>P-002</td>
                                <td>Siti Nurhaliza</td>
                                <td>19/06/2024</td>
                                <td><span class="badge bg-warning">Proses</span></td>
                            </tr>
                            <tr>
                                <td>P-003</td>
                                <td>Budi Santoso</td>
                                <td>18/06/2024</td>
                                <td><span class="badge bg-info">Pembayaran</span></td>
                            </tr>
                            <tr>
                                <td>P-004</td>
                                <td>Dewi Lestari</td>
                                <td>17/06/2024</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>P-005</td>
                                <td>Rudi Hartono</td>
                                <td>16/06/2024</td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">Paket Terpopuler</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama Paket</th>
                                <th>Kategori</th>
                                <th>Jumlah Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Paket Pernikahan Gold</td>
                                <td>Pernikahan</td>
                                <td>15</td>
                            </tr>
                            <tr>
                                <td>Paket Pernikahan Silver</td>
                                <td>Pernikahan</td>
                                <td>12</td>
                            </tr>
                            <tr>
                                <td>Paket Dekorasi Premium</td>
                                <td>Dekorasi</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Paket Pernikahan Bronze</td>
                                <td>Pernikahan</td>
                                <td>8</td>
                            </tr>
                            <tr>
                                <td>Paket Catering Deluxe</td>
                                <td>Catering</td>
                                <td>7</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Chart Pemesanan
        var optionsPemesanan = {
            series: [{
                name: 'Pemesanan Barang',
                data: [31, 40, 28, 51, 42, 82, 56]
            }, {
                name: 'Pemesanan Paket',
                data: [11, 32, 45, 32, 34, 52, 41]
            }],
            chart: {
                height: 300,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            colors: ['#8e44ad', '#2ecc71'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                categories: ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy'
                },
            },
        };

        var chartPemesanan = new ApexCharts(document.querySelector("#chart-pemesanan"), optionsPemesanan);
        chartPemesanan.render();

        // Chart Donut
        var optionsDonut = {
            series: [44, 55, 13, 33],
            chart: {
                height: 300,
                type: 'donut',
            },
            labels: ['Paket Pernikahan', 'Paket Dekorasi', 'Sewa Barang', 'Catering'],
            colors: ['#8e44ad', '#2ecc71', '#3498db', '#f39c12'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        show: false
                    }
                }
            }],
            legend: {
                position: 'bottom'
            }
        };

        var chartDonut = new ApexCharts(document.querySelector("#chart-donut"), optionsDonut);
        chartDonut.render();
    });
</script>
<?= $this->endSection() ?>
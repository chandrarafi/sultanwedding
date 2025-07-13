<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="<?= base_url('assets/images/logo-icon.png') ?>" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">SULTAN</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="<?= site_url('admin') ?>">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <!-- Master Data -->
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-data"></i>
                </div>
                <div class="menu-title">Master Data</div>
            </a>
            <ul>
                <li> <a href="<?= site_url('admin/kategori') ?>"><i class="bx bx-right-arrow-alt"></i>Kategori</a>
                </li>
                <li> <a href="<?= site_url('admin/barang') ?>"><i class="bx bx-right-arrow-alt"></i>Barang</a>
                </li>
                <li> <a href="<?= site_url('admin/paket') ?>"><i class="bx bx-right-arrow-alt"></i>Paket</a>
                </li>
                <li> <a href="<?= site_url('admin/pelanggan') ?>"><i class="bx bx-right-arrow-alt"></i>Pelanggan</a>
                </li>
            </ul>
        </li>

        <li class="menu-label">TRANSAKSI</li>
        <!-- Pemesanan -->
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-calendar-check"></i>
                </div>
                <div class="menu-title">Pemesanan</div>
            </a>
            <ul>
                <li>
                    <a href="<?= site_url('admin/pemesananpaket') ?>"><i class="bx bx-right-arrow-alt"></i>Daftar Pemesanan</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?= site_url('admin/pembayaran') ?>">
                <div class="parent-icon"><i class='bx bx-money'></i>
                </div>
                <div class="menu-title">Pembayaran</div>
            </a>
        </li>

        <li class="menu-label">LAPORAN</li>
        <li>
            <a href="<?= site_url('admin/laporan/pemesanan') ?>">
                <div class="parent-icon"><i class='bx bx-file'></i>
                </div>
                <div class="menu-title">Laporan Pemesanan</div>
            </a>
        </li>
        <li>
            <a href="<?= site_url('admin/laporan/pendapatan') ?>">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title">Laporan Pendapatan</div>
            </a>
        </li>

        <li class="menu-label">PENGATURAN</li>
        <li>
            <a href="<?= site_url('admin/users') ?>">
                <div class="parent-icon"><i class='bx bx-user-circle'></i>
                </div>
                <div class="menu-title">User Management</div>
            </a>
        </li>
        <li>
            <a href="<?= site_url('admin/profile') ?>">
                <div class="parent-icon"><i class='bx bx-user-pin'></i>
                </div>
                <div class="menu-title">Profil</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <div class="parent-icon"><i class='bx bx-log-out'></i>
                </div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="text-center">
            <h3>SULTAN</h3>
            <p class="small">Wedding Organizer</p>
        </div>
    </div>
    <hr class="sidebar-divider">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Dashboard' ? 'active' : '' ?>" href="<?= site_url('admin') ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-header mt-3">MASTER DATA</li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Kategori' ? 'active' : '' ?>" href="<?= site_url('admin/kategori') ?>">
                <i class="bi bi-tags"></i>
                <span>Kategori</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Barang' ? 'active' : '' ?>" href="<?= site_url('admin/barang') ?>">
                <i class="bi bi-box-seam"></i>
                <span>Barang</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Paket' ? 'active' : '' ?>" href="<?= site_url('admin/paket') ?>">
                <i class="bi bi-gift"></i>
                <span>Paket</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Pelanggan' ? 'active' : '' ?>" href="<?= site_url('admin/pelanggan') ?>">
                <i class="bi bi-people"></i>
                <span>Pelanggan</span>
            </a>
        </li>

        <li class="nav-header mt-3">TRANSAKSI</li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Pemesanan Barang' ? 'active' : '' ?>" href="<?= site_url('admin/pemesananbarang') ?>">
                <i class="bi bi-cart"></i>
                <span>Pemesanan Barang</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Pemesanan Paket' ? 'active' : '' ?>" href="<?= site_url('admin/pemesananpaket') ?>">
                <i class="bi bi-bag-check"></i>
                <span>Pemesanan Paket</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Pembayaran' ? 'active' : '' ?>" href="<?= site_url('admin/pembayaran') ?>">
                <i class="bi bi-cash-coin"></i>
                <span>Pembayaran</span>
            </a>
        </li>

        <li class="nav-header mt-3">LAPORAN</li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Laporan Pemesanan' ? 'active' : '' ?>" href="<?= site_url('admin/laporan/pemesanan') ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laporan Pemesanan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Laporan Pendapatan' ? 'active' : '' ?>" href="<?= site_url('admin/laporan/pendapatan') ?>">
                <i class="bi bi-graph-up"></i>
                <span>Laporan Pendapatan</span>
            </a>
        </li>

        <li class="nav-header mt-3">PENGATURAN</li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'User Management' ? 'active' : '' ?>" href="<?= site_url('admin/users') ?>">
                <i class="bi bi-person-gear"></i>
                <span>User Management</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $title == 'Profil' ? 'active' : '' ?>" href="<?= site_url('admin/profile') ?>">
                <i class="bi bi-person-circle"></i>
                <span>Profil</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="modal" data-bs-target="#logoutModal" style="cursor: pointer;">
                <i class="bi bi-box-arrow-left"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Mobile Toggle Button -->
<div class="sidebar-toggle d-lg-none" id="sidebarToggle">
    <i class="bi bi-list"></i>
</div>
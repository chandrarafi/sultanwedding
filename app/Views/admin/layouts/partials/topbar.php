<!-- Topbar -->
<div class="topbar glassmorphism">
    <button class="navbar-toggler d-md-none" id="navbarToggler" type="button">
        <i class="bi bi-list navbar-toggler-icon"></i>
    </button>
    <h1><?= $title ?? 'Dashboard' ?></h1>
    <div class="topbar-divider"></div>
    <div class="text-secondary small">Selamat datang, <?= session()->get('name') ?? 'Administrator' ?></div>
    <div class="topbar-nav">
        <div class="topbar-item">
            <a href="#" class="nav-link" data-bs-toggle="tooltip" title="Notifikasi">
                <i class="bi bi-bell"></i>
                <span class="notification-badge">3</span>
            </a>
        </div>
        <div class="topbar-item">
            <a href="#" class="nav-link" data-bs-toggle="tooltip" title="Pesan">
                <i class="bi bi-envelope"></i>
                <span class="notification-badge">2</span>
            </a>
        </div>
        <div class="user-profile" data-bs-toggle="modal" data-bs-target="#userModal">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('name') ?? 'Admin') ?>&background=8e44ad&color=fff" alt="User">
            <div class="user-info">
                <h6><?= session()->get('name') ?? 'Admin' ?></h6>
                <small><?= session()->get('role') ?? 'Administrator' ?></small>
            </div>
        </div>
    </div>
</div>

<?= $this->include('admin/layouts/partials/modals/user_modal') ?>
<?= $this->include('admin/layouts/partials/modals/logout_modal') ?>
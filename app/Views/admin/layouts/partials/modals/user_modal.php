<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Profil Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode(session()->get('name') ?? 'Admin') ?>&background=8e44ad&color=fff" alt="User" class="rounded-circle mb-3" style="width: 100px; height: 100px;">
                    <h5 class="mb-0"><?= session()->get('name') ?? 'Administrator' ?></h5>
                    <p class="text-muted"><?= session()->get('email') ?? 'admin@example.com' ?></p>
                    <div class="badge bg-primary mb-2"><?= session()->get('role') ?? 'Administrator' ?></div>
                </div>

                <div class="list-group list-group-flush">
                    <a href="<?= site_url('admin/profile') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-person-circle me-3"></i>
                            Profil Saya
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="<?= site_url('admin/profile/password') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-key me-3"></i>
                            Ubah Password
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="<?= site_url('admin/settings') ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-gear me-3"></i>
                            Pengaturan
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-danger" id="btn-logout-modal">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </div>
        </div>
    </div>
</div>
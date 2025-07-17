<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Utility</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Migrasi File</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Hasil Migrasi File</h5>
            <div class="ms-auto">
                <a href="<?= site_url('admin') ?>" class="btn btn-primary px-3 radius-30">
                    <i class="bx bx-home"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>

        <div class="mt-3">
            <div class="alert alert-<?= $result['status'] ? 'success' : 'danger' ?>">
                <h4><?= $result['message'] ?></h4>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Detail Proses</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($result['detail'] as $detail): ?>
                            <li class="list-group-item"><?= $detail ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->include('admin/layouts/partials/header') ?>

<!-- Main Layout -->
<div class="wrapper">
    <?= $this->include('admin/layouts/partials/sidebar') ?>

    <!-- Main Content -->
    <div class="main-content">
        <?= $this->include('admin/layouts/partials/topbar') ?>
        <?= $this->include('admin/layouts/partials/content') ?>
    </div>
</div>

<?= $this->include('admin/layouts/partials/footer') ?>
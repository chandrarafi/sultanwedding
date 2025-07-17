<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor5">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="<?= csrf_hash() ?>" />
    <meta name="csrf_header" content="X-CSRF-TOKEN" />
    <!--favicon-->
    <link rel="icon" href="<?= base_url('assets/images/favicon-32x32.png') ?>" type="image/png" />

    <!-- Styles -->
    <?= $this->include('admin/layouts/partials/styles') ?>

    <title>Sultan Wedding - Admin Panel</title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?= $this->include('admin/layouts/partials/sidebar') ?>
        <!--end sidebar wrapper -->

        <!--start header -->
        <?= $this->include('admin/layouts/partials/topbar') ?>
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <?= $this->include('admin/layouts/partials/content') ?>
            </div>
        </div>
        <!--end page wrapper -->

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <?= $this->include('admin/layouts/partials/footer') ?>
    </div>
    <!--end wrapper-->

    <!-- Scripts -->
    <?= $this->include('admin/layouts/partials/scripts') ?>
</body>

</html>
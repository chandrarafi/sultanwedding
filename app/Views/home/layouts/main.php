<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sultan Wedding' ?> - Sultan Wedding Organizer</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Konfigurasi Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            200: '#fbcfe8',
                            300: '#f9a8d4',
                            400: '#f472b6',
                            500: '#ec4899',
                            600: '#db2777',
                            700: '#be185d',
                            800: '#9d174d',
                            900: '#831843',
                            950: '#500724',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Style -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Fix for dropdown menu z-index issue */
        nav {
            position: relative;
            z-index: 50;
        }

        .dropdown-menu {
            z-index: 100;
        }

        /* Ensure hero section stays below navbar */
        section.relative {
            z-index: 10;
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>

<body class="bg-gray-50">
    <!-- Header/Navbar -->
    <?= $this->include('home/layouts/partials/navbar') ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?= $this->include('home/layouts/partials/footer') ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flash Messages -->
    <script>
        $(document).ready(function() {
            <?php if (session()->getFlashdata('success')) : ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '<?= session()->getFlashdata('success') ?>',
                    timer: 1500,
                    showConfirmButton: false
                });
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= session()->getFlashdata('error') ?>'
                });
            <?php endif; ?>
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
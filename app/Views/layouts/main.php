<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sultan Wedding' ?> - Sultan Wedding Organizer</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon-32x32.png') ?>">

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
            color: var(--secondary-700);
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Custom Styles */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>

<body class="bg-gray-50">
    <!-- Header/Navbar -->
    <?= $this->include('layouts/partials/navbar') ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <?= $this->include('layouts/partials/footer') ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>

    <!-- Update Cart Count -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to update cart count from session
            function updateCartCount() {
                <?php if (session()->get('logged_in') === true): ?>
                    fetch('<?= site_url('sewa/cart/count') ?>')
                        .then(response => response.json())
                        .then(data => {
                            const cartCountElements = document.querySelectorAll('.cart-count');
                            cartCountElements.forEach(element => {
                                if (data.count > 0) {
                                    element.textContent = data.count;
                                    element.classList.remove('hidden');
                                } else {
                                    element.classList.add('hidden');
                                }
                            });
                        })
                        .catch(error => console.error('Error updating cart count:', error));
                <?php endif; ?>
            }

            // Call on page load
            updateCartCount();

            // Set interval to update periodically
            setInterval(updateCartCount, 30000); // Update every 30 seconds

            // Listen for custom event that might be triggered after cart operations
            document.addEventListener('cartUpdated', function() {
                updateCartCount();
            });
        });
    </script>

    <!-- Notification Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Check for rejected payments notifications
            function checkRejectedPayments() {
                <?php if (session()->get('logged_in') === true && session()->get('role') === 'pelanggan') : ?>
                    fetch('<?= site_url('pelanggan/pemesanan/check-rejected-payments') ?>')
                        .then(response => response.json())
                        .then(data => {
                            if (data.status && data.count > 0) {
                                // Update notification count in all Alpine components
                                document.querySelectorAll('[x-data]').forEach(el => {
                                    if (el.__x) {
                                        try {
                                            if (typeof el.__x.getUnobservedData === 'function') {
                                                const alpineData = el.__x.getUnobservedData();
                                                if ('count' in alpineData) {
                                                    el.__x.updateData('count', data.count);
                                                }
                                            }
                                        } catch (e) {
                                            console.error('Error updating Alpine data:', e);
                                        }
                                    }
                                });

                                // Show notification if there are new ones
                                if (data.count > 0 && !localStorage.getItem('notification_shown')) {
                                    Swal.fire({
                                        title: 'Pembayaran Ditolak',
                                        text: 'Anda memiliki ' + data.count + ' pembayaran yang ditolak. Silahkan cek notifikasi.',
                                        icon: 'warning',
                                        confirmButtonText: 'OK'
                                    });
                                    localStorage.setItem('notification_shown', 'true');

                                    // Clear notification_shown flag after 1 hour
                                    setTimeout(() => {
                                        localStorage.removeItem('notification_shown');
                                    }, 3600000); // 1 hour in milliseconds
                                }
                            }
                        })
                        .catch(error => console.error('Error checking notifications:', error));
                <?php endif; ?>
            }

            // Function to fetch notifications (called when clicking the bell)
            window.fetchNotifications = function() {
                <?php if (session()->get('logged_in') === true && session()->get('role') === 'pelanggan') : ?>
                    fetch('<?= site_url('pelanggan/pemesanan/check-rejected-payments') ?>')
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {
                                // Update Alpine.js data
                                document.querySelectorAll('[x-data]').forEach(el => {
                                    if (el.__x) {
                                        try {
                                            if (typeof el.__x.getUnobservedData === 'function') {
                                                const alpineData = el.__x.getUnobservedData();
                                                if ('notifications' in alpineData) {
                                                    el.__x.updateData('notifications', data.notifications);
                                                    el.__x.updateData('count', data.count);
                                                }
                                            } else if (el.__x.$data) {
                                                // Alpine.js v3 compatibility
                                                if ('notifications' in el.__x.$data) {
                                                    el.__x.$data.notifications = data.notifications;
                                                    el.__x.$data.count = data.count;
                                                }
                                            }
                                        } catch (e) {
                                            console.error('Error updating Alpine data:', e);
                                        }
                                    }
                                });
                            }
                        })
                        .catch(error => console.error('Error fetching notifications:', error));
                <?php endif; ?>
            };

            // Check for notifications on page load
            checkRejectedPayments();

            // Check for new notifications every 5 minutes
            setInterval(checkRejectedPayments, 300000); // 5 minutes
        });
    </script>

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

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');

                    // Toggle icons
                    const menuIcon = mobileMenuButton.querySelector('svg:first-child');
                    const closeIcon = mobileMenuButton.querySelector('svg:last-child');

                    if (menuIcon && closeIcon) {
                        menuIcon.classList.toggle('hidden');
                        closeIcon.classList.toggle('hidden');
                    }
                });
            }
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
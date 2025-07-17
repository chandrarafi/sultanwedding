<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sultan Wedding Organizer</title>

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

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .register-bg {
            background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side - Image -->
        <div class="hidden md:block md:w-1/2 register-bg">
            <div class="h-full w-full bg-black bg-opacity-50 flex items-center justify-center p-12">
                <div class="text-center">
                    <h1 class="text-4xl font-serif font-bold text-white mb-6">Sultan Wedding</h1>
                    <p class="text-white text-lg mb-8">Wujudkan pernikahan impian Anda bersama kami</p>
                    <div class="w-24 h-1 bg-primary-500 mx-auto"></div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-6 md:p-12">
            <div class="w-full max-w-md">
                <!-- Logo for Mobile -->
                <div class="md:hidden text-center mb-8">
                    <h1 class="text-3xl font-serif font-bold text-secondary-800">Sultan Wedding</h1>
                    <div class="w-24 h-1 bg-primary-500 mx-auto mt-2"></div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-serif font-bold text-secondary-800">Daftar Akun Baru</h2>
                        <p class="text-secondary-500 mt-2">Lengkapi data diri Anda untuk mendaftar</p>
                    </div>

                    <!-- Alert untuk error -->
                    <div id="registerError" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 hidden" role="alert">
                        <span id="errorMessage"></span>
                    </div>

                    <form id="registerForm" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-5">
                            <label for="name" class="block text-secondary-700 font-medium mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary-500">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="w-full pl-10 pr-4 py-3 border border-secondary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="name" name="name" required placeholder="Masukkan nama lengkap">
                            </div>
                            <p class="text-red-600 text-sm mt-1 hidden" id="name-error"></p>
                        </div>

                        <div class="mb-5">
                            <label for="username" class="block text-secondary-700 font-medium mb-2">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary-500">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input type="text" class="w-full pl-10 pr-4 py-3 border border-secondary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="username" name="username" required placeholder="Masukkan username">
                            </div>
                            <p class="text-red-600 text-sm mt-1 hidden" id="username-error"></p>
                        </div>

                        <div class="mb-5">
                            <label for="email" class="block text-secondary-700 font-medium mb-2">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary-500">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="w-full pl-10 pr-4 py-3 border border-secondary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="email" name="email" required placeholder="Masukkan email">
                            </div>
                            <p class="text-red-600 text-sm mt-1 hidden" id="email-error"></p>
                        </div>

                        <div class="mb-5">
                            <label for="nohp" class="block text-secondary-700 font-medium mb-2">Nomor HP</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary-500">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="text" class="w-full pl-10 pr-4 py-3 border border-secondary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="nohp" name="nohp" required placeholder="Masukkan nomor HP">
                            </div>
                            <p class="text-red-600 text-sm mt-1 hidden" id="nohp-error"></p>
                        </div>

                        <div class="mb-5">
                            <label for="alamat" class="block text-secondary-700 font-medium mb-2">Alamat</label>
                            <div class="relative">
                                <span class="absolute top-3 left-0 flex items-center pl-3 text-secondary-500">
                                    <i class="fas fa-home"></i>
                                </span>
                                <textarea class="w-full pl-10 pr-4 py-3 border border-secondary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                            </div>
                            <p class="text-red-600 text-sm mt-1 hidden" id="alamat-error"></p>
                        </div>

                        <div class="mb-5">
                            <label for="password" class="block text-secondary-700 font-medium mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary-500">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="w-full pl-10 pr-10 py-3 border border-secondary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="password" name="password" required placeholder="Masukkan password">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-secondary-500 hover:text-secondary-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-red-600 text-sm mt-1 hidden" id="password-error"></p>
                        </div>

                        <div class="mb-6">
                            <label for="password_confirm" class="block text-secondary-700 font-medium mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-secondary-500">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="w-full pl-10 pr-10 py-3 border border-secondary-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" id="password_confirm" name="password_confirm" required placeholder="Konfirmasi password">
                                <button type="button" id="togglePasswordConfirm" class="absolute inset-y-0 right-0 flex items-center pr-3 text-secondary-500 hover:text-secondary-700">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-red-600 text-sm mt-1 hidden" id="password_confirm-error"></p>
                        </div>

                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-4 rounded-md transition duration-300 flex items-center justify-center" id="btnRegister">
                            <i class="fas fa-user-plus mr-2"></i>
                            Daftar Sekarang
                        </button>

                        <div class="text-center mt-6">
                            <p class="text-secondary-600">
                                Sudah punya akun?
                                <a href="<?= site_url('auth/login') ?>" class="text-primary-600 hover:text-primary-800 font-medium">Login di sini</a>
                            </p>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-8">
                    <a href="<?= site_url() ?>" class="text-secondary-600 hover:text-secondary-800 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-5 rounded-lg shadow-lg flex items-center">
            <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-primary-600 mr-3"></div>
            <span class="text-secondary-800">Memproses...</span>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Toggle Password Visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Toggle Password Confirm Visibility
            $('#togglePasswordConfirm').on('click', function() {
                const passwordInput = $('#password_confirm');
                const icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Clear error on input
            $('input, textarea').on('input', function() {
                const id = $(this).attr('id');
                $(`#${id}-error`).addClass('hidden').text('');
            });

            // Handle form submission
            $('#registerForm').submit(function(e) {
                e.preventDefault();

                // Hide previous errors
                $('#registerError').addClass('hidden');
                $('.text-red-600').addClass('hidden').text('');

                // Show loading overlay
                $('#loadingOverlay').removeClass('hidden');

                // Disable button
                $('#btnRegister').html('<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...');
                $('#btnRegister').prop('disabled', true);

                const formData = $(this).serialize();

                $.ajax({
                    url: '<?= site_url('auth/registerProcess') ?>',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="<?= csrf_token() ?>"]').val()
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Registrasi Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function() {
                                // Redirect
                                window.location.href = response.redirect;
                            });
                        } else {
                            // Hide loading overlay
                            $('#loadingOverlay').addClass('hidden');

                            // Reset button
                            $('#btnRegister').html('<i class="fas fa-user-plus mr-2"></i> Daftar Sekarang');
                            $('#btnRegister').prop('disabled', false);

                            // Show error message
                            if (response.errors) {
                                // Show field errors
                                $.each(response.errors, function(field, message) {
                                    $(`#${field}-error`).removeClass('hidden').text(message);
                                });

                                // Show general error
                                $('#errorMessage').text('Silakan perbaiki kesalahan pada form.');
                                $('#registerError').removeClass('hidden');
                            } else {
                                // Show general error
                                $('#errorMessage').text(response.message);
                                $('#registerError').removeClass('hidden');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        // Hide loading overlay
                        $('#loadingOverlay').addClass('hidden');

                        // Reset button
                        $('#btnRegister').html('<i class="fas fa-user-plus mr-2"></i> Daftar Sekarang');
                        $('#btnRegister').prop('disabled', false);

                        // Show error message
                        $('#errorMessage').text('Terjadi kesalahan. Silakan coba lagi.');
                        $('#registerError').removeClass('hidden');
                    }
                });
            });
        });
    </script>
</body>

</html>
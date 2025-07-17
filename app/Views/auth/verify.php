<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun - Sultan Wedding</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Verifikasi Akun</h2>
                <p class="text-sm text-gray-600">Silakan masukkan kode OTP yang telah dikirim ke email Anda</p>
            </div>

            <div class="mt-8">
                <form id="verifyForm" class="space-y-6" action="<?= site_url('auth/verifyProcess') ?>" method="POST">
                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                    <?= csrf_field() ?>

                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700">Kode OTP</label>
                        <div class="mt-2">
                            <input
                                type="text"
                                name="otp"
                                id="otp"
                                maxlength="6"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-center tracking-widest text-xl"
                                placeholder="000000"
                                required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <p id="countdown" class="text-gray-600">Kode akan berakhir dalam: <span id="timer">24:00:00</span></p>
                        </div>
                        <div class="text-sm">
                            <a href="javascript:void(0)" id="resendBtn" class="font-medium text-indigo-600 hover:text-indigo-500 disabled:text-gray-400">
                                Kirim ulang kode
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" id="verifyBtn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-500 hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-400">
                            Verifikasi
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?= site_url('auth/login') ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Kembali ke halaman login
                        </a>
                    </div>
                </form>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer" class="mt-4 hidden">
                <div id="alertContent" class="p-4 rounded-md"></div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Form submission
            $('#verifyForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();
                const submitBtn = $('#verifyBtn');

                // Disable button and show loading state
                submitBtn.prop('disabled', true).html('<span class="inline-block animate-spin mr-2">⟳</span> Memproses...');

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="<?= csrf_token() ?>"]').val()
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showAlert('success', response.message);
                            // Redirect after 2 seconds
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            showAlert('error', response.message);
                            submitBtn.prop('disabled', false).text('Verifikasi');
                        }
                    },
                    error: function() {
                        showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        submitBtn.prop('disabled', false).text('Verifikasi');
                    }
                });
            });

            // Resend OTP
            $('#resendBtn').on('click', function() {
                const userId = $('input[name="user_id"]').val();
                const resendBtn = $(this);

                // Disable button and show loading state
                resendBtn.prop('disabled', true).text('Mengirim...');

                $.ajax({
                    type: 'POST',
                    url: '<?= site_url('auth/resendOTP') ?>',
                    data: {
                        user_id: userId,
                        '<?= csrf_token() ?>': $('input[name="<?= csrf_token() ?>"]').val()
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="<?= csrf_token() ?>"]').val()
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showAlert('success', response.message);
                            // Disable button for 60 seconds
                            let countdown = 60;
                            const countdownInterval = setInterval(function() {
                                resendBtn.text(`Tunggu (${countdown}s)`);
                                countdown--;

                                if (countdown < 0) {
                                    clearInterval(countdownInterval);
                                    resendBtn.prop('disabled', false).text('Kirim ulang kode');
                                }
                            }, 1000);
                        } else {
                            showAlert('error', response.message);
                            resendBtn.prop('disabled', false).text('Kirim ulang kode');
                        }
                    },
                    error: function() {
                        showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        resendBtn.prop('disabled', false).text('Kirim ulang kode');
                    }
                });
            });

            // Display alert message
            function showAlert(type, message) {
                const alertContainer = $('#alertContainer');
                const alertContent = $('#alertContent');

                alertContainer.removeClass('hidden');

                if (type === 'success') {
                    alertContent.removeClass('bg-red-50 text-red-800').addClass('bg-green-50 text-green-800');
                } else {
                    alertContent.removeClass('bg-green-50 text-green-800').addClass('bg-red-50 text-red-800');
                }

                alertContent.text(message);

                // Auto hide after 5 seconds
                setTimeout(function() {
                    alertContainer.addClass('hidden');
                }, 5000);
            }

            // Simple timer countdown (24 hours from now)
            function updateTimer() {
                const now = new Date();
                const tomorrow = new Date(now);
                tomorrow.setDate(tomorrow.getDate() + 1);

                const timeDiff = tomorrow - now;
                const hours = Math.floor(timeDiff / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

                $('#timer').text(
                    (hours < 10 ? '0' + hours : hours) + ':' +
                    (minutes < 10 ? '0' + minutes : minutes) + ':' +
                    (seconds < 10 ? '0' + seconds : seconds)
                );
            }

            // Update timer every second
            setInterval(updateTimer, 1000);
            updateTimer();
        });
    </script>
</body>

</html>
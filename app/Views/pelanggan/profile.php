<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-secondary-900">Profil Saya</h1>
            <p class="mt-2 text-lg text-secondary-600">
                Kelola informasi profil dan akun Anda
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="md:flex">
                <!-- Sidebar -->
                <div class="w-full md:w-64 bg-gray-50 p-6 border-r border-gray-200">
                    <div class="flex flex-col items-center text-center mb-6">
                        <div class="h-24 w-24 rounded-full bg-primary-600 text-white flex items-center justify-center text-3xl font-semibold mb-4">
                            <?= substr($user['name'] ?? 'User', 0, 1) ?>
                        </div>
                        <h3 class="text-xl font-semibold text-secondary-900"><?= $user['name'] ?? 'User' ?></h3>
                        <p class="text-secondary-600 text-sm mt-1"><?= $user['email'] ?? '' ?></p>
                    </div>

                    <div class="space-y-1">
                        <a href="#personal-info" class="block px-4 py-2 bg-primary-50 text-primary-700 rounded-md font-medium">
                            <i class="fas fa-user mr-2"></i> Informasi Pribadi
                        </a>
                        <a href="#account-info" class="block px-4 py-2 text-secondary-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-lock mr-2"></i> Informasi Akun
                        </a>
                        <a href="<?= site_url('pelanggan/pemesanan') ?>" class="block px-4 py-2 text-secondary-700 hover:bg-gray-100 rounded-md">
                            <i class="fas fa-shopping-bag mr-2"></i> Riwayat Pemesanan
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 p-6">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc pl-5">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('updateProfile') ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Informasi Pribadi -->
                        <div id="personal-info" class="mb-8">
                            <h2 class="text-xl font-semibold text-secondary-900 mb-4 pb-2 border-b border-gray-200">
                                Informasi Pribadi
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="namapelanggan" class="block text-sm font-medium text-secondary-700 mb-1">
                                        Nama Lengkap
                                    </label>
                                    <input type="text" id="namapelanggan" name="namapelanggan" value="<?= $pelanggan['namapelanggan'] ?? old('namapelanggan') ?? $user['name'] ?? '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" required>
                                </div>

                                <div>
                                    <label for="nohp" class="block text-sm font-medium text-secondary-700 mb-1">
                                        Nomor Telepon
                                    </label>
                                    <input type="text" id="nohp" name="nohp" value="<?= $pelanggan['nohp'] ?? old('nohp') ?? '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" required>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm font-medium text-secondary-700 mb-1">
                                        Alamat
                                    </label>
                                    <textarea id="alamat" name="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500" required><?= $pelanggan['alamat'] ?? old('alamat') ?? '' ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Akun -->
                        <div id="account-info" class="mb-8">
                            <h2 class="text-xl font-semibold text-secondary-900 mb-4 pb-2 border-b border-gray-200">
                                Informasi Akun
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-secondary-700 mb-1">
                                        Email
                                    </label>
                                    <input type="email" id="email" name="email" value="<?= $user['email'] ?? old('email') ?? '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500 bg-gray-50" readonly>
                                    <p class="text-xs text-secondary-500 mt-1">Email tidak dapat diubah</p>
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-secondary-700 mb-1">
                                        Password Baru (opsional)
                                    </label>
                                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                                    <p class="text-xs text-secondary-500 mt-1">Biarkan kosong jika tidak ingin mengubah password</p>
                                </div>

                                <div>
                                    <label for="password_confirm" class="block text-sm font-medium text-secondary-700 mb-1">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" id="password_confirm" name="password_confirm" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-primary-600 text-white font-medium rounded-md hover:bg-primary-700 transition-colors duration-300">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Toggle between sections
        $('a[href="#personal-info"]').click(function(e) {
            e.preventDefault();
            $(this).addClass('bg-primary-50 text-primary-700').removeClass('text-secondary-700 hover:bg-gray-100');
            $('a[href="#account-info"]').removeClass('bg-primary-50 text-primary-700').addClass('text-secondary-700 hover:bg-gray-100');
            $('#personal-info').show();
            $('#account-info').hide();
        });

        $('a[href="#account-info"]').click(function(e) {
            e.preventDefault();
            $(this).addClass('bg-primary-50 text-primary-700').removeClass('text-secondary-700 hover:bg-gray-100');
            $('a[href="#personal-info"]').removeClass('bg-primary-50 text-primary-700').addClass('text-secondary-700 hover:bg-gray-100');
            $('#account-info').show();
            $('#personal-info').hide();
        });

        // Password validation
        $('form').submit(function(e) {
            const password = $('#password').val();
            const passwordConfirm = $('#password_confirm').val();

            if (password && password !== passwordConfirm) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Password dan konfirmasi password tidak cocok'
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>
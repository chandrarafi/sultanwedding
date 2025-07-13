<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-serif font-bold text-secondary-800 mb-6">Notifikasi</h1>

        <?php if (empty($notifications)): ?>
            <div class="bg-gray-50 p-8 rounded-lg border border-gray-200 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-secondary-700 mb-2">Tidak Ada Notifikasi</h3>
                    <p class="text-secondary-500">Anda tidak memiliki notifikasi saat ini.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($notifications as $notification): ?>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div class="p-5">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <?php if ($notification['payment_stage'] === 'dp'): ?>
                                        <div class="h-10 w-10 rounded-full bg-red-100 text-red-500 flex items-center justify-center">
                                            <i class="fas fa-times-circle text-xl"></i>
                                        </div>
                                    <?php elseif ($notification['payment_stage'] === 'h1'): ?>
                                        <div class="h-10 w-10 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center">
                                            <i class="fas fa-exclamation-circle text-xl"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center">
                                            <i class="fas fa-exclamation-circle text-xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-medium text-secondary-900"><?= $notification['title'] ?></h3>
                                        <span class="text-xs text-secondary-500"><?= $notification['time'] ?></span>
                                    </div>
                                    <p class="text-secondary-700 mt-1"><?= $notification['message'] ?></p>
                                    <p class="text-red-600 font-medium mt-2"><?= $notification['action_required'] ?></p>
                                    <div class="mt-3">
                                        <a href="<?= $notification['action_url'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <?php if ($notification['payment_stage'] === 'dp'): ?>
                                                <i class="fas fa-shopping-cart mr-2"></i> Pesan Paket Baru
                                            <?php else: ?>
                                                <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Master Data</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item"><a href="<?= site_url('admin/paket') ?>">Paket</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="<?= base_url('admin/paket') ?>" class="btn btn-light px-3 radius-30">
            <i class="bx bx-arrow-back"></i>Kembali
        </a>
    </div>
</div>

<?php if (session()->has('errors')) : ?>
    <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-danger"><i class="bx bx-x-circle"></i></div>
            <div class="ms-3">
                <h6 class="mb-0 text-danger">Error</h6>
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="border p-4 rounded">
            <div class="card-title d-flex align-items-center">
                <h5 class="mb-0">Form Tambah Paket</h5>
            </div>
            <hr />
            <form action="<?= base_url('admin/paket/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="namapaket" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="namapaket" name="namapaket" value="<?= old('namapaket') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="kdkategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select" id="kdkategori" name="kdkategori" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($kategori as $k) : ?>
                                <option value="<?= $k['kdkategori'] ?>" <?= old('kdkategori') == $k['kdkategori'] ? 'selected' : '' ?>>
                                    <?= $k['namakategori'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control" id="harga" name="harga" value="<?= old('harga') ?>" min="0" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="kdbarang" class="form-label">Barang (Opsional)</label>
                        <select class="form-select" id="kdbarang" name="kdbarang">
                            <option value="">Pilih Barang</option>
                            <?php foreach ($barang as $b) : ?>
                                <option value="<?= $b['kdbarang'] ?>" <?= old('kdbarang') == $b['kdbarang'] ? 'selected' : '' ?>>
                                    <?= $b['namabarang'] ?> - <?= $b['satuan'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="detailpaket" class="form-label">Detail Paket</label>
                    <textarea class="form-control" id="detailpaket" name="detailpaket" rows="5"><?= old('detailpaket') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Paket</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    <div class="form-text">Format: JPG, JPEG, PNG. Maks: 2MB.</div>
                </div>

                <div class="preview-container mt-3 mb-3 d-none">
                    <label class="form-label">Preview</label>
                    <div class="text-center">
                        <img id="imagePreview" class="rounded" style="max-height: 200px;">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <button type="reset" class="btn btn-secondary px-4">Reset</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Image preview
        $('#foto').change(function() {
            const file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#imagePreview').attr('src', event.target.result);
                    $('.preview-container').removeClass('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                $('.preview-container').addClass('d-none');
            }
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Paket</h1>
        <a href="<?= base_url('admin/paket') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <?php if (session()->has('errors')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Paket</h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/paket/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="namapaket" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="namapaket" name="namapaket" value="<?= old('namapaket') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="kdkategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-control" id="kdkategori" name="kdkategori" required>
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
                        <select class="form-control" id="kdbarang" name="kdbarang">
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
                        <img id="imagePreview" class="img-fluid img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
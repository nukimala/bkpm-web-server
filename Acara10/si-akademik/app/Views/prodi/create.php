<?php $title = 'Tambah Prodi'; ?>
<h1>Tambah Prodi</h1>
<?php $error = flash('flash_error'); ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form action="<?= BASE_PATH ?>/prodi/store" method="POST" class="mt-3" style="max-width: 640px;">
    <div class="mb-3">
        <label for="kode" class="form-label">Kode Prodi</label>
        <input type="text" class="form-control" id="kode" name="kode" maxlength="10" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Prodi</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= BASE_PATH ?>/prodi" class="btn btn-secondary">Batal</a>
</form>
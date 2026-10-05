<?php $title = 'Edit Matakuliah'; ?>
<h1>Edit Matakuliah</h1>
<?php $error = flash('flash_error'); ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form action="<?= BASE_PATH ?>/matakuliah/update/<?php echo $matakuliah['id']; ?>" method="POST" class="mt-3" style="max-width: 640px;">
    <div class="mb-3">
        <label for="kode" class="form-label">Kode Matakuliah</label>
        <input type="text" class="form-control" id="kode" name="kode" maxlength="10"
               value="<?php echo htmlspecialchars($matakuliah['kode']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Matakuliah</label>
        <input type="text" class="form-control" id="nama" name="nama"
               value="<?php echo htmlspecialchars($matakuliah['nama']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="sks" class="form-label">SKS</label>
        <input type="number" class="form-control" id="sks" name="sks" min="1" max="6"
               value="<?= (int) $matakuliah['sks']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="prodi_id" class="form-label">Prodi</label>
        <select class="form-select" id="prodi_id" name="prodi_id" required>
            <?php foreach ($prodi as $p): ?>
                <option value="<?php echo $p['id']; ?>"
                    <?php if ((int) $p['id'] === (int) $matakuliah['prodi_id']): ?>selected<?php endif; ?>>
                    <?php echo htmlspecialchars($p['kode'] . ' - ' . $p['nama']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= BASE_PATH ?>/matakuliah" class="btn btn-secondary">Batal</a>
</form>
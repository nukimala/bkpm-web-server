<?php $title = 'Edit Mahasiswa'; ?>
<h1>Edit Mahasiswa</h1>
<?php $error = flash('flash_error'); ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form action="<?= BASE_PATH ?>/mahasiswa/update/<?php echo $mhs->getId(); ?>" method="POST" class="mt-3" style="max-width: 640px;">
    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input type="text" class="form-control" id="nim" name="nim" maxlength="20"
               value="<?php echo htmlspecialchars($mhs->getNim()); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama"
               value="<?php echo htmlspecialchars($mhs->getNama()); ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?php echo htmlspecialchars($mhs->getEmail()); ?>" required>
    </div>
    <div class="mb-3">
        <label for="prodi_id" class="form-label">Prodi</label>
        <select class="form-select" id="prodi_id" name="prodi_id" required>
            <?php foreach ($prodi as $p): ?>
                <option value="<?php echo $p['id']; ?>"
                    <?php if ((int) $p['id'] === $mhs->getProdiId()): ?>selected<?php endif; ?>>
                    <?php echo htmlspecialchars($p['kode'] . ' - ' . $p['nama']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="angkatan" class="form-label">Angkatan</label>
        <input type="number" class="form-control" id="angkatan" name="angkatan"
               min="2000" max="<?= (int) date('Y') + 1; ?>" value="<?php echo $mhs->getAngkatan(); ?>" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status">
            <option value="aktif" <?php if ($mhs->getStatus() === 'aktif'): ?>selected<?php endif; ?>>Aktif</option>
            <option value="cuti"  <?php if ($mhs->getStatus() === 'cuti'): ?>selected<?php endif; ?>>Cuti</option>
            <option value="lulus" <?php if ($mhs->getStatus() === 'lulus'): ?>selected<?php endif; ?>>Lulus</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= BASE_PATH ?>/mahasiswa" class="btn btn-secondary">Batal</a>
</form>
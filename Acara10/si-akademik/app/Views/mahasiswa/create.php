<?php $title = 'Tambah Mahasiswa'; ?>
<h1>Tambah Mahasiswa</h1>
<?php $error = flash('flash_error'); ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<form action="<?= BASE_PATH ?>/mahasiswa/store" method="POST" class="mt-3" style="max-width: 640px;">
    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input type="text" class="form-control" id="nim" name="nim" maxlength="20" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="prodi_id" class="form-label">Prodi</label>
        <select class="form-select" id="prodi_id" name="prodi_id" required>
            <option value="">-- Pilih Prodi --</option>
            <?php foreach ($prodi as $p): ?>
                <option value="<?php echo $p['id']; ?>">
                    <?php echo htmlspecialchars($p['kode'] . ' - ' . $p['nama']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="angkatan" class="form-label">Angkatan</label>
        <input type="number" class="form-control" id="angkatan" name="angkatan"
               min="2000" max="<?= (int) date('Y'); ?>" value="<?= (int) date('Y'); ?>" required>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status">
            <option value="aktif">Aktif</option>
            <option value="cuti">Cuti</option>
            <option value="lulus">Lulus</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= BASE_PATH ?>/mahasiswa" class="btn btn-secondary">Batal</a>
</form>
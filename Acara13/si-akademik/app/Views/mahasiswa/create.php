<?php $title = 'Tambah Mahasiswa';
require_once __DIR__ . '/../../Core/Session.php';
$errors = Session::get('errors', []);
?>
<h1>Tambah Mahasiswa</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <strong>Terdapat kesalahan pada isian:</strong>
        <ul class="mb-0 mt-1">
            <?php foreach ($errors as $messages): ?>
                <?php foreach ($messages as $msg): ?>
                    <li><?php echo htmlspecialchars($msg); ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= BASE_PATH ?>/mahasiswa/store" method="POST" class="mt-3" style="max-width: 640px;">
    <div class="mb-3">
        <label for="nim" class="form-label">NIM</label>
        <input type="text" class="form-control" id="nim" name="nim"
               value="<?php echo htmlspecialchars(Session::old('nim')); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama" name="nama"
               value="<?php echo htmlspecialchars(Session::old('nama')); ?>" required>
    </div>
    <div class="mb-3">
        <label for="prodi_id" class="form-label">Prodi</label>
        <select class="form-select" id="prodi_id" name="prodi_id" required>
            <option value="">-- Pilih Prodi --</option>
            <?php foreach ($prodi as $p): ?>
                <option value="<?php echo $p['id']; ?>"
                    <?php if ((string)$p['id'] === Session::old('prodi_id')): ?>selected<?php endif; ?>>
                    <?php echo htmlspecialchars($p['nama']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status">
            <option value="aktif"    <?php if (Session::old('status', 'aktif') === 'aktif'): ?>selected<?php endif; ?>>Aktif</option>
            <option value="nonaktif" <?php if (Session::old('status') === 'nonaktif'): ?>selected<?php endif; ?>>Nonaktif</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="2"><?php echo htmlspecialchars(Session::old('alamat')); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= BASE_PATH ?>/mahasiswa" class="btn btn-secondary">Batal</a>
</form>
<?php Session::remove('errors');
Session::clearOld(); ?>
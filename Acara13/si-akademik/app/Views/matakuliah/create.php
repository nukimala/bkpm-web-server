<?php $title = 'Tambah Matakuliah';
require_once __DIR__ . '/../../Core/Session.php';
$errors = Session::get('errors', []);
?>
<h1>Tambah Matakuliah</h1>

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

<form action="<?= BASE_PATH ?>/matakuliah/store" method="POST" class="mt-3" style="max-width: 640px;">
    <div class="mb-3">
        <label for="kode" class="form-label">Kode Matakuliah</label>
        <input type="text" class="form-control" id="kode" name="kode"
               value="<?php echo htmlspecialchars(Session::old('kode')); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Matakuliah</label>
        <input type="text" class="form-control" id="nama" name="nama"
               value="<?php echo htmlspecialchars(Session::old('nama')); ?>" required>
    </div>
    <div class="mb-3">
        <label for="sks" class="form-label">SKS</label>
        <input type="number" class="form-control" id="sks" name="sks" min="1" max="6"
               value="<?php echo htmlspecialchars(Session::old('sks', '2')); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= BASE_PATH ?>/matakuliah" class="btn btn-secondary">Batal</a>
</form>
<?php Session::remove('errors');
Session::clearOld(); ?>
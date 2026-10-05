<?php $title = 'Edit Prodi';
require_once __DIR__ . '/../../Core/Session.php';
$errors = Session::get('errors', []);
?>
<h1>Edit Prodi</h1>

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

<form action="<?= BASE_PATH ?>/prodi/update/<?php echo $prodi['id']; ?>" method="POST" class="mt-3" style="max-width: 640px;">
    <div class="mb-3">
        <label for="kode" class="form-label">Kode Prodi</label>
        <input type="text" class="form-control" id="kode" name="kode" value="<?php echo htmlspecialchars($prodi['kode']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Prodi</label>
        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($prodi['nama']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="ka_prodi" class="form-label">Ketua Prodi</label>
        <input type="text" class="form-control" id="ka_prodi" name="ka_prodi" value="<?php echo htmlspecialchars($prodi['ka_prodi'] ?? ''); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= BASE_PATH ?>/prodi" class="btn btn-secondary">Batal</a>
</form>
<?php Session::remove('errors');
Session::clearOld(); ?>
<?php $title = 'Daftar Matakuliah'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Matakuliah</h1>
    <a href="<?= BASE_PATH ?>/matakuliah/create" class="btn btn-primary">Tambah Matakuliah</a>
</div>
<?php $msg = flash(); ?>
<?php if ($msg !== ''): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Matakuliah</th>
            <th>SKS</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($matakuliah)): ?>
            <tr><td colspan="5" class="text-center">Belum ada data matakuliah.</td></tr>
        <?php else: ?>
            <?php foreach ($matakuliah as $i => $mk): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($mk['kode']); ?></td>
                    <td><?php echo htmlspecialchars($mk['nama']); ?></td>
                    <td><?php echo (int)$mk['sks']; ?></td>
                    <td class="text-nowrap">
                        <a href="<?= BASE_PATH ?>/matakuliah/edit/<?php echo $mk['id']; ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                        <form method="POST" action="<?= BASE_PATH ?>/matakuliah/delete/<?php echo $mk['id']; ?>"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus matakuliah ini?');">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
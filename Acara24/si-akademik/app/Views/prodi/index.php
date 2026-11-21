<?php $title = 'Daftar Prodi'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Prodi</h1>
    <a href="<?= BASE_PATH ?>/prodi/create" class="btn btn-primary">Tambah Prodi</a>
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
            <th>Nama Prodi</th>
            <th>Ketua Prodi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($prodi)): ?>
            <tr><td colspan="5" class="text-center">Belum ada data prodi.</td></tr>
        <?php else: ?>
            <?php foreach ($prodi as $i => $p): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($p['kode']); ?></td>
                    <td><?php echo htmlspecialchars($p['nama']); ?></td>
                    <td><?php echo htmlspecialchars($p['ka_prodi'] ?? ''); ?></td>
                    <td class="text-nowrap">
                        <a href="<?= BASE_PATH ?>/prodi/edit/<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                        <form method="POST" action="<?= BASE_PATH ?>/prodi/delete/<?php echo $p['id']; ?>"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus prodi ini?');">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
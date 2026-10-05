<?php $title = 'Daftar Matakuliah'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Matakuliah</h1>
    <a href="<?= BASE_PATH ?>/matakuliah/create" class="btn btn-primary">Tambah Matakuliah</a>
</div>
<?php $msg = flash(); ?>
<?php if ($msg !== ''): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php $error = flash('flash_error'); ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="GET" action="<?= BASE_PATH ?>/matakuliah" class="row g-2 mb-3" style="max-width: 640px;">
    <div class="col-8">
        <input type="text" name="search" class="form-control"
               placeholder="Cari kode / nama matakuliah / prodi" value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    </div>
    <div class="col-4">
        <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
    </div>
</form>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Matakuliah</th>
            <th>SKS</th>
            <th>Prodi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($matakuliah)): ?>
            <tr><td colspan="6" class="text-center">
                <?php if (($keyword ?? '') !== ''): ?>
                    Data tidak ditemukan untuk keyword tersebut.
                <?php else: ?>
                    Belum ada data matakuliah.
                <?php endif; ?>
            </td></tr>
        <?php else: ?>
            <?php foreach ($matakuliah as $i => $mk): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?php echo htmlspecialchars($mk['kode']); ?></td>
                    <td><?php echo htmlspecialchars($mk['nama']); ?></td>
                    <td><?= (int) $mk['sks']; ?></td>
                    <td><?php echo htmlspecialchars($mk['prodi']); ?></td>
                    <td class="text-nowrap">
                        <a href="<?= BASE_PATH ?>/matakuliah/edit/<?php echo (int) $mk['id']; ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                        <form method="POST" action="<?= BASE_PATH ?>/matakuliah/delete/<?php echo (int) $mk['id']; ?>"
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

<p class="text-muted">Menampilkan <?= count($matakuliah) ?> dari <?= (int) $total ?> data.</p>

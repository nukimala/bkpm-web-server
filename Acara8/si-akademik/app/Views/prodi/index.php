<?php $title = 'Daftar Prodi'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Prodi</h1>
    <a href="<?= BASE_PATH ?>/prodi/create" class="btn btn-primary">Tambah Prodi</a>
</div>
<?php $msg = flash(); ?>
<?php if ($msg !== ''): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php $error = flash('flash_error'); ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="GET" action="<?= BASE_PATH ?>/prodi" class="row g-2 mb-3" style="max-width: 520px;">
    <div class="col-8">
        <input type="text" name="search" class="form-control"
               placeholder="Cari kode / nama prodi" value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    </div>
    <div class="col-4">
        <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
    </div>
</form>

<table class="table table-bordered table-striped align-middle" style="max-width: 720px;">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Prodi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($prodi)): ?>
            <tr><td colspan="4" class="text-center">
                <?php if (($keyword ?? '') !== ''): ?>
                    Data tidak ditemukan untuk keyword tersebut.
                <?php else: ?>
                    Belum ada data prodi.
                <?php endif; ?>
            </td></tr>
        <?php else: ?>
            <?php foreach ($prodi as $i => $p): ?>
                <tr>
                    <td><?= $offset + $i + 1 ?></td>
                    <td><?php echo htmlspecialchars($p['kode']); ?></td>
                    <td><?php echo htmlspecialchars($p['nama']); ?></td>
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

<p class="text-muted">Menampilkan <?= count($prodi) ?> dari <?= (int) $total ?> data.</p>

<?php if ($pages > 1): ?>
    <nav aria-label="Navigasi halaman">
        <ul class="pagination">
            <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                <a class="page-link" href="<?= BASE_PATH ?>/prodi?<?= http_build_query(['search' => $keyword, 'page' => $page - 1]) ?>">Sebelumnya</a>
            </li>
            <?php for ($p = 1; $p <= $pages; $p++): ?>
                <li class="page-item<?= $p === $page ? ' active' : '' ?>">
                    <a class="page-link" href="<?= BASE_PATH ?>/prodi?<?= http_build_query(['search' => $keyword, 'page' => $p]) ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item<?= $page >= $pages ? ' disabled' : '' ?>">
                <a class="page-link" href="<?= BASE_PATH ?>/prodi?<?= http_build_query(['search' => $keyword, 'page' => $page + 1]) ?>">Berikutnya</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
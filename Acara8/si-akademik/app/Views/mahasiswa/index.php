<?php $title = 'Daftar Mahasiswa'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Mahasiswa</h1>
    <a href="<?= BASE_PATH ?>/mahasiswa/create" class="btn btn-primary">Tambah Mahasiswa</a>
</div>

<?php $msg = flash(); ?>
<?php if ($msg !== ''): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php $error = flash('flash_error'); ?>
<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="GET" action="<?= BASE_PATH ?>/mahasiswa" class="row g-2 mb-3" style="max-width: 640px;">
    <div class="col-8">
        <input type="text" name="search" class="form-control"
               placeholder="Cari NIM / Nama / Prodi" value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    </div>
    <div class="col-4">
        <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
    </div>
</form>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Prodi</th>
            <th>Angkatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($mahasiswa)): ?>
            <tr><td colspan="8" class="text-center">
                <?php if (($keyword ?? '') !== ''): ?>
                    Data tidak ditemukan untuk keyword tersebut.
                <?php else: ?>
                    Belum ada data mahasiswa.
                <?php endif; ?>
            </td></tr>
        <?php else: ?>
            <?php foreach ($mahasiswa as $i => $mhs): ?>
                <tr>
                    <td><?= $offset + $i + 1 ?></td>
                    <td><?php echo htmlspecialchars($mhs->getNim()); ?></td>
                    <td><?php echo htmlspecialchars($mhs->getNama()); ?></td>
                    <td><?php echo htmlspecialchars($mhs->getEmail()); ?></td>
                    <td><?php echo htmlspecialchars($mhs->getProdi()); ?></td>
                    <td><?php echo $mhs->getAngkatan(); ?></td>
                    <td>
                        <?php if ($mhs->getStatus() === 'aktif'): ?>
                            <span class="badge text-bg-success">Aktif</span>
                        <?php elseif ($mhs->getStatus() === 'cuti'): ?>
                            <span class="badge text-bg-warning">Cuti</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Lulus</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-nowrap">
                        <a href="<?= BASE_PATH ?>/mahasiswa/<?php echo $mhs->getNim(); ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                        <a href="<?= BASE_PATH ?>/mahasiswa/edit/<?php echo $mhs->getId(); ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                        <form method="POST" action="<?= BASE_PATH ?>/mahasiswa/delete/<?php echo $mhs->getId(); ?>"
                              class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus <?php echo htmlspecialchars($mhs->getNama()); ?>?');">
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<p class="text-muted">Menampilkan <?= count($mahasiswa) ?> dari <?= (int) $total ?> data.</p>

<?php if ($pages > 1): ?>
    <nav aria-label="Navigasi halaman">
        <ul class="pagination">
            <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                <a class="page-link" href="<?= BASE_PATH ?>/mahasiswa?<?= http_build_query(['search' => $keyword, 'page' => $page - 1]) ?>">Sebelumnya</a>
            </li>
            <?php for ($p = 1; $p <= $pages; $p++): ?>
                <li class="page-item<?= $p === $page ? ' active' : '' ?>">
                    <a class="page-link" href="<?= BASE_PATH ?>/mahasiswa?<?= http_build_query(['search' => $keyword, 'page' => $p]) ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item<?= $page >= $pages ? ' disabled' : '' ?>">
                <a class="page-link" href="<?= BASE_PATH ?>/mahasiswa?<?= http_build_query(['search' => $keyword, 'page' => $page + 1]) ?>">Berikutnya</a>
            </li>
        </ul>
    </nav>
<?php endif; ?>
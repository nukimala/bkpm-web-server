<?php $title = 'Daftar Mahasiswa'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Mahasiswa</h1>
    <a href="<?= BASE_PATH ?>/mahasiswa/create" class="btn btn-primary">Tambah Mahasiswa</a>
</div>

<!-- Form pencarian (Tugas Mandiri Acara 8) -->
<form method="GET" action="<?= BASE_PATH ?>/mahasiswa" class="row g-2 mb-3" style="max-width: 520px;">
    <div class="col-8">
        <input type="text" name="search" class="form-control"
               placeholder="Cari NIM / Nama / Prodi" value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    </div>
    <div class="col-4">
        <button type="submit" class="btn btn-outline-primary w-100">Cari</button>
    </div>
</form>

<?php $msg = flash(); ?>
<?php if ($msg !== ''): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Angkatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($mahasiswa)): ?>
            <tr><td colspan="7" class="text-center">Belum ada data mahasiswa.</td></tr>
        <?php else: ?>
            <?php foreach ($mahasiswa as $i => $mhs): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($mhs->getNim()); ?></td>
                    <td><?php echo htmlspecialchars($mhs->getNama()); ?></td>
                    <td><?php echo htmlspecialchars($mhs->getProdi()); ?></td>
                    <td><?php echo $mhs->getAngkatan(); ?></td>
                    <td>
                        <?php if ($mhs->getStatus() === 'aktif'): ?>
                            <span class="badge text-bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Nonaktif</span>
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
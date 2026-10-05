<?php $title = 'Daftar Mahasiswa'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Mahasiswa</h1>
    <a href="<?= BASE_PATH ?>/mahasiswa/create" class="btn btn-primary">Tambah Mahasiswa</a>
</div>
<p class="text-muted">Data diambil dari database <code><?= htmlspecialchars($namaDatabase ?? ''); ?></code> memakai PDO.</p>
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
            <tr><td colspan="8" class="text-center">Belum ada data mahasiswa.</td></tr>
        <?php else: ?>
            <?php foreach ($mahasiswa as $i => $mhs): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
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
                    <td>
                        <a href="<?= BASE_PATH ?>/mahasiswa/<?php echo $mhs->getNim(); ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
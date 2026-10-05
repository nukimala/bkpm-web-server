<?php $title = 'Daftar Mahasiswa'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Mahasiswa</h1>
    <a href="<?= $baseUrl ?>/mahasiswa/create" class="btn btn-primary">Tambah Mahasiswa</a>
</div>
<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Angkatan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mahasiswa as $i => $mhs): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars($mhs->getNim()); ?></td>
                <td><?php echo htmlspecialchars($mhs->getNama()); ?></td>
                <td><?php echo htmlspecialchars($mhs->getProdi()); ?></td>
                <td><?php echo $mhs->getAngkatan(); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
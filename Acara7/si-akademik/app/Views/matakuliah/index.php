<?php $title = 'Daftar Matakuliah'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Matakuliah</h1>
</div>
<p class="text-muted">Data diambil dari tabel <code>matakuliah</code>. Input matakuliah akan dibuat di acara berikutnya.</p>
<table class="table table-bordered table-striped align-middle" style="max-width: 720px;">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Matakuliah</th>
            <th>SKS</th>
            <th>Prodi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($matakuliah)): ?>
            <tr><td colspan="5" class="text-center">Belum ada data matakuliah.</td></tr>
        <?php else: ?>
            <?php foreach ($matakuliah as $i => $m): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($m['kode']); ?></td>
                    <td><?php echo htmlspecialchars($m['nama']); ?></td>
                    <td><?php echo htmlspecialchars($m['sks']); ?></td>
                    <td><?php echo htmlspecialchars($m['prodi']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<a href="<?= BASE_PATH ?>/dashboard" class="btn btn-secondary">&laquo; Kembali ke Dashboard</a>
<?php $title = 'Daftar Prodi'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Prodi</h1>
</div>
<p class="text-muted">Data diambil dari tabel <code>prodi</code>. Input prodi akan dibuat di acara berikutnya.</p>
<table class="table table-bordered table-striped align-middle" style="max-width: 720px;">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Prodi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($prodi)): ?>
            <tr><td colspan="3" class="text-center">Belum ada data prodi.</td></tr>
        <?php else: ?>
            <?php foreach ($prodi as $i => $p): ?>
                <tr>
                    <td><?php echo $i + 1; ?></td>
                    <td><?php echo htmlspecialchars($p['kode']); ?></td>
                    <td><?php echo htmlspecialchars($p['nama']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<a href="<?= BASE_PATH ?>/dashboard" class="btn btn-secondary">&laquo; Kembali ke Dashboard</a>
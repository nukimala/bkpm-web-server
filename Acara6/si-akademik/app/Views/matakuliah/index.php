<?php $title = 'Daftar Matakuliah'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Matakuliah</h1>
</div>
<p class="text-muted">Data masih di-hardcode. Input matakuliah akan dibuat di acara berikutnya.</p>
<table class="table table-bordered table-striped align-middle" style="max-width: 720px;">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Matakuliah</th>
            <th>SKS</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($matakuliah as $i => $m): ?>
            <tr>
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars($m['kode']); ?></td>
                <td><?php echo htmlspecialchars($m['nama']); ?></td>
                <td><?php echo htmlspecialchars($m['sks']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="<?= BASE_PATH ?>/dashboard" class="btn btn-secondary">&laquo; Kembali ke Dashboard</a>
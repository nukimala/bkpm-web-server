<?php $title = 'Detail Mahasiswa'; ?>
<h1>Detail Mahasiswa</h1>
<?php if ($mhs): ?>
    <table class="table table-bordered mt-3" style="max-width: 520px;">
        <tr>
            <th>NIM</th>
            <td><?php echo htmlspecialchars($mhs->getNim()); ?></td>
        </tr>
        <tr>
            <th>Nama</th>
            <td><?php echo htmlspecialchars($mhs->getNama()); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($mhs->getEmail()); ?></td>
        </tr>
        <tr>
            <th>Prodi</th>
            <td><?php echo htmlspecialchars($mhs->getProdi()); ?></td>
        </tr>
        <tr>
            <th>Angkatan</th>
            <td><?php echo $mhs->getAngkatan(); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <?php if ($mhs->getStatus() === 'aktif'): ?>
                    <span class="badge text-bg-success">Aktif</span>
                <?php elseif ($mhs->getStatus() === 'cuti'): ?>
                    <span class="badge text-bg-warning">Cuti</span>
                <?php else: ?>
                    <span class="badge text-bg-secondary">Lulus</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>
<?php else: ?>
    <div class="alert alert-warning mt-3">Data mahasiswa dengan NIM tersebut tidak ditemukan.</div>
<?php endif; ?>
<a href="<?= BASE_PATH ?>/mahasiswa" class="btn btn-secondary">&laquo; Kembali ke Daftar</a>
<?php $title = 'Detail Mahasiswa'; ?>
<h1>Detail Mahasiswa</h1>

<?php if ($mhs): ?>
    <table class="table table-bordered mt-3" style="max-width: 520px;">
        <tr>
            <th>NIM</th>
            <td><?php echo htmlspecialchars($mhs['nim']); ?></td>
        </tr>
        <tr>
            <th>Nama</th>
            <td><?php echo htmlspecialchars($mhs['nama']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($mhs['email']); ?></td>
        </tr>
        <tr>
            <th>Prodi</th>
            <td><?php echo htmlspecialchars($mhs['prodi']); ?></td>
        </tr>
        <tr>
            <th>Angkatan</th>
            <td><?php echo (int) $mhs['angkatan']; ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <?php if ($mhs['status'] === 'aktif'): ?>
                    <span class="badge text-bg-success">Aktif</span>
                <?php elseif ($mhs['status'] === 'cuti'): ?>
                    <span class="badge text-bg-warning">Cuti</span>
                <?php else: ?>
                    <span class="badge text-bg-secondary">Lulus</span>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <a href="<?= BASE_PATH ?>/mahasiswa/edit/<?php echo (int) $mhs['id']; ?>" class="btn btn-outline-warning">Edit</a>
<?php else: ?>
    <div class="alert alert-warning mt-3">
        Data mahasiswa dengan NIM <?php echo htmlspecialchars($nim); ?> tidak ditemukan.
    </div>
<?php endif; ?>

<a href="<?= BASE_PATH ?>/mahasiswa" class="btn btn-secondary">&laquo; Kembali ke Daftar</a>
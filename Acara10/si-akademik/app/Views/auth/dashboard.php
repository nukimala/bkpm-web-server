<?php
$title = 'Dashboard';
$msg   = flash();
if ($msg !== '') {
    echo '<div class="alert alert-success">' . htmlspecialchars($msg) . '</div>';
}

$statistik = $statistik ?? ['mahasiswa' => 0, 'prodi' => 0, 'matakuliah' => 0];
?>
<h1>Dashboard</h1>
<p class="lead">Selamat datang, <strong><?php echo htmlspecialchars($user ?? ''); ?></strong>.</p>

<div class="row mt-4">
    <?php foreach ([
        'Mahasiswa'  => [$statistik['mahasiswa'], '/mahasiswa', 'btn-primary'],
        'Prodi'      => [$statistik['prodi'], '/prodi', 'btn-outline-primary'],
        'Matakuliah' => [$statistik['matakuliah'], '/matakuliah', 'btn-outline-primary'],
    ] as $label => [$jumlah, $url, $warna]): ?>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><?= $label ?></h5>
                    <p class="display-6 mb-2"><?= (int) $jumlah ?></p>
                    <a href="<?= BASE_PATH ?><?= $url ?>" class="btn btn-sm <?= $warna ?>">Kelola</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
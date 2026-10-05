<?php $title = 'Dashboard'; ?>
<?php $msg = flash(); ?>
<?php if ($msg !== ''): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<h1>Dashboard</h1>
<p class="lead">Selamat datang, <strong><?php echo htmlspecialchars($user); ?></strong>.</p>
<div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Mahasiswa</h5>
                <a href="<?= BASE_PATH ?>/mahasiswa" class="btn btn-sm btn-primary">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Prodi</h5>
                <a href="<?= BASE_PATH ?>/prodi" class="btn btn-sm btn-outline-primary">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Matakuliah</h5>
                <a href="<?= BASE_PATH ?>/matakuliah" class="btn btn-sm btn-outline-primary">Kelola</a>
            </div>
        </div>
    </div>
</div>
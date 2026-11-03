<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_PATH ?>/">SI Akademik</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_PATH ?>/">Beranda</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_PATH ?>/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_PATH ?>/mahasiswa">Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_PATH ?>/prodi">Prodi</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_PATH ?>/matakuliah">Matakuliah</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_PATH ?>/logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_PATH ?>/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
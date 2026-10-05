<div style="max-width: 640px;">
    <h1 class="mb-4">Tambah Mahasiswa</h1>
    <form action="<?= BASE_PATH ?>/mahasiswa" method="POST">
        <div class="mb-3">
            <label for="nim" class="form-label">NIM</label>
            <input type="text" class="form-control" id="nim" name="nim" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="prodi" class="form-label">Prodi</label>
            <select class="form-select" id="prodi" name="prodi">
                <option value="">-- Pilih Prodi --</option>
                <option value="1">Teknik Informatika</option>
                <option value="2">Sistem Informasi</option>
                <option value="3">Teknik Komputer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= BASE_PATH ?>/mahasiswa" class="btn btn-secondary">Batal</a>
    </form>
</div>
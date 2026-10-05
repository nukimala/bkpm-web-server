# Git Dasar (Acara 11)

Dokumentasi perintah Git yang dipraktikkan: `init`, `add`, `commit`, `remote`,
`push`, `pull`, dan `clone`.

## 1. Inisialisasi Repositori

```bash
# nyalakan git di dalam folder project
git init

# profil identitas (cukup sekali, tersimpan lokal di .git/config)
git config user.name  "siakademik-dev"
git config user.email "siakademik@localhost"

# cek status: file mana yang belum di-track
git status
```

## 2. Menyimpan Perubahan (Add + Commit)

```bash
# staging area: pilih file yang akan dicommit
git add .

# lihat apa saja yang masuk staging
git status
git diff --cached

# commit dengan pesan yang jelas
git commit -m "Acara 11: inisialisasi Git, .gitignore, dan README"
```

Alur kerja Git: **working directory -> staging area -> repository (commit)**.

## 3. Remote, Push, dan Clone

Skenario: repositori kosongan (bare) bertindak sebagai server remote di mesin
yang sama, sehingga `push`, `pull`, dan `clone` bisa dicoba.

```bash
# buat repositori bare sebagai "server"
mkdir demo-git && cd demo-git
git init --bare siakademik-remote.git

# hubungkan project ke remote
git remote add origin ../demo-git/siakademik-remote.git

# push pertama kali ke remote (buat cabang utama main)
git push -u origin main

# clone: menyalin repositori dari remote ke folder baru
cd .. && git clone ../demo-git/siakademik-remote.git salinan-si-akademik

# pull: mengambil perubahan terbaru dari remote
git pull origin main
```

Artinya:
- `push`  -> mengirim commit lokal ke remote.
- `pull`  -> mengambil commit dari remote ke lokal (gabungan fetch + merge).
- `clone` -> menyalin seluruh repositori remote ke mesin lain / folder baru.
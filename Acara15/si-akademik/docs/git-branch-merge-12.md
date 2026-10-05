# Branch, Merge, Pull Request, dan Conflict (Acara 12)

Dokumentasi operasi Git untuk kerja paralel: `branch`, `checkout`, `merge`,
`Pull Request` (via remote), serta penjelasan konflik dan cara menyelesaikannya.

## 1. Membuat dan Berpindah Branch

Branch adalah "jalur kerja" terpisah dari `main` sehingga dua fitur bisa
dikerjakan bersamaan tanpa saling mengganggu.

```bash
# buat branch baru fitur-tampilan lalu langsung pindah ke sana
git checkout -b fitur-tampilan

# ubah file, lalu commit di branch fitur
git add app/Views/partials/footer.php
git commit -m "fitur-tampilan: tahun copyright otomatis via date()"

# kembali ke branch utama
git checkout main
```

## 2. Mengirim Branch ke Remote dan Pull Request

```bash
# publish branch fitur ke remote
git push -u origin fitur-tampilan

# setelah di-push, Pull Request (PR) bisa diajukan di GitHub/GitLab
# PR = permintaan resmi agar perubahan di branch fitur digabung ke main.
```

Berikutnya dilakukan **review + merge**. Secara lokal, PR disimulasikan dengan
merge commit (bukan fast-forward) agar riwayat menunjukkan adanya percabangan:

```bash
git merge --no-ff fitur-tampilan -m "Merge fitur-tampilan (simulasi Pull Request)"
git push origin main
```

## 3. Konflik saat Merge

Konflik terjadi bila dua branch mengubah **baris yang sama** pada file yang sama
sehingga Git tidak tahu perubahan mana yang benar.

```bash
# contoh: branch konflik-merge mengubah satu baris README
git checkout -b konflik-merge
git commit -am "konflik-merge: tandai login sementara di README"
git checkout main
git commit -am "main: ubah baris README yang sama"

git merge konflik-merge
# <<<<<<< HEAD CONFLICT (content): Merge conflict in README.md >>>>>>>
```

Cara menyelesaikan:

1. Buka file yang berkonflik; Git menandai bagian dari masing-masing branch
   dengan penanda `<<<<<<<`, `=======`, dan `>>>>>>>`.
2. Pilih / gabungkan isi yang benar secara manual, hapus semua penanda konflik.
3. `git add README.md` lalu `git commit` untuk menyimpan hasil merge.

> Praktikum ini berhasil dimerge tanpa konflik (kedua branch mengubah baris
> yang berbeda). Perilaku konflik di atas adalah rambu yang sama untuk mengerti
> cara Git menandai tabrakan perubahan.

## 4. Alur Kerja Ringkas

```
main ----+-- fitur-tampilan <- di-push ke remote <- Pull Request --> merge
         +-- konflik-merge  <- merge tanpa konflik
```

Lihat riwayat lengkap:

```bash
git log --oneline --graph --all
```

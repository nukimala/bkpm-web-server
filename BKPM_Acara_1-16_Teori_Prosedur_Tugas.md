# BKPM Workshop Sistem Informasi Web Server (TIF330805)

Acara 1–16: Dasar Teori (penjelasan), Prosedur Kerja, dan Tugas Mandiri


---

# Acara 1

**Pokok Bahasan:** Konsep Frontend, Backend, dan Arsitektur Client- Server

## Dasar Teori

Apa itu Frontend?

Frontend adalah bagian dari aplikasi web yang langsung berinteraksi dengan
pengguna. Semua yang Anda lihat di browser, teks, tombol, form, gambar, warna,
animasi , adalah frontend.

Teknologi utama frontend:

- HTML , struktur halaman (seperti kerangka bangunan)

- CSS, tampilan dan gaya (cat, wallpaper, furniture)

- JavaScript (di browser) , interaktivitas (tombol yang bisa diklik, validasi form,
animasi)

Analogi: Frontend seperti ruang tamu sebuah rumah. Tamu (pengguna) hanya
melihat dan berinteraksi dengan ruang tamu. Mereka tidak perlu tahu bagaimana
listrik, air, atau dapur bekerja.

Apa itu Backend?
Backend adalah bagian aplikasi yang berjalan di server. Backend tidak langsung
terlihat oleh pengguna, tetapi bertanggung jawab atas logika bisnis, pengolahan data,
autentikasi, dan komunikasi dengan database.
Teknologi backend yang akan kita pelajari:
- PHP: bahasa pemrograman server-side
- Apache: web server yang menerima request dan menjalankan PHP
- MySQL: sistem manajemen database relasional
Analogi: Backend adalah dapur restoran. Pelanggan memesan makanan (request),
pelayan menyampaikan ke dapur, koki memasak (PHP memproses), mengambil
bahan dari gudang (MySQL), lalu menyajikan hasilnya kembali ke pelanggan
(response).

Mengapa Backend Diperlukan?

Tanpa backend, website hanya bisa menampilkan halaman statis. Anda tidak bisa:

- Menyimpan data pengguna (registrasi, login)

- Menampilkan data dinamis dari database (daftar mahasiswa, jadwal)

- Melakukan validasi dan keamanan

- Mengirim email, generate PDF, atau proses pembayaran

Hampir semua aplikasi modern (e-commerce, media sosial, sistem akademik)
membutuhkan backend yang kuat.

Arsitektur Client-Server

Model client-server adalah pola komunikasi di mana client (browser) meminta
layanan, dan server menyediakan layanan tersebut.

Alur sederhana:

Gambar 1. Alur Sederhana Bagaimana Client Server Berjalan

Penjelasan langkah demi langkah:
1. Pengguna mengetik URL di browser atau mengklik link.
2. Browser mengirim HTTP Request ke server (Apache).
3. Apache menerima request. Jika file yang diminta adalah .php, Apache
menyerahkan ke PHP.
4. PHP membaca kode, mengeksekusi logika, dan jika perlu, mengirim query ke
MySQL.
5. MySQL mengembalikan data ke PHP.
6. PHP merangkai data menjadi HTML.
7. Apache mengirim HTML tersebut sebagai HTTP Response ke browser.
8. Browser merender HTML menjadi halaman yang terlihat pengguna.

Peran Masing-masing Komponen
- Browser (Client)
Bertugas mengirim request, menerima response, dan menampilkan hasil.
Browser tidak menjalankan kode PHP. Kode PHP sudah dieksekusi di server
sebelum sampai ke browser.
- Apache (Web Server)
Program yang selalu "mendengarkan" di port 80 (HTTP) atau 443 (HTTPS).
Apache yang menerima request dari internet, menentukan file mana yang
harus diproses, dan mengembalikan response. Di XAMPP, Apache sudah
dikonfigurasi siap pakai.

- PHP
Bahasa pemrograman yang dieksekusi di server. PHP membaca file .php,
mengeksekusi instruksi, dan menghasilkan output (biasanya HTML). PHP tidak
           "hidup" sendiri , ia dipanggil oleh Apache.

- MySQL
Sistem database yang menyimpan data secara terstruktur dalam tabel. PHP
berkomunikasi dengan MySQL menggunakan ekstensi PDO (yang akan kita
pelajari kemudian).

Kesalahan yang Sering Dilakukan Mahasiswa

- Mengira JavaScript di browser adalah backend. JavaScript di browser tetap
frontend.

- Mengira file .html bisa berisi logika database. File HTML murni tidak bisa
berkomunikasi dengan MySQL.

- Bingung kenapa kode PHP tidak berjalan jika dibuka langsung sebagai file
(file:///). PHP harus dijalankan melalui web server (http://localhost).

- Membuka folder htdocs secara langsung di Windows Explorer dan mengira itu
sama dengan membuka di browser.

⭐ Best Practice
Selalu bedakan dengan jelas: kode yang berjalan di browser (HTML, CSS, JS client-
side) vs kode yang berjalan di server (PHP, query database). Jangan mencampur
logika bisnis di frontend.

Contoh

Misalkan Anda memiliki file index.php sederhana:

```
<?php
// File: index.php
echo "<h1>Halo dari Server!</h1>";
echo "<p>Waktu server saat ini: " . date("Y-m-d H:i:s") . "</p>";
```

?>

Ketika Anda membuka http://localhost/index.php:
- Browser mengirim GET request ke Apache.
- Apache melihat ekstensi .php, memanggil PHP.
- PHP mengeksekusi date() di server, menghasilkan string waktu.
- PHP mengirim HTML lengkap ke Apache.
- Apache mengirim response ke browser.
- Browser menampilkan judul dan waktu server.

Perhatikan: fungsi date() dijalankan di server, bukan di komputer pengguna. Jika
server berada di Jakarta dan pengguna di Papua, waktu yang muncul adalah waktu
Jakarta.

## Prosedur Kerja

Studi Kasus

Bayangkan Anda sedang membuka website kampus untuk melihat jadwal kuliah.
Anda mengetik URL di browser, menekan Enter, lalu beberapa detik kemudian muncul
halaman berisi tabel jadwal. Apa yang sebenarnya terjadi di balik layar?

Di dunia nyata, proses ini melibatkan minimal empat "aktor": browser (client), web
server (Apache), bahasa pemrograman (PHP), dan database (MySQL). Jika salah satu
aktor bermasalah, halaman tidak akan muncul. Memahami alur ini adalah fondasi
mutlak sebelum Anda menulis baris kode PHP pertama.

Tujuan
Mahasiswa memahami alur request-response dengan membuat halaman PHP
sederhana dan mengamati hasilnya.
Langkah
- Pastikan Apache di XAMPP sudah running.
- Buat folder project di htdocs, misalnya C:\xampp\htdocs\belajar-backend
- Buat file index.php dengan isi kode di atas.
- Buka browser, ketik http://localhost/belajar-backend/
- Amati hasilnya. Ubah teks di echo, refresh browser, lihat perubahan.
- Coba buka file index.php langsung dari Windows Explorer (double-click). Apa
yang terjadi? Mengapa berbeda?
Analisis
Ketika dibuka via file:///, browser hanya menampilkan teks mentah PHP karena
tidak ada Apache yang mengeksekusi. Ketika dibuka via http://localhost, Apache +
PHP bekerja sama menghasilkan HTML murni.

Kesimpulan
Backend PHP hanya berjalan jika request melewati web server.

Tugas Mandiri
Buat file info.php yang menampilkan:
- Nama Anda
- NIM
- Waktu server
- Versi PHP (gunakan phpversion())
- Sistem operasi server (gunakan PHP_OS)
Tampilkan dalam format HTML yang rapi menggunakan tag <table> atau <ul>.

---

# Acara 2

**Pokok Bahasan:** HTTP, Instalasi XAMPP, dan Tools Pengembangan

## Dasar Teori

Apa itu HTTP?
HTTP (HyperText Transfer Protocol) adalah protokol komunikasi antara client dan
server di web. Setiap kali browser berkomunikasi dengan server, mereka berbicara
dalam bahasa HTTP.

HTTP bersifat stateless, setiap request berdiri sendiri. Server tidak "mengingat"
request sebelumnya kecuali kita menggunakan mekanisme tambahan (seperti
Session, yang akan dipelajari nanti).

HTTP Request

Request terdiri dari beberapa bagian penting:

a. Method (Verb)
- GET — meminta data. Data dikirim lewat URL (query string).

- POST — mengirim data untuk diproses. Data dikirim di body, tidak terlihat
di URL.
- PUT, DELETE, PATCH — digunakan di REST API (nanti).
b. URL
Alamat lengkap resource. Contoh: http://localhost/mahasiswa/detail?id=5
c. Header
Informasi tambahan seperti Content-Type, User-Agent, Cookie, Authorization.
d. Body
Isi data (khususnya pada POST). Pada GET, body biasanya kosong.
HTTP Response

Response berisi:

- Status Code: angka yang memberi tahu hasil request.

- Header: informasi tentang response.

- Body: isi yang dikirim (HTML, JSON, dll).

Status Code Penting
- 200 OK — request berhasil, data dikembalikan.
- 302 Found — redirect sementara. Browser akan diminta ke URL lain.
- 404 Not Found — resource yang diminta tidak ditemukan.
- 500 Internal Server Error — terjadi kesalahan di server (biasanya error PHP atau
konfigurasi).

Status code 4xx berarti kesalahan di sisi client. 5xx berarti kesalahan di sisi server.

```
GET vs POST
```

Gunakan GET ketika:

- Hanya mengambil data (tidak mengubah apa pun di server).

- Parameter boleh terlihat di URL (misalnya pencarian, filter, pagination).

- User boleh bookmark atau share URL-nya.

Gunakan POST ketika:

- Mengirim data sensitif (password, data pribadi).

- Mengubah data di server (insert, update, delete).

- Data terlalu panjang untuk URL.

⚠️ Warning

Jangan pernah mengirim password atau data sensitif menggunakan GET. URL
tersimpan di history browser, log server, dan bisa dibagikan.

Instalasi XAMPP

XAMPP adalah paket yang berisi Apache, MySQL, PHP, dan phpMyAdmin dalam satu
installer. Sangat cocok untuk development lokal.

Langkah Instalasi (Windows):

1. Download XAMPP dari https://www.apachefriends.org (pilih versi PHP 8.x).

2. Jalankan installer. Install di C:\xampp (disarankan).

3. Buka XAMPP Control Panel.

4. Klik Start pada Apache dan MySQL.

5. Buka browser, ketik http://localhost — harus muncul halaman dashboard
XAMPP.

6. Ketik http://localhost/phpmyadmin — harus muncul interface phpMyAdmin.

Struktur Penting XAMPP:

- C:\xampp\htdocs — folder tempat meletakkan project web. Semua file di sini
bisa diakses via http://localhost/nama-folder

- C:\xampp\php — instalasi PHP

- C:\xampp\mysql — instalasi MySQL

- C:\xampp\apache\conf\httpd.conf — konfigurasi Apache

Composer

Composer adalah dependency manager untuk PHP (mirip npm di Node.js atau pip di
Python). Meskipun kita membangun framework sendiri tanpa Laravel, Composer tetap
berguna untuk autoloading dan mengelola library.

Instalasi: download dari https://getcomposer.org lalu ikuti petunjuk. Setelah terinstal,
perintah composer bisa dijalankan di terminal.

Git dan Repository

Git adalah sistem kontrol versi. Setiap perubahan kode dicatat. GitHub adalah layanan
hosting repository Git.

Manfaat untuk mahasiswa:

- Tidak takut kehilangan kode.

- Bisa melihat history perubahan.

- Bisa kolaborasi (meskipun di semester 2 masih individual).

- Portfolio di GitHub terlihat profesional.

Contoh form HTML yang menggunakan GET dan POST:

```
     <!-- form-get.html -->
     <form action="proses.php" method="GET">
       <label>Cari Mahasiswa:</label>
       <input type="text" name="keyword">
       <button type="submit">Cari</button>
     </form>

     <!-- form-post.html -->
     <form action="login.php" method="POST">
       <label>Username:</label>
       <input type="text" name="username">
       <label>Password:</label>
       <input type="password" name="password">
       <button type="submit">Login</button>
     </form>
     Di proses.php (untuk GET):
     <?php
     $keyword = $_GET['keyword'] ?? '';
     echo "Anda mencari: " . htmlspecialchars($keyword);
     ?>
     Di login.php (untuk POST):
     <?php
     $username = $_POST['username'] ?? '';
     $password = $_POST['password'] ?? '';
     // Jangan echo password di production!
     echo "Username yang dikirim: " . htmlspecialchars($username);
     ?>
```

## Prosedur Kerja

Tujuan

Mahasiswa berhasil menginstal XAMPP, membuat project pertama, memahami
perbedaan GET/POST, dan membuat repository Git.

Langkah

1. Instal XAMPP dan pastikan Apache + MySQL running.

2. Buat folder C:\xampp\htdocs\si-akademik

3. Buat file index.php yang menampilkan "Selamat datang di SI Akademik".

4. Buat form pencarian (GET) dan form login sederhana (POST).

5. Instal Git (jika belum), buat repository lokal di folder project, lakukan commit

pertama.
6. Buat akun GitHub, buat repository remote, push project.

Hasil yang Diharapkan
http://localhost/si-akademik menampilkan halaman selamat datang. Form GET

menampilkan keyword di URL. Form POST tidak menampilkan data di URL.

Tugas Mandiri

Buat halaman yang mendeteksi method request (GET atau POST) menggunakan

```
$_SERVER['REQUEST_METHOD'] dan menampilkan pesan berbeda.
```

---

# Acara 3

**Pokok Bahasan:** Pengenalan MVC dan Integrasi HTML Form dengan Bootstrap

## Dasar Teori

Apa itu MVC?

MVC adalah pola arsitektur yang memisahkan aplikasi menjadi tiga komponen utama:

a. Model

Bertanggung jawab atas data dan aturan bisnis terkait data. Model
berkomunikasi dengan database. Contoh: mengambil daftar mahasiswa,
menyimpan data baru, validasi unik NIM.

b. View

Bertanggung jawab atas presentasi. View hanya menampilkan data yang
diberikan Controller. View tidak boleh berisi logika bisnis atau query database.

c. Controller

Pengatur lalu lintas. Controller menerima request, memanggil Model jika
perlu data, lalu memilih View mana yang harus ditampilkan. Controller adalah
```
         "otak" yang mengorkestrasi.
```

Mengapa MVC?
- Kode lebih terorganisir dan mudah dibaca.
- Mudah diuji (testing).
- Mudah dikembangkan oleh tim (satu orang urus Model, satu orang urus View).
- Mengurangi duplikasi kode.
- Memisahkan concern (separation of concerns) — prinsip penting software
engineering.

Alur MVC Sederhana

Integrasi HTML Form

Form HTML adalah cara utama pengguna mengirim data ke server. Atribut penting:
- action — URL tujuan (nanti akan diarahkan ke route/controller)
- method — GET atau POST.
 •       name pada input — kunci untuk mengambil data di PHP ($_POST['nama'])

Bootstrap 5

Bootstrap adalah framework CSS yang memudahkan pembuatan tampilan
responsif dan modern tanpa menulis banyak CSS dari nol. Kita akan menggunakan
Bootstrap 5 via CDN agar sederhana.

Komponen yang sering dipakai: container, row, col, card, form-control, btn, table,
alert, navbar.

⭐ Best Practice

Jangan menulis logika bisnis di dalam file View. View hanya boleh berisi HTML + sedikit
PHP untuk menampilkan variabel. Semua keputusan dan query ada di
Controller/Model.

Contoh
Struktur awal project yang akan kita bangun:
si-akademik/
├── public/
│ └── index.php
├── app/
│ ├── Controllers/
│ ├── Models/
│ └── Views/
├── config/
└── routes/

Contoh View sederhana dengan Bootstrap
(app/Views/mahasiswa/index.php):

<!DOCTYPE html>
```
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
```

rel="stylesheet">
```
</head>
<body>
  <div class="container mt-4">
    <h1 class="mb-4">Daftar Mahasiswa</h1>
    <a href="/mahasiswa/create" class="btn btn-primary mb-3">Tambah Mahasiswa</a>
    <table class="table table-bordered table-striped">
       <thead class="table-dark">
         <tr>
           <th>NIM</th>
           <th>Nama</th>
           <th>Prodi</th>
           <th>Aksi</th>
         </tr>
       </thead>
       <tbody>
         <!-- Data akan diisi dari Controller -->
       </tbody>
    </table>
  </div>

       <script
     src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></s
     cript>
     </body>
     </html>
```

## Prosedur Kerja

Studi Kasus

Bayangkan Anda diminta membuat sistem pencatatan mahasiswa. Jika semua kode
(koneksi database, query, HTML, validasi) ditulis dalam satu file index.php, file
tersebut akan menjadi sangat panjang, sulit dibaca, dan sulit dikembangkan. MVC
memisahkan tanggung jawab: Model urus data, View urus tampilan, Controller urus
logika alur.

Tujuan

Mahasiswa membuat struktur folder MVC dasar dan halaman form + tabel dengan
Bootstrap.

Langkah
1. Buat struktur folder sesuai contoh di atas di dalam htdocs/si-akademik
2. Buat file public/index.php sebagai entry point sementara yang hanya echo
```
        "MVC siap".
```

3. Buat file View untuk form tambah mahasiswa dan daftar mahasiswa
menggunakan Bootstrap 5 CDN.
4. Pastikan tampilan rapi dan responsif.

Hasil
Halaman form dan tabel terlihat profesional dengan Bootstrap, siap dihubungkan ke
Controller di pertemuan berikutnya.

---

# Acara 4

**Pokok Bahasan:** PHP Native View, Layout, dan Dasar OOP

## Dasar Teori

```
      include dan require

      include dan require digunakan untuk menyisipkan isi file lain ke dalam file saat ini.
```

- include — jika file tidak ditemukan, muncul warning, script tetap jalan.

- require — jika file tidak ditemukan, muncul fatal error, script berhenti.

- include_once / require_once — sama seperti di atas, tetapi memastikan file
hanya dimasukkan sekali (mencegah redeclaration).

Best practice: gunakan require_once untuk file penting (config, class). Gunakan
```
      include untuk partial view.

      Layout dan Partial
      Struktur umum:
      app/Views/
      ├── layouts/
      │   └── main.php      ← template utama (header + content + footer)
      ├── partials/
      │   ├── header.php
      │   ├── navbar.php
      │   └── footer.php
      └── mahasiswa/
          ├── index.php

        ├── create.php
        └── edit.php
```

Di main.php biasanya ada tempat untuk content:
```
<!-- layouts/main.php -->
```

<!DOCTYPE html>
```
<html lang="id">
<head>
```

...
```
</head>
<body>
  <?php include __DIR__ . '/../partials/navbar.php'; ?>
  <main class="container mt-4">
    <?php require $content; ?>
  </main>
  <?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
```

Class dan Object

Class adalah blueprint. Object adalah instance dari class.
Analogi: Class "Mobil" mendefinisikan bahwa mobil punya merek, warna, dan bisa
jalan(). Object adalah mobil konkret: Toyota Avanza warna putih.
```
<?php
class Mahasiswa {
    // Property
    public string $nim;
    public string $nama;
    public string $prodi;

        // Constructor
        public function __construct(string $nim, string $nama, string $prodi) {
            $this->nim = $nim;
            $this->nama = $nama;
            $this->prodi = $prodi;
        }

        // Method
        public function getLabel(): string {
            return "{$this->nim} - {$this->nama} ({$this->prodi})";
        }
}

// Membuat object
$mhs = new Mahasiswa("2401001", "Budi Santoso", "Teknik Informatika");
echo $mhs->getLabel();
```

?>

Visibility dan Encapsulation

- public — bisa diakses dari mana saja.
- private — hanya bisa diakses dari dalam class itu sendiri.
- protected — bisa diakses dari class itu sendiri dan class turunannya.
Encapsulation berarti menyembunyikan detail internal dan hanya mengekspos yang
diperlukan melalui method (getter/setter). Ini mencegah data diubah secara
sembarangan.

Namespace

Namespace mencegah konflik nama class. Bayangkan dua library punya class
bernama "User". Dengan namespace, kita bisa bedakan App\Models\User dan
Vendor\Auth\User.

```
<?php
namespace App\Models;

class Mahasiswa {
    // ...
}
```

?>
Cara menggunakan:
```
<?php
use App\Models\Mahasiswa;

$mhs = new Mahasiswa(...);
```

?>

Contoh
Contoh lengkap class sederhana + penggunaan di controller sementara:
```
<?php
// app/Models/Mahasiswa.php
namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;

     public function __construct(string $nim, string $nama)
     {
         $this->nim = $nim;
         $this->nama = $nama;
     }

     public function getNim(): string
     {
         return $this->nim;
     }

     public function getNama(): string
     {
         return $this->nama;
     }

     public function setNama(string $nama): void
     {
         if (strlen($nama) < 3) {
             throw new \InvalidArgumentException("Nama terlalu pendek");
         }
         $this->nama = $nama;
     }
}
```

?>

## Prosedur Kerja

Studi Kasus

Setiap halaman aplikasi biasanya punya header (logo, menu) dan footer
(copyright) yang sama. Jika Anda copy-paste header ke 20 file, ketika menu berubah
Anda harus edit 20 files. Dengan partial + include, Anda hanya edit satu file.

Tujuan

Mahasiswa membuat layout dengan partial, membuat class Mahasiswa sederhana,
dan menampilkan data dari object di View.

Langkah

1. Buat folder partials dan layouts.
2. Buat header.php, navbar.php, footer.php.
3. Buat class Mahasiswa dengan property private + getter.
4. Di index.php (sementara), buat beberapa object Mahasiswa dan kirim ke View.
5. Tampilkan data dalam tabel Bootstrap.

Tugas Mandiri

Tambahkan method di class Mahasiswa yang mengembalikan angkatan berdasarkan
2 digit awal NIM (asumsi format NIM tertentu). Tampilkan di View.

---

# Acara 5

**Pokok Bahasan:** Routing, .htaccess, Request dan Response

## Dasar Teori

Apa itu Routing?

Routing adalah proses memetakan URL request ke kode yang tepat (biasanya method
di Controller). Router adalah "petugas satpam" yang melihat URL lalu mengarahkan
ke ruangan yang benar.

Array Routing Sederhana
```
      <?php
      // routes/web.php
      $routes = [
          'GET' => [
              '/'              => ['HomeController', 'index'],
              '/mahasiswa'     => ['MahasiswaController', 'index'],
              '/mahasiswa/create' => ['MahasiswaController', 'create'],
              '/login'         => ['AuthController', 'loginForm'],
          ],
          'POST' => [
              '/mahasiswa'     => ['MahasiswaController', 'store'],

              '/login'      => ['AuthController', 'login'],
         ],
];
```

?>

.htaccess dan URL Rewriting

File .htaccess di folder public memberitahu Apache agar semua request yang tidak
mengarah ke file nyata diteruskan ke index.php.

# public/.htaccess
RewriteEngine On
```
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
```

RewriteRule ^ index.php [QSA,L]

Artinya: jika yang diminta bukan file atau folder yang benar-benar ada, serahkan ke
index.php. Query string tetap dipertahankan (QSA).

Entry Point index.php

Semua request masuk ke public/index.php. Di situ kita:

1. Ambil URL yang diminta.
2. Ambil method (GET/POST).
3. Cari di array routes.
4. Jika ketemu, panggil Controller@method.
5. Jika tidak ketemu, tampilkan 404.

Superglobal Penting

```
     •    $_GET — data dari query string
     •    $_POST — data dari body form POST
     •    $_SERVER — informasi server dan request (REQUEST_URI, REQUEST_METHOD,
          HTTP_HOST, dll)
     •    $_SESSION — data session (nanti)
     •    $_COOKIE — data cookie
```

Redirect

```
<?php
// Setelah berhasil simpan data
header("Location: /mahasiswa");
exit; // penting! stop eksekusi
```

?>
Redirect biasanya memakai status 302. Untuk permanent redirect gunakan 301.

⚠️ Warning

Jangan lupa exit; atau die; setelah header Location. Jika tidak, kode di bawahnya

masih dieksekusi dan bisa menyebabkan error "headers already sent".

Contoh

Contoh sederhana router di public/index.php:

```
     <?php
     // public/index.php
     require_once __DIR__ . '/../config/app.php';
     require_once __DIR__ . '/../routes/web.php';

     $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
     // Hilangkan base path jika project di subfolder
     $base = '/si-akademik/public';
     if (str_starts_with($uri, $base)) {
         $uri = substr($uri, strlen($base)) ?: '/';
     }

     $method = $_SERVER['REQUEST_METHOD'];

     if (isset($routes[$method][$uri])) {
         [$controllerName, $action] = $routes[$method][$uri];
         $controllerClass = "App\\Controllers\\{$controllerName}";
         $controller = new $controllerClass();
         $controller->$action();
     } else {
         http_response_code(404);
         echo "404 - Halaman tidak ditemukan";
     }
     ?>
```

## Prosedur Kerja

Studi Kasus

Tanpa routing, URL aplikasi menjadi jelek: http://localhost/si-
akademik/app/Controllers/MahasiswaController.php?action=index. Dengan routing +
.htaccess, URL menjadi bersih: http://localhost/mahasiswa atau
http://localhost/mahasiswa/5/edit. URL yang bersih lebih profesional, mudah diingat,

dan lebih aman (struktur folder tidak terekspos).

Tujuan

Mahasiswa mengimplementasikan routing sederhana + .htaccess sehingga URL bersih
berfungsi.

Langkah
1. Buat file public/.htaccess sesuai contoh.

2. Buat routes/web.php.
3. Buat public/index.php sebagai front controller.
4. Buat HomeController dan MahasiswaController sederhana (hanya echo dulu).
5. Uji URL: /, /mahasiswa, /mahasiswa/create, dan URL yang tidak ada (harus 404).

Hasil

URL bersih berfungsi. Request ke /mahasiswa memanggil
MahasiswaController::index().

Tugas Mandiri

Tambahkan dukungan parameter URL sederhana, misalnya /mahasiswa/5 memanggil
show($id) dengan $id = 5. (Bisa menggunakan regex atau explode).

---

# Acara 6

**Pokok Bahasan:** Middleware, Auth Sederhana, dan Struktur Folder Lengkap

## Dasar Teori

Apa itu Middleware?

Middleware adalah lapisan yang berada di antara request masuk dan Controller. Ia
bisa:

- Memeriksa apakah user sudah login.

- Memeriksa hak akses (admin vs user biasa).

- Melakukan logging.

- Memvalidasi CSRF token.

- Memodifikasi request/response.

Analogi: Middleware seperti satpam di pintu masuk gedung. Sebelum tamu masuk ke
ruangan (Controller), satpam mengecek identitas.

Session untuk Auth
Session menyimpan data di server yang terkait dengan satu pengguna (via session
ID di cookie).
```
<?php
// Mulai session (harus dipanggil sebelum output)
session_start();

// Set data login
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Admin';
$_SESSION['logged_in'] = true;

// Cek login
if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // user sudah login
}

// Logout
session_destroy();
```

?>

Middleware Auth Sederhana

```
<?php
// app/Core/Middleware/AuthMiddleware.php
namespace App\Core\Middleware;

class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

         if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
             header('Location: /login');
             exit;
         }
     }
}
```

?>

Struktur Folder Lengkap

si-akademik/
├── app/
│ ├── Controllers/
│ │ ├── HomeController.php
│ │ ├── AuthController.php
│ │ ├── MahasiswaController.php
│ │ ├── ProdiController.php
│ │ └── MatakuliahController.php
│ ├── Models/
│ ├── Views/
│ │ ├── layouts/
│ │ ├── partials/
│ │ ├── auth/
│ │ ├── mahasiswa/
│ │ ├── prodi/
│ │ └── matakuliah/
│ ├── Core/

│ │ ├── Router.php
│ │ ├── Controller.php ← Base Controller
│ │ ├── Model.php ← Base Model
│ │ └── Middleware/
│ │ └── AuthMiddleware.php
│ ├── Services/
│ └── Repositories/
├── config/
│ ├── app.php
│ └── database.php
├── public/
│ ├── index.php
│ ├── .htaccess
│ └── assets/
│ ├── css/
│ └── js/
├── routes/
│ └── web.php
├── storage/
│ └── logs/
├── vendor/ ← jika pakai Composer
├── .gitignore
├── composer.json
└── README.md

⭐ Best Practice

Letakkan semua file yang bisa diakses publik (index.php, CSS, JS, gambar) di dalam
folder public/. Folder app/, config/, routes/ jangan bisa diakses langsung dari browser.

Contoh
Cara memasang middleware di router:
```
   <?php
   // Di Router, sebelum memanggil controller
   $middleware = $route['middleware'] ?? [];
   foreach ($middleware as $mw) {
       $mwInstance = new $mw();
       $mwInstance->handle();
   }
   // baru panggil controller
```

?>

## Prosedur Kerja

Studi Kasus

Halaman dashboard, form tambah mahasiswa, dan halaman edit hanya boleh diakses
user yang sudah login. Tanpa proteksi, siapa pun yang tahu URL bisa masuk.
Middleware Auth mengecek session sebelum Controller dijalankan. Jika belum login,
user diarahkan ke halaman login.

Tujuan

Mahasiswa menyusun struktur folder lengkap, membuat AuthController
(login/logout), dan melindungi beberapa route dengan AuthMiddleware.

Langkah
1. Susun folder sesuai struktur di atas.
2. Buat AuthController dengan method loginForm, login, logout.
3. Buat form login sederhana.
4. Simulasikan login sukses dengan hardcode username/password dulu (nanti
diganti database).
5. Pasang AuthMiddleware pada route /dashboard dan /mahasiswa.
6. Uji: akses /mahasiswa tanpa login harus redirect ke /login.

Tugas Mandiri
Tambahkan flash message: setelah login sukses, tampilkan alert "Selamat datang,
Admin" di dashboard. Setelah logout, tampilkan "Anda telah logout".

---

# Acara 7

**Pokok Bahasan:** Database, CREATE TABLE, dan Model Dasar

## Dasar Teori

Desain Database Sederhana
Kita akan membuat 3 tabel utama:

a. Tabel prodi
```
         CREATE TABLE prodi (
             id INT AUTO_INCREMENT PRIMARY KEY,
             kode VARCHAR(10) NOT NULL UNIQUE,
             nama VARCHAR(100) NOT NULL,
             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
             updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
         );

      b. Tabel mahasiswa
         CREATE TABLE mahasiswa (
             id INT AUTO_INCREMENT PRIMARY KEY,
             nim VARCHAR(20) NOT NULL UNIQUE,
             nama VARCHAR(100) NOT NULL,
             email VARCHAR(100) NOT NULL,
             prodi_id INT NOT NULL,
             angkatan YEAR NOT NULL,

         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         CONSTRAINT fk_mahasiswa_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
             ON DELETE RESTRICT ON UPDATE CASCADE
    );
```

c. Tabel Matakuliah
```
    CREATE TABLE matakuliah (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kode VARCHAR(10) NOT NULL UNIQUE,
        nama VARCHAR(150) NOT NULL,
        sks TINYINT NOT NULL,
        prodi_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT fk_matakuliah_prodi FOREIGN KEY (prodi_id) REFERENCES prodi(id)
            ON DELETE RESTRICT ON UPDATE CASCADE
    );
```

Data Awal (Seeding)

INSERT INTO prodi (kode, nama) VALUES

('TI', 'Teknik Informatika'),
('SI', 'Sistem Informasi'),
```
('TK', 'Teknik Komputer');

INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan) VALUES
```

('2401001', 'Budi Santoso', 'budi@email.com', 1, 2024),
('2401002', 'Ani Wijaya', 'ani@email.com', 1, 2024),
```
('2402001', 'Citra Lestari', 'citra@email.com', 2, 2024);

INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES
```

('TI101', 'Pemrograman Dasar', 3, 1),
('TI102', 'Basis Data', 3, 1),
```
('SI101', 'Pengantar SI', 2, 2);
```

Model

Model merepresentasikan tabel dan menyediakan method untuk berinteraksi
dengan data. Pada tahap ini kita masih menggunakan mysqli atau PDO sederhana.
Nanti kita akan pakai PDO + prepared statement secara konsisten.

💡 Catatan Penting
Selalu gunakan FOREIGN KEY agar integritas data terjaga. Jangan biarkan
mahasiswa punya prodi_id yang tidak ada di tabel prodi.

Contoh
File config/database.php:
```
<?php
return [
    'host' => 'localhost',
    'dbname' => 'si_akademik',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];
```

?>

## Prosedur Kerja

Studi Kasus
Sistem Informasi Akademik membutuhkan data Mahasiswa, Program Studi, dan Mata
Kuliah. Data ini harus tersimpan permanen, bisa dicari, diubah, dan dihapus. File teks
atau array PHP tidak cocok untuk data yang besar dan sering berubah. Database
relasional (MySQL) adalah solusinya.

Tujuan

Mahasiswa membuat database si_akademik, tiga tabel, data awal, dan Model
sederhana yang bisa mengambil semua data.

Langkah
1. Buka phpMyAdmin, buat database si_akademik.
2. Jalankan semua perintah CREATE TABLE.
3. Jalankan INSERT data awal.
4. Buat config/database.php.
5. Buat class Model dasar dan MahasiswaModel yang punya method all().
6. Tampilkan data mahasiswa di halaman /mahasiswa (masih sederhana).

Tugas Mandiri

Tambahkan kolom 'status' (ENUM 'aktif','cuti','lulus') pada tabel mahasiswa, isi default
'aktif', lalu update data yang sudah ada.

---

# Acara 8

**Pokok Bahasan:** PDO, Prepared Statement, Relasi, dan CRUD Lengkap

## Dasar Teori

PDO dan Prepared Statement
```
         <?php
         // config/database.php atau Core/Database.php
         class Database
         {
             private static ?PDO $instance = null;

            public static function getInstance(): PDO
            {
                if (self::$instance === null) {

            $config = require __DIR__ . '/../config/database.php';
            $dsn =
"mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}"
;
            self::$instance = new PDO($dsn, $config['username'], $config['password'],
[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$instance;
    }
}
```

?>

Contoh prepared statement:
```
<?php
$pdo = Database::getInstance();
$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE nim = :nim");
$stmt->execute(['nim' => $nim]);
$mahasiswa = $stmt->fetch();
```

?>

Binding dan Error Handling

Gunakan named parameter (:nim) atau positional (?). Selalu set
ERRMODE_EXCEPTION agar error PDO dilempar sebagai exception yang bisa
ditangkap.

Transaction
Ketika beberapa query harus berhasil semua atau gagal semua (contoh: transfer
nilai, atau insert header + detail), gunakan transaction.
```
<?php
$pdo = Database::getInstance();
try {
    $pdo->beginTransaction();
    // query 1
    // query 2
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
}
```

?>

Relasi One-to-Many dan JOIN
Satu Prodi memiliki banyak Mahasiswa (One-to-Many). Untuk menampilkan nama
prodi bersama data mahasiswa:
```
SELECT m.*, p.nama AS prodi_nama
```

FROM mahasiswa m
JOIN prodi p ON m.prodi_id = p.id
ORDER BY m.nim

CRUD Lengkap

Setiap resource (Mahasiswa, Prodi, Matakuliah) memiliki:

- index — daftar data + pagination sederhana
- create — form tambah
- store — proses simpan (POST)
- edit — form edit
- update — proses update (POST)
- destroy — proses hapus (POST)
- show — detail (opsional)

⭐ Best Practice
Selalu gunakan method POST untuk store, update, dan destroy. Jangan pakai GET
untuk operasi yang mengubah data.

Contoh method store di MahasiswaController (disederhanakan):
```
   <?php
   public function store(): void
   {
       $nim = trim($_POST['nim'] ?? '');
       $nama = trim($_POST['nama'] ?? '');
       $email = trim($_POST['email'] ?? '');
       $prodi_id = (int)($_POST['prodi_id'] ?? 0);
       $angkatan = (int)($_POST['angkatan'] ?? date('Y'));

        // Validasi sederhana
        if ($nim === '' || $nama === '') {
            // redirect kembali dengan error
            header('Location: /mahasiswa/create');
            exit;
        }

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan)"
        );
        $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodi_id,
            'angkatan' => $angkatan,
        ]);

        header('Location: /mahasiswa');
        exit;
   }
```

?>

## Prosedur Kerja

Studi Kasus

Seorang mahasiswa mengetik di form pencarian: ' OR '1'='1. Jika query digabung
langsung dengan string, seluruh data bisa bocor (SQL Injection). Prepared statement
memisahkan query dan data, sehingga input berbahaya diperlakukan sebagai data
biasa, bukan perintah SQL.

Tujuan

Mahasiswa menyelesaikan CRUD penuh untuk ketiga resource (Mahasiswa, Prodi,
Mata Kuliah) menggunakan PDO prepared statement dan Bootstrap.

Langkah
1. Implementasikan Database class singleton dengan PDO.
2. Buat Repository atau method di Model untuk all, find, create, update, delete.
3. Lengkapi Controller + View untuk CRUD Mahasiswa.
4. Ulangi untuk Prodi dan Mata Kuliah.
5. Pastikan JOIN digunakan saat menampilkan daftar mahasiswa (tampilkan
nama prodi).
6. Tambahkan konfirmasi JavaScript sebelum hapus.
Tugas Mandiri

Tambahkan fitur pencarian (search) pada halaman index mahasiswa berdasarkan
nama atau NIM, menggunakan LIKE dengan prepared statement.

---

# Acara 9

**Pokok Bahasan:** OOP MVC Lanjutan: Object, Dependency, Getter Setter

## Dasar Teori

Object Composition
Lebih baik "memiliki" object lain daripada mewarisi terlalu dalam. Controller
memiliki Repository, Repository memiliki PDO.

Dependency Injection Sederhana
```
 <?php
 class MahasiswaController extends Controller
 {
     private MahasiswaRepository $repo;

    public function __construct()
    {
        $this->repo = new MahasiswaRepository(Database::getInstance());
    }

    public function index(): void

      {
           $data = $this->repo->all();
           $this->view('mahasiswa/index', ['mahasiswa' => $data]);
      }
}
```

?>

Getter dan Setter
Gunakan untuk mengontrol akses dan menambahkan validasi/logika saat data
dibaca atau diubah.

## Prosedur Kerja

Studi Kasus
Controller yang langsung membuat new PDO di setiap method menjadi sulit diuji
dan sulit diganti implementasinya. Dengan menyuntikkan dependency (Database)
ke Constructor, kode menjadi lebih fleksibel dan testable.

Buatlah sebuah aplikasi Manajemen Data Mahasiswa sederhana yang menerapkan
Object Composition, Dependency Injection, serta Getter dan Setter.
Mahasiswa diminta untuk:
1. Membuat class Database yang bertugas mengelola koneksi ke database
menggunakan PDO.
2. Membuat class MahasiswaRepository yang menerima objek Database melalui
constructor (constructor injection) untuk melakukan operasi CRUD data
mahasiswa.
3. Membuat class MahasiswaController yang menerima objek
MahasiswaRepository melalui constructor, sehingga controller tidak lagi
membuat koneksi database secara langsung.
4. Mengubah seluruh atribut pada class Mahasiswa menjadi private, kemudian
membuat getter dan setter untuk mengakses serta mengubah data.
5. Menambahkan validasi sederhana pada setter, misalnya nama mahasiswa tidak
boleh kosong dan NIM harus berupa angka.

6. Menjalankan aplikasi untuk memastikan proses tambah, ubah, tampil, dan hapus
data tetap berjalan setelah proses refactoring.
Output yang diharapkan: Mahasiswa mampu memahami penerapan Object
Composition, Constructor Dependency Injection, serta penggunaan Getter dan Setter
untuk menghasilkan kode yang lebih rapi, fleksibel, mudah diuji (testable), dan mudah
dikembangkan.

---

# Acara 10

**Pokok Bahasan:** Inheritance, Base Controller, Base Model, Repository Pattern

## Dasar Teori

Pengertian Inheritance
Inheritance (pewarisan) adalah konsep OOP yang memungkinkan sebuah class
mewarisi atribut dan method dari class lain. Dengan inheritance, kode yang sama
tidak perlu ditulis berulang kali sehingga lebih mudah dipelihara.

Pada aplikasi MVC, banyak Controller memiliki fungsi yang sama, misalnya untuk
menampilkan halaman (view()) dan mengarahkan pengguna ke halaman lain
(redirect()). Daripada setiap Controller menulis method tersebut berulang kali, dibuat
sebuah BaseController yang nantinya diwarisi oleh semua Controller.

Keuntungan Inheritance
- Mengurangi duplikasi kode (DRY - Don't Repeat Yourself).
- Mempermudah pemeliharaan aplikasi.

- Perubahan cukup dilakukan pada BaseController tanpa mengubah seluruh
Controller.

Alur Kerja Inheritance

Contoh Kode:
```
<?php
class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

        protected function redirect(string $url): void
        {
            header("Location: {$url}");
            exit;
        }
}

class MahasiswaController extends Controller
{
    public function index(): void
    {
        $this->view('mahasiswa/index', ['title' => 'Daftar Mahasiswa']);
    }
}
```

?>

Pengertian Repository Pattern
Repository Pattern adalah pola desain yang bertugas mengelola seluruh proses
akses data ke database. Seluruh perintah SQL ditempatkan di dalam Repository

sehingga Controller hanya berinteraksi dengan method yang tersedia tanpa
mengetahui bagaimana query dijalankan.

Dengan pendekatan ini, apabila struktur database berubah atau sistem berpindah
dari MySQL ke DBMS lain, perubahan cukup dilakukan pada Repository tanpa
mengubah Controller.

Keuntungan Repository Pattern
- Memisahkan logika bisnis dengan logika akses database.
- Controller menjadi lebih sederhana.
- Kode lebih mudah diuji (testable).
- Lebih mudah dikembangkan dan dipelihara.
Contoh Kode:
```
<?php
class MahasiswaRepository
{
    public function __construct(private PDO $pdo) {}

         public function all(): array
         {
             $stmt = $this->pdo->query(
                 "SELECT m.*, p.nama AS prodi_nama
                  FROM mahasiswa m
                  JOIN prodi p ON m.prodi_id = p.id
                  ORDER BY m.nim"
             );
             return $stmt->fetchAll();
         }

         public function find(int $id): ?array
         {
             $stmt = $this->pdo->prepare("SELECT * FROM mahasiswa WHERE id = :id");
             $stmt->execute(['id' => $id]);
             $row = $stmt->fetch();
             return $row ?: null;
         }

         public function create(array $data): int
         {
             $stmt = $this->pdo->prepare(
                 "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
                  VALUES (:nim, :nama, :email, :prodi_id, :angkatan)"
             );
             $stmt->execute($data);
             return (int)$this->pdo->lastInsertId();
         }

         // update, delete, dst.
}
```

?>

Alur Kerja Repository Pattern

Controller hanya memanggil method seperti:

```
     $repository->all();
     $repository->find($id);
     $repository->create($data);

     Controller tidak lagi menulis SQL secara langsung, karena seluruh query berada
     di dalam Repository.
```

## Prosedur Kerja

Studi Kasus

Setiap Controller butuh method view() dan redirect(). Setiap Model butuh akses
PDO. Daripada copy-paste, buat BaseController dan BaseModel, lalu class lain extends
darinya.

Buatlah sebuah aplikasi Manajemen Data Mahasiswa dengan menerapkan konsep
Inheritance dan Repository Pattern. Mahasiswa diminta untuk:
1. Membuat BaseController yang berisi method view() dan redirect().
2. Membuat MahasiswaController yang mewarisi (extends) BaseController dan
menggunakan method yang diwariskan untuk menampilkan halaman data
mahasiswa.
3. Membuat MahasiswaRepository yang bertugas menangani seluruh operasi
database (menampilkan, menambah, mengubah, dan menghapus data
mahasiswa).
4. Memodifikasi MahasiswaController agar seluruh proses akses data dilakukan
melalui MahasiswaRepository, tanpa menuliskan query SQL di dalam
Controller.
5. Menguji aplikasi dengan menjalankan fitur tampil data, tambah data, ubah
data, dan hapus data untuk memastikan seluruh fungsi berjalan dengan baik.

Output yang diharapkan: Mahasiswa mampu menerapkan konsep Inheritance
untuk mengurangi duplikasi kode pada Controller serta Repository Pattern untuk
memisahkan logika akses database dari logika aplikasi sehingga kode menjadi lebih
rapi, mudah dipelihara, dan mudah dikembangkan.

---

# Acara 11

**Pokok Bahasan:** Git Dasar: Repository, Commit, Push, Pull, Clone

## Dasar Teori

Pengenalan Git dan GitHub
Bayangkan Anda sudah mengerjakan aplikasi Sistem Informasi Akademik
selama beberapa minggu. Suatu hari laptop rusak dan seluruh folder project hilang.
Karena project tidak pernah disimpan di tempat lain, semua kode harus dibuat dari
awal.
Masalah tersebut dapat diminimalkan dengan menggunakan Git dan GitHub.
- Git digunakan untuk mencatat perubahan kode pada komputer.
- GitHub digunakan untuk menyimpan repository Git secara online.
- Dengan Git, setiap perubahan project dapat memiliki history sehingga kita
dapat mengetahui perkembangan kode dari waktu ke waktu.
- Dengan GitHub, project dapat disimpan di cloud sehingga lebih aman apabila
komputer mengalami kerusakan.

Alur Kerja Git dan GitHub

Penjelasan setiap tahap:
1. Project di Laptop
Mahasiswa membuat dan mengubah kode program di komputer. Setiap
perubahan pada file akan terdeteksi oleh Git.

2. git add — memilih perubahan
Perintah ini digunakan untuk memasukkan perubahan yang ingin disimpan ke
dalam staging area.
```
   git add.
```

3. git commit — menyimpan versi kode
Commit adalah titik penyimpanan (checkpoint) pada project. Setiap commit
memiliki pesan yang menjelaskan perubahan yang dilakukan.
   git commit -m "Menambahkan fitur data mahasiswa"

4. Repository Git — menyimpan riwayat
Git menyimpan informasi tentang commit dan perubahan kode di repository
lokal. Jadi, kita dapat melihat kembali perkembangan project dari waktu ke
waktu.

5. git push — mengirim ke GitHub
Push digunakan untuk mengirim commit dari komputer ke repository GitHub.
```
   git push
```

6. GitHub — penyimpanan online
GitHub menyimpan repository secara online. Jika laptop rusak, repository yang
sudah di-push ke GitHub tetap dapat diakses dan digunakan kembali.

Ketika Ada Perubahan dari GitHub

Jika project di GitHub sudah berubah, misalnya dikerjakan dari komputer lain,
perubahan tersebut dapat diambil menggunakan:

```
  git pull
```

Sedangkan jika project belum ada di komputer, gunakan:

```
  git clone https://github.com/username/si-akademik.git
```

Ingat Pola Sederhananya

EDIT KODE
↓
```
      git add
         ↓
      git commit
         ↓
      git push
         ↓
      GITHUB
```

Jadi, add = pilih perubahan → commit = simpan versi → push = kirim ke
GitHub.

Perintah Dasar Git
a. Membuat repository Git
Jalankan di dalam folder project:
```
    git init
    Perintah ini membuat folder project mulai dikelola oleh Git.
```

b. Melihat kondisi project
```
    git status

    Digunakan untuk melihat file yang berubah, file baru, atau file yang belum
    disimpan dalam commit.
```

c. Menambahkan perubahan
```
    git add .
    Menyiapkan seluruh perubahan untuk disimpan.
```

d. Menyimpan perubahan
```
    git commit -m "Menambahkan fitur data mahasiswa"
    Commit dapat dianggap sebagai titik penyimpanan (checkpoint) dari project.
    Gunakan pesan commit yang jelas, misalnya:

    git commit -m "Menambahkan form mahasiswa"
    git commit -m "Memperbaiki validasi NIM"
    git commit -m "Menambahkan fitur hapus mahasiswa"
```

e. Menghubungkan Project dengan GitHub
Setelah membuat repository kosong di GitHub, hubungkan project lokal dengan:

```
           git remote add origin https://github.com/username/si-akademik.git.
           Kemudian gunakan branch utama:
           git branch -M main
           Kirim project ke GitHub:
           git push -u origin main
           Setelah itu, kode project dapat dilihat pada repository GitHub.
      f.   Mengambil Perubahan dari GitHub
           Jika terdapat perubahan terbaru di GitHub:
           git pull
           Sedangkan jika ingin mengambil project yang belum ada di laptop:
           git clone https://github.com/username/si-akademik.git
```

Perbedaan sederhana:
- git push → mengirim perubahan dari laptop ke GitHub.
- git pull → mengambil perubahan dari GitHub ke laptop.
- git clone → mengambil seluruh project dari GitHub untuk pertama kali.
File .gitignore
Tidak semua file dalam project perlu dikirim ke GitHub. Misalnya folder vendor dapat
berisi banyak file library, sedangkan .env dapat berisi konfigurasi sensitif seperti

password database. Untuk menentukan file yang tidak perlu disimpan di Git,
gunakan file bernama: .gitignore
Contoh:
```
   /vendor/
   /node_modules/
```

.env
```
   /storage/logs/*
```

.DS_Store
Thumbs.db
Artinya:
- /vendor/ → folder library PHP tidak perlu di-track.
- /node_modules/ → dependency Node.js tidak perlu di-track.
- .env → file konfigurasi lokal tidak dikirim ke repository.
- /storage/logs/* → file log tidak perlu disimpan.
- .DS_Store dan Thumbs.db → file bawaan sistem operasi tidak perlu disimpan.

Catatan penting: .gitignore bukan berarti file tersebut dihapus dari komputer. Git
hanya diberitahu untuk mengabaikan file tersebut.

## Prosedur Kerja

Menyimpan Project Sistem Informasi Akademik ke GitHub
Buatlah sebuah project sederhana Sistem Informasi Akademik yang telah dibuat
pada praktikum sebelumnya. Kemudian gunakan Git dan GitHub untuk menyimpan
serta mengelola project tersebut.
Langkah Praktikum
1. Buat repository baru di GitHub dengan nama:
si-akademik
2. Buka terminal pada folder project.
3. Inisialisasi Git dengan git init.
4. Buat file. gitignore dan masukkan file/folder yang tidak perlu di-upload.
5. Periksa perubahan menggunakan git status.
6. Tambahkan file project menggunakan git add.
7. Buat commit pertama dengan pesan yang jelas.
8. Hubungkan project lokal dengan repository GitHub.
9. Push project ke GitHub.
10. Tambahkan satu perubahan pada project, misalnya fitur atau tampilan baru.
11. Lakukan add → commit → push kembali.
12. Periksa commit history pada GitHub untuk melihat perubahan yang telah
dilakukan.
Output Praktikum
Mahasiswa mengumpulkan:
- Link repository GitHub project si-akademik.
- Project sudah berhasil di-push ke GitHub.
- Repository memiliki minimal 2 commit dengan pesan yang jelas.
- Terdapat file .gitignore.
- Mahasiswa mampu menjelaskan fungsi git add, git commit, git push, git pull,
dan git clone.
Tujuan akhirnya: mahasiswa tidak hanya hafal perintah Git, tetapi memahami
bahwa Git digunakan untuk mengelola riwayat perubahan kode, sedangkan
GitHub digunakan sebagai tempat penyimpanan repository secara online.

---

# Acara 12

**Pokok Bahasan:** Branch, Merge, Pull Request, dan Conflict.

## Dasar Teori

Branch
Branch adalah cabang dari project yang memungkinkan kita mengembangkan fitur
baru tanpa langsung mengubah kode pada branch utama (main).
Misalnya, project utama sudah berjalan dengan baik. Kita ingin membuat fitur login.
Daripada langsung mengubah main, kita membuat branch baru:
```
      git checkout -b fitur-login

      Sekarang kita bekerja pada branch fitur-login.
      main
       │
       ├── kode aplikasi yang sudah stabil
       │
       └── fitur-login
             ├── membuat form login
             ├── membuat proses login
             └── commit perubahan

      Setelah fitur selesai dan sudah diuji, branch tersebut dapat digabungkan kembali ke
      main.
```

Merge dan Conflict
Merge digunakan untuk menggabungkan perubahan dari satu branch ke branch
lainnya.
Misalnya fitur login sudah selesai:
```
git checkout main
git merge fitur-login
```

Artinya, perubahan dari fitur-login digabungkan ke main.

Apa itu Conflict?

Conflict terjadi ketika Git menemukan perubahan yang bertabrakan dan tidak
dapat menentukan perubahan mana yang harus digunakan.
Contohnya:
main:
<h1>Sistem Informasi Akademik</h1>

fitur-login:
<h1>Login Sistem Akademik</h1>

Jika kedua branch mengubah baris yang sama, Git akan meminta kita menentukan
kode mana yang ingin dipertahankan. Biasanya Git memberikan tanda seperti:
<<<<<<< HEAD
<h1>Sistem Informasi Akademik</h1>
=======
<h1>Login Sistem Akademik</h1>
>>>>>>> fitur-login

Mahasiswa harus menghapus tanda tersebut dan menentukan kode akhir yang
benar, kemudian:
```
git add .
git commit -m "Menyelesaikan merge conflict"
```

Intinya: conflict bukan berarti project rusak. Git hanya meminta programmer
memilih atau menggabungkan perubahan secara manual.

Pull Request

Pull Request (PR) adalah fitur GitHub untuk mengusulkan agar perubahan dari
suatu branch digabungkan ke branch lain, biasanya main.
Contohnya:
fitur-login
│
│ Pull Request
↓
main

Sebelum di-merge, perubahan dapat diperiksa terlebih dahulu melalui code review.
Walaupun mahasiswa mengerjakan project sendirian, latihan menggunakan Pull

Request tetap penting karena dalam proyek nyata, programmer biasanya bekerja
dalam tim.

## Prosedur Kerja

Gunakan project si-akademik dari praktikum sebelumnya. Mahasiswa diminta
membuat dan menggabungkan fitur baru menggunakan branch.
Langkah Praktikum
1. Pastikan project berada pada branch main.
2. Buat branch baru dengan nama:
```
         git checkout -b fitur-login
```

3. Tambahkan fitur sederhana Halaman Login.
4. Simpan perubahan dengan:
```
         git add .
         git commit -m "Menambahkan fitur login"
```

5. Kembali ke branch main:
```
         git checkout main
```

6. Gabungkan branch:
```
         git merge fitur-login
```

7. Pastikan fitur login sudah masuk ke main.
8. Buat simulasi conflict dengan mengubah baris kode yang sama pada main dan
branch lain.
9. Lakukan merge dan amati conflict yang muncul.
10. Selesaikan conflict secara manual, kemudian lakukan:
```
         git add .
         git commit -m "Menyelesaikan merge conflict"
```

11. Push hasil akhir ke GitHub.
12. Buat Pull Request dari branch fitur menuju main.

Output Praktikum

Mahasiswa menghasilkan:
- Repository GitHub si-akademik.
- Minimal 1 branch fitur.
- Minimal 1 fitur baru.
- Minimal 1 proses merge.
- Bukti berhasil menyelesaikan merge conflict.
- Minimal 1 Pull Request.
Pemahaman yang harus dicapai: mahasiswa memahami bahwa branch
digunakan untuk mengembangkan fitur secara terpisah, merge digunakan
untuk menggabungkan perubahan, conflict terjadi ketika perubahan
bertabrakan, dan Pull Request digunakan untuk mengajukan perubahan agar
dapat diperiksa sebelum digabungkan.

---

# Acara 13

**Pokok Bahasan:** Service Layer, Validasi, Session, dan Business Logic

## Dasar Teori

Service Layer
Pada aplikasi sederhana, Controller mungkin masih bisa menangani validasi dan
proses penyimpanan data. Namun, ketika aplikasi semakin besar, Controller akan
menjadi terlalu panjang.
Contohnya, saat menambahkan mahasiswa, aplikasi mungkin harus:
- Memeriksa NIM wajib diisi.
- Memeriksa NIM tidak boleh sama.
- Memeriksa email valid.
- Memeriksa program studi tersedia.
- Melakukan beberapa proses sebelum data disimpan.

Jika semua proses tersebut diletakkan di Controller, kode akan menjadi sulit dibaca
dan dipelihara. Untuk mengatasi masalah tersebut, digunakan Service Layer.
Service bertugas menangani logika bisnis aplikasi, sedangkan Controller hanya
menerima request, memanggil Service, kemudian menentukan response.

Pembagian Tugas

Contohnya:
Controller
```
"Ada permintaan untuk menambahkan mahasiswa."
```

Service
"Saya cek apakah NIM kosong, apakah NIM sudah digunakan, apakah prodi valid, lalu

saya proses."
Repository
"Saya yang menjalankan query INSERT ke database."

Dengan demikian, Controller tetap tipis dan Service menjadi tempat utama untuk

logika bisnis.
Contoh Service
```
class MahasiswaService
{
    public function __construct(
        private MahasiswaRepository $repo,
        private ProdiRepository $prodiRepo
    ) {}

    public function create(array $input): array
    {
        $errors = $this->validate($input);

          if (!empty($errors)) {
              return [
                  'success' => false,
                  'errors' => $errors
              ];
          }

          $id = $this->repo->create($input);

          return [
              'success' => true,
              'id' => $id
          ];
    }

    private function validate(array $input): array
    {
        $errors = [];

          if (empty($input['nim'])) {
              $errors['nim'] = 'NIM wajib diisi';
          }

          if (!empty($input['nim']) &&
              $this->repo->existsByNim($input['nim'])) {
              $errors['nim'] = 'NIM sudah terdaftar';
          }

          return $errors;
    }
}
```

Intinya:

Controller mengatur alur → Service mengatur logika bisnis → Repository mengatur
database.

Flash Message dengan Session
Setelah mahasiswa berhasil ditambahkan, pengguna biasanya perlu mendapatkan
informasi seperti:
```
"Data mahasiswa berhasil ditambahkan."
```

Pesan tersebut dapat disimpan sementara menggunakan Session.
Pesan seperti ini disebut Flash Message, yaitu pesan yang hanya ditampilkan satu
kali kemudian dihapus.
Contoh:

```
$_SESSION['flash'] = [
    'type' => 'success',
    'message' => 'Data mahasiswa berhasil ditambahkan'
];
```

Setelah ditampilkan, pesan tersebut dihapus:

```
$message = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

     Contoh alurnya:

     Flash message juga dapat digunakan untuk pesan error:
     ✓ Data berhasil disimpan
     ✕ NIM sudah terdaftar
     ✕ Data gagal dihapus
```

## Prosedur Kerja

Service Layer dan Flash Message
Gunakan project Sistem Informasi Akademik dari praktikum sebelumnya.
Mahasiswa diminta melakukan refactoring proses tambah dan ubah data
mahasiswa agar menggunakan Service Layer.
Langkah Praktikum
1. Buat class MahasiswaService.
2. Inject MahasiswaRepository dan ProdiRepository melalui constructor.
3. Pindahkan proses validasi data mahasiswa dari Controller ke
MahasiswaService.

4. Buat method:
create()
untuk menangani proses penambahan mahasiswa.

5. Buat method:

update()
untuk menangani proses perubahan data mahasiswa.

6. Pastikan Controller tidak lagi melakukan validasi dan query database secara
langsung.
7. Buat mekanisme Flash Message menggunakan $_SESSION.

8. Tampilkan pesan ketika:
 - Data berhasil ditambahkan.
 - Data berhasil diubah.
 - Data gagal disimpan.
 - NIM sudah terdaftar.
9. Setelah pesan ditampilkan, hapus Flash Message dari session.
10. Uji seluruh proses melalui browser.

Output Praktikum

dan mampu menunjukkan:
- MahasiswaService.php
- Controller yang lebih sederhana
- Validasi berada di Service
- Proses database berada di Repository
- Flash message berhasil ditampilkan setelah proses create/update.

Tujuan praktikum: mahasiswa memahami bahwa semakin kompleks aplikasi,
semakin penting memisahkan alur request, logika bisnis, dan akses database
agar kode tetap rapi dan mudah dikembangkan.

---

# Acara 14

**Pokok Bahasan:** Security, Exception, Logging, PRG, dan Integrasi CRUD Lengkap

## Dasar Teori

Security pada Aplikasi Web
Security digunakan untuk melindungi aplikasi dan database dari input atau tindakan
yang tidak diinginkan.
Beberapa praktik dasar yang digunakan:

Validasi Input

Data yang diterima dari pengguna harus diperiksa terlebih dahulu.

```
     if (empty($input['nim'])) {

    throw new Exception('NIM wajib diisi');
}
```

Prepared Statement
Jangan memasukkan input pengguna langsung ke dalam query SQL.

Kurang aman:

```
$sql = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
```

Gunakan prepared statement:

```
$stmt = $pdo->prepare(
    "SELECT * FROM mahasiswa WHERE nim = :nim"
);

$stmt->execute([
    'nim' => $nim
]);
```

Dengan cara ini, input pengguna dipisahkan dari perintah SQL sehingga membantu
mencegah SQL Injection.

Exception Handling

Exception digunakan untuk menangani kondisi ketika program mengalami masalah.

Daripada aplikasi berhenti dan menampilkan error teknis kepada pengguna, error dapat
ditangani menggunakan:

```
try {
    // kode yang berpotensi mengalami error
} catch (Exception $e) {
    // menangani error
}
```

Contoh:

```
try {
    $id = $service->create($input);

    $_SESSION['flash'] = [
        'type' => 'success',
        'message' => 'Data berhasil ditambahkan'
    ];

} catch (Exception $e) {

    $_SESSION['flash'] = [
        'type' => 'danger',
        'message' => 'Data gagal disimpan'
    ];
}
```

Pengguna cukup mendapatkan pesan:

- Data gagal disimpan.
- Detail error tidak perlu ditampilkan kepada pengguna.

Logging

Logging adalah proses mencatat kejadian penting atau error yang terjadi pada
aplikasi.
Contohnya:
error_log(
date('Y-m-d H:i:s') .
```
    " - " .
    $e->getMessage() .
    PHP_EOL,
    3,
    __DIR__ . '/../storage/logs/app.log'
);
```

Log dapat digunakan programmer untuk mengetahui:
- kapan error terjadi,
- error apa yang terjadi,
- bagian aplikasi yang bermasalah.

Contoh isi app.log:

2026-08-08 10:30:15 - SQLSTATE[23000]: Duplicate entry
2026-08-08 10:35:42 - Database connection failed

Penting: informasi sensitif seperti password database jangan ditulis ke dalam log.

PRG (Post/Redirect/Get)

PRG (Post/Redirect/Get) adalah pola untuk mencegah form dikirim ulang ketika
pengguna melakukan refresh halaman.

Contoh:

```
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $service->create($_POST);

      $_SESSION['flash'] = [
          'type' => 'success',
          'message' => 'Data berhasil disimpan'
      ];

      header('Location: /mahasiswa');
      exit;
  }
```

Setelah data berhasil disimpan, pengguna diarahkan kembali ke halaman mahasiswa
menggunakan redirect.

Integrasi CRUD dengan MySQL

Pada bagian ini seluruh konsep sebelumnya digabungkan menjadi aplikasi CRUD.

CRUD terdiri dari:

Operasi Fungsi
Create Menambahkan data mahasiswa
Read Menampilkan data mahasiswa
Update Mengubah data mahasiswa
Delete Menghapus data mahasiswa

## Prosedur Kerja

Studi Kasus
Aplikasi Sistem Informasi Akademik sudah dapat melakukan CRUD data
mahasiswa. Namun, aplikasi masih memiliki beberapa masalah:
- Input pengguna belum divalidasi dengan baik.
- Query database harus aman dari SQL Injection.
- Jika terjadi error database, aplikasi menampilkan error teknis kepada pengguna.
- Belum ada catatan (log) ketika terjadi error.
- Setelah form disubmit, ketika halaman di-refresh, data dapat terkirim kembali.
- Fitur CRUD belum terintegrasi secara lengkap dengan MySQL.
Untuk mengatasi masalah tersebut, aplikasi akan diperbaiki dengan menerapkan
Security, Exception Handling, Logging, PRG, dan CRUD lengkap.
CRUD Mahasiswa Aman dengan MySQL

Tujuan

Mahasiswa membuat aplikasi CRUD Data Mahasiswa yang terintegrasi dengan
MySQL serta menerapkan:
- Security
- Validasi input
- Prepared Statement
- Exception Handling
- Logging
- Session
- Flash Message
- PRG
- CRUD lengkap

Langkah Praktikum

1. Buat database MySQL

Buat database:

```
   CREATE DATABASE si_akademik;
```

Kemudian buat tabel:

```
   CREATE TABLE mahasiswa (
       id INT AUTO_INCREMENT PRIMARY KEY,
       nim VARCHAR(20) NOT NULL UNIQUE,
       nama VARCHAR(100) NOT NULL,
       email VARCHAR(100) NOT NULL,
       angkatan YEAR NOT NULL
   );
```

2. Hubungkan aplikasi dengan MySQL

Gunakan PDO untuk membuat koneksi database.

3. Pastikan Repository menggunakan Prepared Statement

Implementasikan method:

all()
find()
create()
update()
delete()
existsByNim()

4. Buat MahasiswaService

Service bertugas melakukan:
- validasi NIM,
- validasi nama,
- validasi email,
- pengecekan NIM duplikat,
- memanggil Repository.

5. Terapkan Exception Handling

Gunakan try-catch pada proses CRUD yang berpotensi mengalami error.

6. Buat Logging

Jika terjadi exception, simpan detail error ke:

storage/logs/app.log

Jangan tampilkan detail error database kepada pengguna.

7. Terapkan Flash Message

Berikan pesan seperti:

✓ Data mahasiswa berhasil ditambahkan.
✓ Data mahasiswa berhasil diubah.
✓ Data mahasiswa berhasil dihapus.
✕ Data gagal disimpan.
✕ NIM sudah terdaftar.

8. Terapkan PRG

Setelah proses create, update, atau delete berhasil:

     POST → proses → redirect → GET

     Pastikan ketika halaman di-refresh, data tidak dikirim ulang.

9. Buat CRUD lengkap

Aplikasi minimal memiliki halaman:
```
     /mahasiswa
         → daftar mahasiswa

     /mahasiswa/create
         → form tambah

     /mahasiswa/edit?id=1
         → form edit

     /mahasiswa/delete
         → proses hapus
```

Skenario Pengujian

Mahasiswa wajib menguji:

1. Tambah mahasiswa dengan data valid.
2. Tambah mahasiswa dengan NIM kosong.
3. Tambah mahasiswa dengan NIM yang sudah terdaftar.
4. Masukkan email yang tidak valid.
5. Edit data mahasiswa.
6. Hapus data mahasiswa.
7. Refresh halaman setelah proses tambah.
8. Simulasikan error database.
9. Periksa apakah error tercatat di app.log.
10. Pastikan pengguna hanya mendapatkan pesan error yang aman dan tidak
menampilkan informasi teknis database.

Output Praktikum
Mahasiswa menghasilkan:
- Aplikasi CRUD Mahasiswa.
- Database MySQL.

- Repository dengan Prepared Statement.
- Service dengan validasi dan Business Logic.
- Exception Handling.
- File app.log.
- Flash Message.
- Implementasi PRG.
- Repository GitHub berisi source code.

Pemahaman yang Harus Dicapai

Mahasiswa memahami bahwa aplikasi yang baik bukan hanya aplikasi yang bisa
menyimpan data, tetapi juga harus:
aman → tervalidasi → mampu menangani error → mencatat error →
memberikan feedback kepada pengguna → dan mencegah pengiriman form
berulang.

Alur akhirnya:

User
↓
Controller
↓
Service
↓
Validasi
↓
Repository
↓
MySQL
↓
Exception jika terjadi error
↓
Logging
↓
Flash Message
↓
Redirect (PRG)
↓
User melihat hasil

---

# Acara 15

**Pokok Bahasan:** Konsep API dan JSON

## Dasar Teori

Apa itu API?
API (Application Programming Interface) adalah mekanisme yang
memungkinkan satu aplikasi berkomunikasi dengan aplikasi atau sistem lainnya.
Contohnya, aplikasi frontend membutuhkan data mahasiswa dari backend. Frontend
tidak langsung mengakses database, tetapi meminta data melalui API.

Contoh sederhana:

Aplikasi meminta:
```
"Berikan daftar mahasiswa"
```

API memproses permintaan tersebut,
mengambil data dari database,
kemudian mengirimkan hasilnya kembali.

API dalam Aplikasi Web

Pada aplikasi web, komunikasi API biasanya menggunakan protokol HTTP.
Beberapa HTTP method yang umum digunakan:

Method Fungsi Contoh

```
 GET             Mengambil data               Menampilkan mahasiswa

 POST            Menambahkan data             Menambah mahasiswa
```

PUT/PATCH Mengubah data Mengubah mahasiswa

```
 DELETE          Menghapus data               Menghapus mahasiswa
```

Contoh:

```
GET      /api/mahasiswa
POST     /api/mahasiswa
PUT      /api/mahasiswa/1
DELETE   /api/mahasiswa/1
```

Apa itu JSON?

JSON (JavaScript Object Notation) adalah format pertukaran data yang ringan
dan mudah dibaca manusia maupun komputer. JSON banyak digunakan sebagai
format request dan response API.
Contoh data mahasiswa:

```
{
    "id": 1,
    "nim": "23201001",
    "nama": "Budi",
    "email": "budi@gmail.com"
}
```

Data tersebut memiliki pasangan:

key → value

Contohnya:

```
"nama" → "Budi"
"email" → "budi@gmail.com"
```

JSON Object dan Array

```
Object menggunakan { }.

{
    "nama": "Budi",
    "nim": "23201001"
}
```

Sedangkan kumpulan data menggunakan array [ ].

```
[
    {
         "id": 1,
         "nama": "Budi"
    },
    {
         "id": 2,
         "nama": "Siti"
    }
]
```

JSON sebagai Response API

Misalnya frontend meminta:

```
GET /api/mahasiswa
```

API dapat memberikan response:

```
{
    "success": true,
    "data": [
        {
            "id": 1,

                   "nim": "23201001",
                   "nama": "Budi"
              },
              {
                   "id": 2,
                   "nim": "23201002",
                   "nama": "Siti"
              }
        ]
}
```

Frontend kemudian membaca JSON tersebut dan menampilkan data kepada
pengguna.

JSON pada PHP

PHP dapat mengubah array menjadi JSON menggunakan json_encode().

```
$data = [
    'id' => 1,
    'nama' => 'Budi',
    'email' => 'budi@gmail.com'
];

echo json_encode($data);
```

Hasil:

```
{
        "id": 1,
        "nama": "Budi",
        "email": "budi@gmail.com"
}
```

Sebaliknya, JSON dapat diubah menjadi array PHP menggunakan json_decode().

```
$json = '{"nama":"Budi","nim":"23201001"}';

$data = json_decode($json, true);

echo $data['nama'];
```

Hasil:

Budi

Hubungan API dan JSON

API dan JSON bukan hal yang sama.

- API → mekanisme/interface untuk berkomunikasi.
- JSON → salah satu format yang digunakan untuk mengirim data.

Gambaran sederhananya:

API adalah jalan komunikasi antar aplikasi, sedangkan JSON adalah salah satu
format data yang digunakan dalam komunikasi tersebut.

## Prosedur Kerja

Studi Kasus Membuat API Sederhana dan Response JSON

Buatlah API Data Mahasiswa yang dapat digunakan oleh aplikasi lain untuk
mengambil data mahasiswa.
Langkah Praktikum

1. Buat database dan tabel

```
            CREATE DATABASE si_akademik;

            USE si_akademik;

            CREATE TABLE mahasiswa (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nim VARCHAR(20) NOT NULL,
                nama VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL

   );
```

Tambahkan beberapa data:

   INSERT INTO mahasiswa (nim, nama, email) VALUES

('23001', 'Budi', 'budi@gmail.com'),
('23002', 'Siti', 'siti@gmail.com'),
```
   ('23003', 'Andi', 'andi@gmail.com');
```

2. Buat endpoint API

Buat file:

api/
└── mahasiswa.php

Endpoint tersebut bertugas mengambil data mahasiswa dari MySQL.

3. Buat koneksi PDO

Gunakan PDO untuk terhubung ke database.

4. Ambil data mahasiswa

Gunakan query SELECT untuk mengambil seluruh data mahasiswa.

5. Ubah hasil query menjadi JSON

Gunakan:

```
   header('Content-Type: application/json');

   echo json_encode($data);
```

Response yang dihasilkan:

```
   [
        {
             "id": 1,
             "nim": "23001",
             "nama": "Budi",
             "email": "budi@gmail.com"
        },
        {
             "id": 2,
             "nim": "23002",
             "nama": "Siti",
             "email": "siti@gmail.com"
        }
   ]
```

6. Buat response yang lebih terstruktur

Gunakan format:
```
   {
        "success": true,
        "message": "Data berhasil diambil",
        "data": []
   }
```

7. Uji API

Jalankan endpoint melalui browser:

```
              http://localhost/si-akademik/api/mahasiswa.php

              Kemudian uji kembali menggunakan Postman.

      Tugas Pengembangan

      Setelah API GET berhasil, tambahkan endpoint:

      GET         /api/mahasiswa
      GET         /api/mahasiswa?id=1
      POST        /api/mahasiswa

      Untuk POST, kirim data dalam format JSON:

      {
              "nim": "23004",
              "nama": "Dewi",
              "email": "dewi@gmail.com"
      }

      Output Praktikum
      Mahasiswa mengumpulkan:
```

- Source code API.
- Database MySQL.
- Screenshot pengujian menggunakan Postman.
- Contoh JSON response.
- Minimal endpoint GET dan POST berhasil dijalankan.

---

# Acara 16

**Pokok Bahasan:** RESTful API + endpoint request/response

## Dasar Teori

Pengertian RESTful API

RESTful API adalah API yang menggunakan prinsip REST (Representational State
Transfer) untuk menyediakan dan mengelola data melalui HTTP. Dalam RESTful API,
data biasanya direpresentasikan sebagai resource. Contohnya pada Sistem Informasi
Akademik:
mahasiswa
prodi
dosen
mata_kuliah

Setiap resource memiliki endpoint.
Contoh:

```
/api/mahasiswa
/api/prodi
/api/dosen
```

RESTful API biasanya menggunakan HTTP Method untuk menentukan operasi yang
dilakukan.
Method Fungsi Contoh

```
        GET            Mengambil data                      /api/mahasiswa

        POST           Menambahkan data                    /api/mahasiswa

        PUT            Mengubah seluruh data               /api/mahasiswa/1

        PATCH          Mengubah sebagian data              /api/mahasiswa/1

        DELETE         Menghapus data                      /api/mahasiswa/1
```

Contoh Alur CRUD REST API
```
GET      /api/mahasiswa      → mengambil semua mahasiswa
GET      /api/mahasiswa/1    → mengambil mahasiswa ID 1
POST     /api/mahasiswa      → menambah mahasiswa
PUT      /api/mahasiswa/1    → mengubah mahasiswa ID 1
DELETE   /api/mahasiswa/1    → menghapus mahasiswa ID 1
```

Intinya: RESTful API menggunakan resource + HTTP method untuk menentukan
tindakan terhadap data.

Endpoint
Endpoint adalah alamat yang digunakan untuk mengakses fungsi tertentu pada API.
Contoh:
```
http://localhost/si-akademik/api/mahasiswa
```

Endpoint dapat memiliki parameter.
```
/api/mahasiswa/1
```

Angka 1 menunjukkan ID mahasiswa yang ingin diakses.

Request

Request adalah permintaan yang dikirim client kepada API. Request dapat terdiri
dari:
- HTTP Method
- URL/Endpoint
- Header
- Parameter
- Body
Contoh request:
```
POST /api/mahasiswa
```

Content-Type: application/json

Body:

```
{
    "nim": "23004",
    "nama": "Dewi",
    "email": "dewi@gmail.com"
}
```

Artinya:

Client meminta API untuk membuat data mahasiswa baru.

Response

Response adalah jawaban yang diberikan API kepada client. Contoh response
berhasil:
```
{
      "success": true,
      "message": "Data mahasiswa berhasil ditambahkan",
      "data": {
          "id": 4,
          "nim": "23004",
          "nama": "Dewi"
      }
}
```

Response juga memiliki HTTP Status Code.

Status Arti
200 Request berhasil
201 Data berhasil dibuat
400 Request tidak valid
404 Data/endpoint tidak ditemukan
500 Terjadi error pada server

Contohnya ketika data berhasil dibuat:

HTTP/1.1 201 Created

Postman
Postman adalah aplikasi yang digunakan untuk membuat, mengirim, dan menguji
HTTP request ke API. Dengan Postman, mahasiswa dapat menguji API tanpa harus
membuat frontend terlebih dahulu.

Membuat Request GET

1. Buka Postman.
2. Pilih GET
3. Masukkan:
```
       http://localhost/si-akademik/api/mahasiswa
```

4. Klik Send.
5. Jika berhasil, Postman akan menampilkan:
```
    {
        "success": true,
        "data": [
            {
                "id": 1,
                "nim": "23001",
                "nama": "Budi"
            }
        ]
    }
```

Mengirim POST dengan Postman

1. Pilih: POST
2. URL:
```
    http://localhost/si-akademik/api/mahasiswa
```

3. Kemudian pilih:
Body → raw → JSON
4. Masukkan:
```
    {
        "nim": "23005",
        "nama": "Rina",
        "email": "rina@gmail.com"
    }
```

5. Klik Send.
6. Jika berhasil:
```
    {
        "success": true,
        "message": "Data mahasiswa berhasil ditambahkan"
    }
```

Menguji PUT

1. Pilih: PUT
2. URL:
```
    http://localhost/si-akademik/api/mahasiswa/1
```

3. Body:
```
    {
        "nim": "23001",
        "nama": "Budi Santoso",
        "email": "budi.santoso@gmail.com"
    }
```

4. Klik Send.

Menguji DELETE
1. Pilih: DELETE
2. URL:
```
    http://localhost/si-akademik/api/mahasiswa/1
```

3. Klik Send.
4. Jika berhasil:
```
              {
                   "success": true,
                   "message": "Data mahasiswa berhasil dihapus"
              }
```

## Prosedur Kerja

Membuat dan Menguji RESTful API

Studi Kasus

Kembangkan API Data Mahasiswa yang telah dibuat pada praktikum sebelumnya
menjadi RESTful API.

Mahasiswa harus membuat endpoint:

```
     GET          /api/mahasiswa
     GET          /api/mahasiswa/{id}
     POST         /api/mahasiswa
     PUT          /api/mahasiswa/{id}
     DELETE       /api/mahasiswa/{id}

     Tugas
```

1. Implementasikan endpoint GET untuk menampilkan seluruh mahasiswa.
2. Implementasikan GET berdasarkan ID.
3. Implementasikan POST untuk menambahkan mahasiswa.
4. Implementasikan PUT untuk mengubah mahasiswa.
5. Implementasikan DELETE untuk menghapus mahasiswa.
6. Gunakan JSON sebagai format request dan response.
7. Gunakan HTTP Status Code yang sesuai.
8. Uji seluruh endpoint menggunakan Postman.
9. Uji juga kondisi error, misalnya:
 - ID tidak ditemukan.
 - NIM kosong.
 - Email tidak valid.

 - Data JSON tidak lengkap.

Output Praktikum
Mahasiswa mengumpulkan:
- Source code RESTful API.
- Database MySQL.
- Collection Postman berisi seluruh endpoint.
- Screenshot pengujian GET, POST, PUT, dan DELETE.
- Contoh response berhasil dan response error.

Kompetensi Akhir

Mahasiswa mampu memahami bahwa:

Endpoint
+
HTTP Method
+
Request
↓
RESTful API
↓
Database
↓
Response JSON
Dan Postman digunakan sebagai alat untuk menguji apakah API tersebut
bekerja sesuai dengan request dan response yang dirancang.

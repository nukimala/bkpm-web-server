# Presentasi & Catatan Belajar — Pembuatan Aplikasi Web dengan PHP & MySQL
### Rentang materi: Acara 1 s.d. 15 (proyek `si-akademik`)

> Dokumen ini adalah hasil belajar interaktif: konsep → kode → demo → poin presentasi.
> Ditulis bertahap, satu sesi per pertemuan. Semua referensi file relatif ke `C:\laragon\www\webserver\`.

---

## Cara memakai dokumen ini

- **Konsep** — pahami dulu "kenapa", baru lihat kode.
- **Kode** — buka file yang disebut, cocokkan penjelasan dengan barisnya.
- **Poin presentasi** — kalimat siap ucap; hafalkan intinya, bukan kalimatnya.
- **Tanya-jawab** — simulasi pertanyaan dosen + jawaban yang bisa dipakai.

---

# SESI 1 — Acara 1 s.d. 3: Client-Server, HTTP, PHP Dasar, dan Ide MVC

## 1. Konsep inti: Web adalah percakapan antara 2 program

```
Browser Anda (CLIENT)                     Server (Apache + PHP)
-----------------------                  -----------------------
1. Ketik http://localhost/webserver/...           |
2. Kirim HTTP REQUEST  ──────────────────────────► | 3. Baca request
                                                   | 4. Eksekusi file PHP
5. Terima HTTP RESPONSE ◄──────────────────────── |    (jalankan kode, hitung)
   (berupa HTML)                                  | 5. Kirim hasil HTML + status
```

Poin kunci:
- PHP adalah **server-side** → kode dijalankan di server, BUKAN di browser.
- Browser hanya menerima **hasilnya** (HTML). Kode `<?php ... ?>` tidak pernah sampai ke browser sebagai kode.
- HTTP **stateless** → tiap request "lupa" request sebelumnya. Untuk mengingat, kita butuh *session* (Acara 6).

## 2. `file://` vs `http://` — kenapa PHP harus lewat server

- `file://` → browser ambil file **mentah** tanpa server. Tag PHP tidak dieksekusi (tampil sebagai teks).
- `http://` → request lewat Apache; Apache mengeksekusi PHP, mengirim hasil.

**Demo presentasi:** buka file `Acara1/belajar-backend/index.php` via `file://`, lalu via `http://localhost/webserver/Acara1/belajar-backend/index.php`. Bandingkan: file:// = kode utuh, http:// = hasil eksekusi.

## 3. Bedah kode Acara 1

`Acara1/belajar-backend/index.php`

```php
<?php
echo "<h1>Halo dari Server!</h1>";
echo "<div class='box'>";
echo "<p>Waktu server saat ini: " . date("Y-m-d H:i:s") . "</p>";
echo "</div>";
?>
```

- `echo` → cetak string ke output HTML.
- `.` (titik) → operator **penggabung string** di PHP.
- `date("Y-m-d H:i:s")` → jam yang tampil adalah **waktu server**, bukan jam mesin user → bukti eksekusi di server.

`Acara1/belajar-backend/info.php`
- `phpversion()` → versi PHP server (punya kita: 8.2/8.3).
- `PHP_OS` (konstanta) → OS server.

## 4. Acara 2 (dirangkum) — dasar HTTP & form

(Tidak ada folder khusus; konsepnya tercakup di Acara 1 dan 3.)
- Form HTML pakai `method="GET"` atau `method="POST"`.
  - GET → data lewat URL (`?nim=...`), untuk membaca.
  - POST → data lewat body request, untuk menyimpan/ubah.
- `$_GET` dan `$_POST` di PHP adalah array tempat data form masuk.

## 5. Acara 3 — kenalan dengan struktur MVC

Folder `Acara3/si-akademik/`:

```
si-akademik/
├── public/            ← satu-satunya yang boleh diakses browser
│   └── index.php      ← "F R O N T   C O N T R O L L E R"
├── config/app.php     ← pengaturan global (BASE_PATH)
├── app/Views/...      ← tampilan (Mahasiswa: index & create)
└── (app/Controllers, app/Models, routes/ muncul di Acara 4-5)
```

Apa itu **MVC**?
- **M**odel → data + aturannya (tabel DB, validasi).
- **V**iew → tampilan ke user (HTML).
- **C**ontroller → perantara: terima request, minta data ke Model, kirim ke View.

Kenapa dipisah? → mudah dipelihara, masing-masing punya 1 tanggung jawab, dan yang boleh dibuka publik hanya folder `public/`.

### Front controller — satu pintu masuk

`public/index.php`
```php
require_once __DIR__ . '/../config/app.php';
```
- `__DIR__` = folder tempat file ini berada (`public`).
- `/../config/app.php` → naik 1 folder, ambil config.
- Semua request di masa depan akan masuk lewat file ini → dibuat ringan dulu: `echo "MVC siappp";`

### BASE_PATH — link yang tidak mudah patah

`config/app.php`
```php
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/'));
```
- URL project dihitung otomatis dari lokasi `public/index.php` → kalau folder dipindah / dinama-ulang, link tetap benar (tidak hardcode `http://localhost/...`).

### View dengan Bootstrap (Acara 3 bagian visual)

`app/Views/mahasiswa/index.php`
- Bootstrap 5.3.3 dipakai lewat CDN (baris 7 & 28).
- `<?= BASE_PATH ?>` = kependekan `<?php echo BASE_PATH ?>`.
- Link "Tambah Mahasiswa" → `<?= BASE_PATH ?>/mahasiswa/create`.

`app/Views/mahasiswa/create.php`
- Form `method="POST"` action `<?= BASE_PATH ?>/mahasiswa`.
- `name="nim"`, `name="nama"`, `<select name="prodi">` → nanti nama-nama ini yang dibaca controller (Acara 5) sebagai `$_POST`.

Apa itu Bootstrap? → kerangka CSS+JS siap pakai: `container`, `btn btn-primary`, `table table-striped`, `form-control`. Tanpa menulis CSS manual.

## 6. Poin presentasi siap ucap

1. "Web dinamis berarti halaman **dihasilkan server**: data (Model), logika (Controller), tampilan (View) dipisah."
2. "MVC bukan framework — ini **pola struktur kode** supaya kita bisa membangun bertahap dari acara ke acara."
3. (Demo kuat) "Buka file yang sama pakai `file://` vs `http://`: terbukti PHP hanya jalan lewat server."

## 7. Tanya-jawab (simulasi dosen)

- **Q:** Apa beda web statis vs dinamis? → Statis: server kirim file apa adanya. Dinamis: server proses dulu (PHP+DB) baru kirim hasil.
- **Q:** Kenapa `file://` tidak menjalankan PHP? → Karena eksekusi PHP butuh server; `file://` mem-bypass server.
- **Q:** Apa keuntungan memisah folder `public/` dari `app/`? → Keamanan (yang ter-expose hanya public), organisasi, dan kerja tim yang jelas.
- **Q:** GET vs POST bedanya apa? → GET taruh data di URL (bisa di-bookmark), POST di body (lebih aman untuk menyimpan data).

---

# SESI 2 — Acara 4 s.d. 5: Layout/Partial, Dasar OOP, Routing & URL Bersih

## Bagian A. Acara 4 — Layout & partial (hindari duplikasi HTML)

Struktur yang muncul di Acara 4:

```
app/
├── Models/Mahasiswa.php     ← kelas Mahasiswa (OOP pertama)
└── Views/
    ├── layouts/main.php     ← kerangka halaman (header+navbar+isi+footer)
    ├── partials/            ← potongan: header, navbar, footer
    └── mahasiswa/           ← halaman spesifik (index, create)
```

### Alur render layout

`public/index.php`
```php
$content = __DIR__ . '/../app/Views/mahasiswa/index.php';
require __DIR__ . '/../app/Views/layouts/main.php';
```

`app/Views/layouts/main.php`
```php
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<main class="container mt-4">
    <?php require $content; ?>   <!-- view konten disisipkan DI SINI -->
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
```

- `include` vs `require`: file tidak ketemu → `include` hanya warning (jalan terus), `require` fatal error (berhenti). Layout wajib ada → pakai `require`.
- `$content` adalah *path* yang diset controller, lalu di-`require` di tengah layout → satu kerangka, banyak konten.
- Partial `header.php` memakai `<?php echo $title ?? 'SI Akademik'; ?>` — operator **null coalescing** `??`: pakai `$title` bila sudah ada, kalau belum pakai default.

### OOP pertama: kelas `Mahasiswa`

`app/Models/Mahasiswa.php`
```php
class Mahasiswa
{
    private string $nim;                        // property privat + tipe data
    private string $nama;
    private string $prodi;
    private string $status;

    public function __construct(string $nim, string $nama, string $prodi, string $status = 'aktif')
    {
        $this->nim = $nim;  $this->nama = $nama;  $this->prodi = $prodi;  $this->status = $status;
    }

    public function getNim(): string   { return $this->nim; }
    // ... getNama(), getProdi(), getStatus() serupa ...

    public function getAngkatan(): int
    {
        $duaDigit = substr($this->nim, 0, 2);
        return (int)('20' . $duaDigit);   // 2401001 -> 2024
    }
}
```

Konsep penting:
- **Kapsulasi**: properti `private` → hanya kelas sendiri yang bisa set; dunia luar dipaksa lewat **getter**. Data tetap konsisten.
- **Constructor** `__construct` → dijalankan otomatis saat `new Mahasiswa(...)`. Parameter ke-4 punya **default value** `'aktif'` → boleh diisi, boleh tidak.
- **Type declaration** (`string`, `int`, `: string`) → PHP menolak nilai yang salah tipe sejak awal (PHP 7+).
- **Method turunan** `getAngkatan()` → bukan menyimpan data, tapi *menghitung* dari data (`substr` ambil 2 digit awal, cast `(int)`).

## Bagian B. Acara 5 — Routing & URL bersih

### Alur 1 request ke `/mahasiswa`

```
Browser
   │  request /mahasiswa
   ▼
public/.htaccess          RewriteEngine On; RewriteCond !-f !-d; RewriteRule ^ index.php [QSA,L]
   │  ⇒ semua request non-file diteruskan ke index.php (front controller)
   ▼
public/index.php          1. ambil REQUEST_URI, buang BASE_PATH → sisakan 'mahasiswa'
                          2. muat routes/web.php
                          3. new Router($routes); $router->dispatch('mahasiswa')
   ▼
app/Core/Router.php       1. cek route sesuai REQUEST_METHOD (GET)
                          2. cocokkan regex, pecah 'MahasiswaController@index'
                          3. require file controller, new objek, panggil ->index()
   ▼
MahasiswaController::index()  set $data & $content → render layouts/main.php → HTML
```

### `.htaccess` (front controller rewrite)

`public/.htaccess`
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```
- Hanya request yang BUKAN file/folder asli (`!-f`, `!-d`) yang diarahkan ke `index.php` → aset statis (gambar/css) tetap bisa diakses langsung.
- `[L]` = last rule (berhenti), `[QSA]` = pertahankan query string (`?page=2`).
- Hasilnya: URL bersih `.../mahasiswa` tanpa `?r=`.

### `public/index.php` — membersihkan URI

```php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);  // path saja, tanpa query
if (str_starts_with($uri, $base_path)) {
    $uri = substr($uri, strlen($base_path));               // buang "/webserver/Acara5/si-akademik/public"
}
$uri = trim($uri, '/');
```
→ BASE_PATH membuat app portabel; di server produksi nilai prefixnya beda tapi kode tidak berubah.

### `routes/web.php` — tabel route per method

```php
return [
    'GET' => [
        ''                 => 'HomeController@index',
        'mahasiswa'        => 'MahasiswaController@index',
        'mahasiswa/create' => 'MahasiswaController@create',   // spesifik DULU
        'mahasiswa/{id}'   => 'MahasiswaController@show',     // baru yang umum
    ],
    'POST' => [
        'mahasiswa'        => 'MahasiswaController@store',
    ],
];
```
- Urutan penting: `mahasiswa/create` ditulis sebelum `mahasiswa/{id}` supaya "create" tidak tertelan pola `{id}`.

### `Router.php` — otak pencocokan

```php
public function dispatch(string $uri): void
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if (isset($this->routes[$method]) && $this->match($this->routes[$method], $uri)) {
        return;                                                    // sukses!
    }
    foreach ($this->routes as $otherMethod => $group) {           // path ada,
        if ($otherMethod !== $method && $this->match($group, $uri, false)) {
            http_response_code(405);                              // tapi method salah
            echo '405 - Metode HTTP tidak diizinkan';
            return;
        }
    }
    http_response_code(404);                                      // path tidak ada
    echo '404 - Halaman tidak ditemukan';
}
```
- `match()`: `{id}` → `(?P<id>[a-zA-Z0-9_]+)` (regex named group); kalau cocok, `explode('@')` memecah handler, `new $controllerName()`, panggil `$controller->$method(...$params)` (spread operator → `show(1)`).
- Status `http_response_code()` → 200 default, 404, 405, 302 (redirect).

### Controller — `store` & redirect

`MahasiswaController.php`
```php
public function store(): void
{
    $nim  = trim($_POST['nim'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    if ($nim === '' || $nama === '') {
        header('Location: ' . BASE_PATH . '/mahasiswa/create');
        exit;                                   // WAJIB: hentikan eksekusi setelah header()
    }
    header('Location: ' . BASE_PATH . '/mahasiswa?saved=1');
    exit;
}
```
- **Pola PRG** (Post/Redirect/Get) pertama: setelah POST diproses, redirect → kalau user refresh, form tidak tersubmit dua kali.
- `exit` penting: tanpa itu kode di bawah `header()` tetap dieksekusi.
- Data masih hardcode di controller. Acara 7–8 akan menggantinya dengan database.

## 6. Poin presentasi (Sesi 2)

1. "Layout dan partial membuat tampilan konsisten tanpa menduplikasi HTML — controller cukup menyuplai `$content` dan `$title`."
2. "URL bersih & method-aware diraih dengan 3 pola kecil: `.htaccess` front controller + satu file route + satu router regex. Total ±60 baris, tanpa framework."
3. "Router mengecek method dulu — kalau path ada tapi method salah, kita balas `405`; kalau path tidak ada, `404`." (Demo curl di atas membuktikan: 200/404/405/302.)

## 7. Tanya-jawab (Sesi 2)

- **Q:** Kenapa `.htaccess` perlu `!-f !-d`? → Supaya aset statis (css/gambar yang benar-benar ada) tetap dilayani langsung, hanya sisanya yang masuk routing.
- **Q:** Kenapa 405 dibedakan dari 404? → Agar API/browser tahu kondisi sebenarnya: alamatnya ada, cuma method salah (info penting mulai Acara 15).
- **Q:** Apa beda `include` dan `require`? → Severity saat file hilang: warning vs fatal error. Layout butuh `require`.
- **Q:** Kenapa properti dibuat `private`? → Enkapsulasi: mencegah nilai tidak valid masuk tanpa lewat getter/setter (dipakai validasi di Acara 9).

---

# SESI 3 — Acara 6: Session, Middleware & Autentikasi

## 1. Masalah: HTTP stateless

Server melupakan setiap request. Kalau user "login", bagaimana server tahu pada request berikutnya bahwa dia sudah login? Jawaban: **session**.

## 2. Cara kerja session

`public/index.php`
```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```
- `session_start()` → server membuat **cookie `PHPSESSID`** (ID acak) ke browser, dan menyimpan data user di **session store** di server.
- Request berikutnya membawa cookie ID itu → server mencocokkan → tahu user yang sama.
- Browser tidak tahu isi data; hanya pegang ID. **Data ada di server.**

## 3. Route config: dari string ke array

`routes/web.php`
```php
'auth'      => ['handler' => 'AuthController@login', 'method' => 'POST'],
'dashboard' => ['handler' => 'DashboardController@index', 'middleware' => ['AuthMiddleware']],
```
Router (Router.php) membaca: path → `method` (wajib untuk POST) → **middleware dijalankan dulu** → baru controller.

## 4. Middleware = penjaga pintu

`app/Core/Middleware/AuthMiddleware.php`
```php
public static function isLoggedIn(): bool
{
    return isset($_SESSION['user']) && $_SESSION['user'] !== '';
}

public function handle(): void
{
    session_start();
    if (!self::isLoggedIn()) {
        $_SESSION['flash'] = 'Silakan login terlebih dahulu.';
        header('Location: ' . BASE_PATH . '/login');
        exit;                       // blokir sebelum controller dijalankan
    }
}
```
- Dipanggil oleh `Router::runMiddleware()` SEBELUM `$controller->$action()` dieksekusi.
- Jika belum login → redirect ke `/login` + pesan flash. Controller tidak pernah jalan.

## 5. Base Controller (file suci DRY)

`app/Core/Controller.php` — semua controller `extends Controller`, mewarisi:
```php
protected function render(string $view, array $data = []): void
{
    extract($data);                              // array -> variabel ($mahasiswa)
    $content = __DIR__ . '/../Views/' . $view;
    require __DIR__ . '/../Views/layouts/main.php';
}

protected function redirect(string $path): void
{
    header('Location: ' . $this->base_path . '/' . ltrim($path, '/'));
    exit;
}
```
- `render()` + `extract()` menggantikan pola manual (set `$content`, `require` layout) dari Acara 4-5 — satu tempat.
- `redirect()` membungkus `header('Location') + exit` → tidak ada lagi lupa `exit`.

### Flash message (Controller::setFlash / flash)
```php
$_SESSION['flash'] = $message;   // simpan pesan
...
$message = $_SESSION['flash'];  unset($_SESSION['flash']);  // baca SEKALI lalu hapus
```
- Flash = pesan sepintas (hanya tampil pada request berikutnya, langsung dihapus).

## 6. Login / logout

`app/Controllers/AuthController.php`
```php
if ($username === self::VALID_USERNAME && $password === self::VALID_PASSWORD) {
    $_SESSION['user'] = $username;
    $this->redirect('dashboard');
}
self::setFlash('Username atau password salah.');  // gagal -> form + pesan
```
```php
public function logout(): void
{
    unset($_SESSION['user']); session_unset(); session_destroy();
    session_start();
    self::setFlash('Anda telah logout');
    $this->redirect('login');
}
```
- Kredensial masih `const` hardcode (`admin`/`admin123`). Database + hash menyusul di Acara 14.
- `loginForm` me-redirect ke `/dashboard` bila sudah login (anti halaman login berulang).

## 7. Proteksi folder non-public (.htaccess di root project)

`Acara6/si-akademik/.htaccess`
```apache
RewriteEngine On
RewriteRule ^(app|config|routes|storage|vendor)/ - [F,L]
```
- `[F]` = Forbidden (403). Folder internal tidak pernah sampai ke browser.
- Berlapis: browser hanya masuk lewat `public/` (front controller); folder lain ditutup paksa.
- Pola inilah yang rencana disalin ke semua acara lain (rencana repo-hygiene, ditunda).

## 8. Poin presentasi (Sesi 3)

1. "Web itu stateless — session adalah cara server 'mengingat' user lewat cookie ID, tanpa membocorkan data."
2. "Middleware adalah gerbang: dipasang di route, dicek sebelum controller. Login dulu, baru boleh masuk."
3. "Base Controller hilangkan duplikasi: satu `render`, satu `redirect`, satu `flash` — semua controller cuma mewarisi."

## 9. Tanya-jawab (Sesi 3)

- **Q:** Dimana data session disimpan? → Di server (mis. folder session Laragon); browser hanya punya cookie ID.
- **Q:** Kenapa logout menghancurkan session, bukan hanya unset? → `session_unset()` + `session_destroy()` membersihkan data & penghubungnya; hanya `unset($_SESSION['user'])` menyisakan session aktif.
- **Q:** Apa beda middleware vs validasi controller? → Middleware mengecek SEBELUM masuk (siapa dia), validasi mengecek isi data (benar/tidak). Keduanya bertabrakan rapi dengan controller tipis (puncaknya Acara 9 & 13).
- **Q:** Bisa tidak satu route punya banyak middleware? → Bisa, array `['A', 'B']` → dijalankan berurutan (Router::runMiddleware → foreach).

---

# SESI 4 — Acara 7 s.d. 8: Database (MySQL), Model & CRUD dengan PDO

## 1. Perubahan besar: data pindah dari kode ke MySQL

Sampai Acara 5–6 data mahasiswa hardcode di controller. Mulai Acara 7 data hidup di MySQL; aplikasi bertanya lewat **SQL**.

`config/database.php` — satu file pengaturan (per acara beda `dbname`):
```php
return [
    'host' => 'localhost', 'port' => 3306,
    'dbname' => 'si_akademik_a7', 'username' => 'root', 'password' => '',
    'charset' => 'utf8mb4',
];
```
> Catatan belajar: tiap acara memakai DB sendiri (`_a7`, `_a8`, ...) supaya tidak tabrakan.

## 2. Koneksi PDO (Acara 7 — `app/Models/Model.php`)

```php
protected static ?PDO $pdo = null;

protected static function connect(): PDO
{
    if (self::$pdo === null) {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // error -> exception
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,          // baris = array asosiatif
        ]);
    }
    return self::$pdo;
}
```
- `?PDO ... null` + cek → koneksi hanya dibuat **sekali** (lazy).
- `FETCH_ASSOC` → tiap baris menjadi `['nim' => ..., 'nama' => ...]`.

## 3. JOIN — ambil data dari tabel lain

`MahasiswaModel::all()`:
```php
SELECT m.id, m.nim, m.nama, ..., p.nama AS prodi
FROM mahasiswa m
JOIN prodi p ON p.id = m.prodi_id   -- nama prodi DIAMBIL dari tabel prodi
ORDER BY m.nim
```
- `mahasiswa` hanya menyimpan `prodi_id` (angka); nama prodi di-`JOIN` saat query → **normalisasi** (satu sumber kebenaran).

## 4. Prepared statement — kunci anti SQL injection

```php
$stmt = $this->pdo->prepare('... WHERE m.id = :id');
$stmt->execute(['id' => $id]);   // nilai dikirim TERPISAH dari template SQL
```
- `:id` = placeholder; nilai tidak pernah digabung ke string SQL.
- Kalau dipakai concatenation (`"...WHERE id=$id"`): input `1 OR 1=1` langsung membuat query mengembalikan semua baris, atau lebih parah `1; DROP TABLE mahasiswa`. Inilah **SQL injection**.
- Takeaway presentasi: *"Input user tidak pernah disisipkan mentah ke SQL — selalu lewat parameter."*

## 5. Object mapping (`toEntity`)

`MahasiswaModel.php:51-61` — baris DB (array) → objek `Mahasiswa`. Controller dapat objek, bukan array mentah.

## 6. Acara 8 — Database singleton + Repository pattern

`app/Core/Database.php`
```php
private function __construct() { ... }             // tidak boleh new langsung
public static function getInstance(): Database     // Satu instance global
```
- `getInstance()` → **satu koneksi PDO** dipakai semua repository.
- `PDO::ATTR_EMULATE_PREPARES => false` → prepared statement sungguhan dikirim ke MySQL (bukan emulasi PHP).

`transaction(callable)`
```php
$this->pdo->beginTransaction();
try { $result = $callback($this->pdo); $this->pdo->commit(); return $result; }
catch (Throwable $e) { $this->pdo->rollBack(); throw $e; }
```
- **Transaction** = rangkaian SQL satu kesatuan: semua sukses → `commit`; satu gagal → `rollBack` semua (data tidak setengah jadi).

`BaseRepository` — helper diwarisi: `count()`, `paginateQuery()` (LIMIT/OFFSET). Semua repo `extends BaseRepository` → dapat `$this->pdo`.

`MahasiswaRepository` — semua SQL tersentral di satu tempat:

| Method | SQL | Dipakai |
|---|---|---|
| `all()` | SELECT + JOIN | daftar |
| `find($id)` | `WHERE m.id = :id` | detail |
| `findByNim($nim)` | `WHERE m.nim = :nim` | cek duplikat (A9) |
| `search($kw)` | `LIKE :kw` (nim/nama/prodi) | pencarian |
| `paginate($page)` | `COUNT(*)` + LIMIT/OFFSET | halaman |
| `create($data)` | `INSERT INTO mahasiswa (...) VALUES (:nim, ...)` | tambah |
| `update($id,$data)` | `UPDATE ... SET ... WHERE id=:id` | ubah |
| `delete($id)` | `DELETE FROM mahasiswa WHERE id=:id` | hapus |

→ Seluruh sisi tulis aplikasi aman dari injection karena semua memakai placeholder.

## 7. Skema database (`database/si_akademik.sql`)

- 3 tabel: `prodi`, `mahasiswa`, `matakuliah`.
- `FOREIGN KEY fk_mahasiswa_prodi (prodi_id) REFERENCES prodi(id)` → **integritas referensial**: mahasiswa tidak bisa menunjuk prodi yang tidak ada.
- Seed `INSERT INTO prodi` data awal.

## 8. Poin presentasi (Sesi 4)

1. "Data dipisah dari kode: aplikasi bertanya ke MySQL lewat SQL; DB menjaga konsistensi dengan foreign key."
2. "Setiap input user tidak pernah di-concatenate ke SQL — selalu prepared statement `:param`." *(demo: ganti placeholder dengan `$id`, isi `1 OR 1=1`)*
3. "Repository memusatkan semua SQL — controller tidak perlu tahu SQL, tinggal minta data."

## 9. Tanya-jawab (Sesi 4)

- **Q:** Bedanya Model Acara 7 vs Repository Acara 8? → A7 model statis membawa SQL; A8+ SQL dipusatkan di Repository, controller tipis (mengarah ke Acara 10).
- **Q:** Kenapa JOIN, bukan simpan nama prodi di mahasiswa? → Normalisasi: nama prodi 1 sumber kebenaran.
- **Q:** Apa itu transaction? → Kumpulan SQL satu kesatuan: sukses semua commit, satu gagal rollback.

---

# SESI 5 — Acara 9 s.d. 10: Dependency Injection, Validasi, BaseController & BaseModel

## 1. Tema besar: controller tipis, aturan di tempat yang tepat

Setelah SQL dipindahkan ke Repository (A8), dua kebiasaan bersih berikutnya:
1. **Dependency Injection** — controller *diberi* dependensi lewat konstruktor (tidak membuat sendiri).
2. **Validasi satu sumber** — naik bertahap: di controller (A8) → di setter objek (A9) → di `validate()` model (A10).

## 2. Acara 9 — Constructor Injection

`MahasiswaController.php`
```php
public function __construct(Database $db)
{
    AuthMiddleware::requireLogin($this->basePath, false);
    $this->repo      = new MahasiswaRepository($db);
    $this->prodiRepo = new ProdiRepository($db);
}
```
- DB **disuntikkan** (bukan dibikin di dalam) → satu koneksi (Singleton) & mudah diganti.
- Bonus: proteksi login sekarang di **konstruktor** — semua method aman tanpa menempel middleware per-method.

Router sebagai "mini DI container" (Router.php):
```php
$reflection = new ReflectionClass($name);            // introspeksi kelas
foreach ($constructor->getParameters() as $param) {
    if (tipe param === Database) $deps[] = Database::getInstance();
}
$reflection->newInstanceArgs($dependencies);          // bangun controller + suntik
```
- **Reflection** = PHP memeriksa kelas saat runtime untuk tahu kebutuhan konstruktor → autowiring sederhana (dipakai juga framework besar).

## 3. Acara 9 — validasi di SETTER (mustahil objek invalid)

`Models/Mahasiswa.php`
```php
public function setNim(string $nim): void
{
    $nim = trim($nim);
    if ($nim === '' || !ctype_digit($nim)) {
        throw new InvalidArgumentException('NIM tidak boleh kosong dan harus berupa angka.');
    }
    $this->nim = $nim;
}

public function setEmail(string $email): void
{
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) throw ...
}
public function setStatus(string $status): void
{
    if (!in_array($status, self::STATUS, true)) throw ...   // aktif/cuti/lulus
}
```
- Konstruktor memanggil **semua setter** → data jelek = `throw InvalidArgumentException` SEBELUM objek lahir.
- Controller menangkap & redirect dengan pesan (jalur tulis create/update tidak melewati validasi mustahil).

## 4. Flash sukses vs error + PRG

`Controller.php`
```php
protected function redirect(string $to, ?string $flash = null, ?string $error = null): void
{
    if ($flash !== null) $_SESSION['flash'] = $flash;
    if ($error  !== null) $_SESSION['flash_error'] = $error;
    header('Location: ' . $this->basePath . $to);
    exit;
}
```
- Pesan sukses dan error **dipisah** (`flash` / `flash_error`) → warna berbeda di view.

### `pesanError()` — jangan bocorkan pesan DB mentah
```php
if (str_contains($message, 'Duplicate entry'))        return 'Data sudah ada (kode/NIM sudah dipakai).';
if (str_contains($message, 'foreign key constraint')) return 'Data masih dipakai oleh tabel lain.';
return 'terjadi kesalahan pada database.';
```
- Bagi user, pesan internal DB (nama tabel, kolom) tidak perlu ditampilkan.

## 5. Acara 10 — abstract Base + DI container generik + validasi terpusat

### BaseController `abstract`
- `view()`, `redirect()` (flash `_success`/`_error`), `pesanError()` (ditambah case `Data too long`).
- `abstract` = tidak bisa di-`new`, hanya untuk `extends` → memaksa pola yang sama.

### Constructor promotion (PHP 8)
```php
public function __construct(
    private MahasiswaRepository $repo,
    private ProdiRepository $prodiRepo,
) {}
```
- 1 baris: deklarasi properti + isi parameter sekaligus (tidak perlu `$this->x = $x`).

### DI container generik & rekursif (Router.php)
```php
$dependencies[] = $this->make($type->getName());   // resolve rekursif
...
public function make(string $className):
    if ($className === 'Database') return Database::getInstance();
    return new $className($this->pdo());           // Repository cukup diberi PDO
    throw new RuntimeException('...');             // tak dikenal -> error jelas
```

### BaseModel `abstract` — helper DB satu tempat
```php
protected function db(string $sql, array $params = []): PDOStatement   // prepare+execute
protected function fetchAll(...) / fetchOne(...) / execute(...) / lastInsertId()
protected function transaction(callable $cb)                            // begin/commit/rollback
public function validate(array $data): array  { return []; }            // di-override model
```

### MahasiswaModel::validate() — array error (multi-error sekaligus)
```php
if ($data['nim'] === '')            $errors[] = 'NIM wajib diisi.';
elseif (!ctype_digit($data['nim'])) $errors[] = 'NIM harus berupa angka.';
...
if ($angkatan > (int) date('Y') + 1) $errors[] = 'Angkatan tidak valid.';   // anti tahun masa depan
return $errors;
```

### Repository memanggil model untuk validasi
```php
public function create(array $data): int {
    $errors = $this->model->validate($data);   // SEBELUM INSERT
    if ($errors) throw new InvalidArgumentException(implode(', ', $errors));
    // INSERT ... ; return $this->lastInsertId();
}
```

## 6. Alur kontrol yang terbentuk (A8 → A10)

```
Browser → Router (DI container) → Controller (tipis)
                                   ├─ minta data ke Repository   (semua SQL di sini)
                                   ├─ validasi lewat Model       (semua rule di sini)
                                   └─ render view / redirect + flash
```

## 7. Poin presentasi (Sesi 5)

1. "Dependency Injection = controller diberi kebutuhannya dari luar; Router jadi pabrik yang tahu membuat & mengumpan — bahkan rekursif."
2. "Validasi pindah dari controller → setter → satu method `validate()` model, selalu di jalur tulis → data jelek tak pernah sampai DB."
3. "BaseController & BaseModel `abstract` menghapus duplikasi: satu `view`, satu `redirect`, satu `db()` — turunan tinggal mewarisi."

## 8. Tanya-jawab (Sesi 5)

- **Q:** Kenapa tidak `new Database()` di dalam controller saja? → Koneksi jadi banyak, susah ditest, ketergantungan liar. Injeksi memberi satu titik kendali.
- **Q:** Apa itu Reflection, ada ongkosnya? → Introspeksi class saat runtime; sedikit overhead tapi wajar untuk autowiring (Laravel juga memakai).
- **Q:** Beda validasi throw vs array error? → Throw berhenti pada error pertama (rule wajib); array mengumpulkan semua masalah sekaligus (UX web). A9 pakai throw, A10 pakai array.
- **Q:** Kenapa kelas dibuat `abstract`? → Tidak boleh diinstansiasi langsung; hanya sebagai kerangka → mencegah pemakaian yang tidak konsisten.

---

# SESI 6 — Acara 11 s.d. 14: Git, Service Layer, Security & Logging

## Bagian A. Acara 11–12 — Git (version control)

Git menyimpan **snapshot** (commit) di setiap tahap proyek → kode bisa dibandingkan & dikembalikan.

Bagian paling meyakinkan untuk presentasi: **bukti nyata lewat riwayat repo ini.**

```
git log --oneline
72ad9b9 update databse per acara
c5b7a09 beberapa edit terkait penyesuaian acara
f26d013 tambahan acara 13,14,15
e64c951 commit untuk acara 1-10
```

Alur kerja dasar:
```
edit file → git add (staging) → git commit (snapshot) → git log / branch / merge
```
- `git status` → file berubah/tak terlacak (demo: 3 `.md` masih `??`).
- `.gitignore` (sejak Acara 6) → mencegah file sampah (vendor, log) ikut commit.

Poin presentasi: *"Git membuat tiap pertemuan menjadi commit — `git log` membuktikan progres acara-per-acara."*

## Bagian B. Acara 13 — Service Layer

Controller menjadi **tipis**: baca input → panggil service → pilih arah halaman (PRG).

`MahasiswaController::store`
```php
$result = $this->service->create($_POST);
if ($result['ok']) $this->redirect('/mahasiswa', 'Data mahasiswa berhasil ditambahkan.');
$_SESSION['errors'] = $result['errors'];
Session::withOldInput($_POST);          // isian form dipertahankan
$this->redirect('/mahasiswa/create', 'Periksa kembali isian form Anda.');
```

`MahasiswaService::create` — business logic di sini:
```php
$errors = $this->validator->validate($data, $this->rules());
if ($errors === [] && $this->repository->findByNim($data['nim'] ?? '')) {
    $errors['nim'][] = 'NIM sudah terdaftar. Gunakan NIM lain.';   // cek unik
}
if ($errors !== []) return ['ok' => false, 'errors' => $errors];
$this->repository->create($data);
return ['ok' => true, 'errors' => []];
```

Keunggulan: **aturan deklaratif + Validator reuse**
```php
'nim'    => 'required|digits|min:7|max:15',
'status' => 'required|in:aktif,nonaktif',
```
`Validator::validate()` meng-`explode('|',$rule)` lalu `switch` tiap aturan → bahasa aturan yang sama utk semua form (tidak ada `if` per field di controller). A14 menambah `existsByNim($nim, $id)` agar update tidak menganggap NIM-nya sendiri duplikat.

Lapisan yang terbentuk:
```
Controller (request) → Service (validasi + business rule) → Repository (SQL) → Database
```

## Bagian C. Acara 14 — Security & Logging

### login berbasis DB + hash
`AuthController`
```php
$user = $this->users->findByUsername($username);           // bukan const lagi!
if ($user !== null && password_verify($password, $user['password'])) {
    session_regenerate_id(true);                            // ganti ID sesi setelah login
    $_SESSION['user'] = $user['username'];
}
```
- Password disimpan **hash bcrypt** (`password_hash`; contoh `$2y$10$...` di dump tabel `users`), dicek `password_verify` → password asli tidak pernah ada di DB.
- `session_regenerate_id(true)` → anti **session fixation** (sesi lama tak bisa di-bajak).

### CSRF
`Csrf.php`
```php
public static function field(): string
{ return '<input type="hidden" name="_token" value="' . self::token() . '">'; }
public static function verify(): void
{
    if (!is_string($sent) || !hash_equals(self::token(), $sent)) {
        http_response_code(419);
        exit('Token CSRF tidak valid. Silakan muat ulang halaman.');
    }
}
```
- Tiap form menyisipkan token acak (di session); `verifyCsrf()` dipanggil di awal tiap POST → membuktikan form berasal dari kita, bukan situs lain.
- Demo: POST tanpa token → ditolak; dengan token benar → 302.
- `onlyPost()` mempertegas method POST (meneruskan filosofi 405 Acara 5).

### Logging
`Logger.php` → menulis `[timestamp] LEVEL pesan` ke `storage/logs/app.log`:
```php
$logger->info('LOGIN BERHASIL: ' . $username . ' dari IP ' . $_SERVER['REMOTE_ADDR']);
$logger->warning('LOGIN GAGAL: username "' . $username . '"');
```
- Admin dapat jejak penuh; **user** hanya dapat pesan generik (`pesanError` sejak Acara 9). Detail PDOException tidak pernah dibocorkan ke layar.

## 8. Poin presentasi (Sesi 6)

1. "Git menjadikan tiap pertemuan snapshot — `git log` bukti progres."
2. "Service Layer memusatkan business logic; controller cukup tahu `ok`/`errors`."
3. "Keamanan berlapis: hash password, token CSRF tiap POST, regenerate session saat login, detail error hanya di log."

## 9. Tanya-jawab (Sesi 6)

- **Q:** Beda Service vs Repository? → Service = aturan bisnis (validasi, unik NIM, alur); Repository = akses data (SQL murni).
- **Q:** Kenapa password di-hash? → Server tidak perlu tahu aslinya, cukup bisa membandingkan hash; DB bocor pun password tidak langsung terbaca.
- **Q:** CSRF attack itu apa? → Situs lain mengirim request "atas nama" kita karena cookie ikut terkirim; token acak di form membuktikan asal form.
- **Q:** Kenapa `session_regenerate_id` setelah login? → Mencegah penyusupan session ID (session fixation).

---

# SESI 7 — Acara 15: API, JSON & Status Code

## 1. Konsep: API = percakapan antar-program

Web biasa: browser ↔ server dengan HTML (untuk manusia). **API**: program ↔ program dengan **JSON**, dan HTTP method menjadi "kata kerja":

```
GET  /api/mahasiswa       → baca daftar   (READ)
GET  /api/mahasiswa/{nim} → baca satu     (READ)
POST /api/mahasiswa       → buat baru     (CREATE)
(PUT/DELETE = ubah/hapus → konsep REST, lanjutan Acara 16)
```

## 2. Jalur API dipecah sendiri

`public/index.php`
```php
if ($uri === 'api' || str_starts_with($uri, 'api/')) {
    // routes/api.php + ApiResponse; TIDAK lewat web Router/Session/middleware
}
```

`routes/api.php` — kunci route = `<METHOD> <path>`
```php
'GET api'                  => 'ApiController@info',
'GET api/mahasiswa'        => 'ApiController@mahasiswa',
'GET api/mahasiswa/{nim}'  => 'ApiController@mahasiswaDetail',
'POST api/mahasiswa'       => 'ApiController@mahasiswaStore',
```
- `explode(' ', $route, 2)` memecah method & path.
- Path cocok tapi method salah → **405**; path tak ada → **404** (dalam JSON).

## 3. Kontrak respons konsisten (`ApiResponse`)

```php
success(): { "success": true,  "message": "...", "data": ... }   // 200/201
error():   { "success": false, "message": "...", "errors": {...} } // 400/404/405/500

json(): 
  http_response_code($status);
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
  exit;
```
- Satu bentuk untuk semua endpoint → pemakai API pasti tahu cara bacanya.
- `JSON_UNESCAPED_UNICODE` → teks Indonesia tidak jadi `\uXXXX`.

## 4. Baca dengan query string

```php
$id = $_GET['id'] ?? null;
if ($id !== null && $id !== '') {
    if (!ctype_digit((string)$id)) { ApiResponse::error('Parameter id tidak valid.', 400); return; }
    $mhs = $this->mahasiswa->find((int)$id);
    if ($mhs === null) { ApiResponse::error('Mahasiswa dengan id ' . $id . ' tidak ditemukan.', 404); return; }
    ApiResponse::success($mhs);
}
ApiResponse::success($this->mahasiswa->all());   // tanpa ?id → semua
```

## 5. Terima body JSON (POST)

```php
$raw   = file_get_contents('php://input');   // body mentah (JSON), bukan $_POST
$input = json_decode($raw ?: '', true);
if (!is_array($input)) { ApiResponse::error('Body JSON tidak valid.', 400); return; }
// validasi per field (NIM wajib+angka+unik, nama wajib, prodi ada, status valid)
if (!empty($errors)) { ApiResponse::error('Data tidak valid.', 400, $errors); return; }
$this->mahasiswa->create([...]);
ApiResponse::success($this->mahasiswa->findByNim($nim), 'Data berhasil ditambahkan', 201);
```

## 6. Peta status code (terbukti live via curl)

| Kode | Arti | Contoh |
|---|---|---|
| 200 | OK | `GET /api/mahasiswa` |
| 201 | Created | POST valid |
| 400 | Bad Request | body bukan JSON / NIM duplikat / id bukan angka |
| 404 | Not Found | NIM/id & endpoint tidak ada |
| 405 | Method Not Allowed | POST ke endpoint GET-only |

## 7. Memakai API (curl & Postman)

```bash
curl -X POST http://localhost/.../api/mahasiswa \
     -H "Content-Type: application/json" \
     -d '{"nim":"7099999","nama":"Budi","prodi_id":1,"status":"aktif"}'
```
- **Postman**: method POST → *Body* → *raw* → JSON → Send.
- **REST** (lanjutan): `PUT /api/mahasiswa/{id}` = ubah, `DELETE .../{id}` = hapus, status sama; autentikasi API (token) = kelanjutan keamanan Acara 14.

## 8. Poin presentasi (Sesi 7)

1. "API memisahkan data dari tampilan — browser dan aplikasi lain memakai data yang sama lewat JSON."
2. "Kontrak `{success, message, data/errors}` membuat pemakai API selalu tahu hasilnya."
3. "Status code adalah bahasa antar mesin: 200/201/400/404/405."

## 9. Tanya-jawab (Sesi 7)

- **Q:** Kenapa API tidak pakai session/middleware di Acara 15? → API publik; autentikasi API (token/key) adalah langkah lanjutan — tulis sebagai roadmap.
- **Q:** `php://input` itu apa? → Alur baca body mentah request; untuk JSON POST tidak lewat `$_POST`.
- **Q:** Kenapa `json_encode` diakhiri `exit`? → Satu respons bersih; mencegah HTML lain ikut menempel.

---

# PENUTUP — peta perjalanan 1 → 15

```
Acara 1-3   Client-server, HTTP, PHP dasar, ide MVC     → "kenapa web"
Acara 4-5   Layout/OOP, routing & URL bersih (.htaccess) → "bagaimana request diarahkan"
Acara 6     Session, middleware, base controller         → "siapa yang boleh masuk"
Acara 7-8   MySQL, PDO, prepared statement, CRUD, JOIN   → "di mana data"
Acara 9-10  DI container, validasi, Base abstract        → "kontrol & reuse"
Acara 11-12 Git                                          → "bukti progres & kerja tim"
Acara 13    Service Layer, validator reusable            → "controller tipis"
Acara 14    Hash, CSRF, session regenerate, logging      → "aman & terlacak"
Acara 15    API + JSON + status code                     → "data bisa dipakai mesin lain"
```

Jalur yang terbentuk di akhir: `Request → Front controller → Router → Controller → Service → Repository → Database`, dengan penjaga berlapis (middleware, CSRF, validasi, log) serta kontrak respons API yang konsisten — persis seperti pola framework modern, tapi dibangun dari nol.

---

# GLOSARIUM

| Istilah | Arti singkat | Muncul di |
|---|---|---|
| Client / Server | Pembeda: yang meminta (browser/user) vs yang menyediakan (Apache+PHP) | S1 |
| HTTP Request / Response | Permintaan dari client / jawaban dari server | S1 |
| Stateless | Server melupakan tiap request sebelumnya (butuh session utk mengingat) | S1, S6 |
| Server-side | Kode dieksekusi di server, hasilnya dikirim (PHP vs HTML/JS) | S1 |
| MVC | Model=data, View=tampilan, Controller=perantara | S1, S3 |
| Front controller | Satu pintu masuk semua request (`public/index.php`) yang lalu menyerahkan ke router | S1, S5 |
| CDN | File library (Bootstrap) diambil dari internet, bukan diunduh ke folder | S1 |
| BASE_PATH | Prefix URL project dihitung otomatis agar app portabel | S3 |
| `__DIR__` | Path absolut folder file yang sedang berjalan (dihitung, bukan diketik) | S3, S4 |
| include / require | Sisipkan file; bedanya level error saat file hilang | S4 |
| Partials / Layout | Potongan tampilan yang dipakai ulang + kerangka halaman | S4 |
| Enkapsulasi | Data dibungkus private, akses lewat getter/setter | S4, S9 |
| Null coalescing `??` | "Pakai nilai kalau ada, kalau tidak pakai default" | S4 |
| Method chaining / Spread `...` | Memasukkan isi array sebagai argumen fungsi | S5 |
| PRG (Post/Redirect/Get) | Setelah POST, redirect supaya tidak double-submit | S5 |
| PDO | API PHP untuk akses database (driver cepat, mendukung prepared statement) | S7 |
| DSN | String data source name untuk koneksi (host, dbname, charset) | S7 |
| Prepared statement | Template SQL + parameter terpisah → anti SQL injection | S7 |
| Placeholder `:nama` | Penanda nilai dalam template SQL yang diisi saat execute | S7 |
| JOIN | Menggabungkan kolom dari 2+ tabel lewat relasi (mis. prodi_id) | S7 |
| Normalisasi | Memecah data ke tabel agar tidak duplikat (prodi sebagai referensi) | S7 |
| Singleton | Pola memastikan hanya 1 instance (di sini: 1 koneksi PDO) | S8 |
| Transaction / commit / rollback | Rangkaian SQL satu kesatuan; semuanya atau tidak sama sekali | S8 |
| Repository | Lapisan yang menampung semua SQL + mapping row→objek | S8 |
| CRUD | Create / Read / Update / Delete | S8 |
| Git / commit / add | Version control; snapshot berkode / mendaftarkan perubahan | S11-12 |
| Staging area | Ruang tunggu sebelum commit (git add) | S11-12 |
| Branch / merge | Jalur kerja terpisah / penggabungan | S11-12 |
| Service Layer | Lapisan business logic (validasi + rule) antara controller & repository | S13 |
| Validator rules | Aturan deklaratif `required\|digits\|min:7\|max:15` | S13 |
| Old input | Isian form dipertahankan saat validasi gagal (Session::withOldInput) | S13 |
| password_hash / verify | Hash bcrypt untuk menyimpan & memeriksa password | S14 |
| session_regenerate_id | Ganti ID sesi setelah login (anti session fixation) | S14 |
| CSRF token | Token acak per sesi di form, diverifikasi saat POST (anti Cross-Site Request Forgery) | S14 |
| Logging | Catat INFO/WARN/ERROR ke storage/logs (jejak admin, tersembunyi dari user) | S14 |
| 419 | Kode HTTP untuk token CSRF tidak valid | S14 |
| API | Antarmuka antar-program; data (JSON) dipakai mesin, bukan tampilan | S15 |
| JSON | Format data teks `{"kunci": "nilai"}`; `json_encode`/`json_decode` | S15 |
| Endpoint | URL API + method, mis. `POST /api/mahasiswa` | S15 |
| php://input | Membaca body mentah request (untuk JSON POST) | S15 |
| Content-Type | Header yang memberi tahu format body (`application/json`) | S15 |
| REST | Gaya API: method = aksi (GET baca, POST buat, PUT ubah, DELETE hapus) | S15/16 |
| 201 / 419 | Created (sukses dibuat) / Page Expired (token CSRF salah) | S15 / S14 |
| 200 / 302 / 404 / 405 | OK / redirect / tidak ditemukan / method tidak diizinkan | S5, S15 |
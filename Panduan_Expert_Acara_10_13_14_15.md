# Panduan Belajar Profesional — Acara 10, 13, 14 & 15
### Dari Pemula (mekanik) sampai Expert (arsitektur & makna)

> Dokumen ini dikhususkan untuk 4 acara puncak: **Acara 10** (DI + validasi terpusat + Base abstract),
> **Acara 13** (Service Layer + Validator deklaratif), **Acara 14** (Keamanan & Logging),
> **Acara 15** (API & JSON). Setiap bagian disusun urut: **masalah → alur → logika → maksud → pemula→expert → latihan**.
> Semua referensi file relatif ke `C:\laragon\www\webserver\` dan diambil dari kode asli.

---

# PETA BESAR — kenapa justru 4 acara ini?

Empat acara ini adalah satu lintasan bertema sama: **membuat controller hampir tidak berisi logika** dan
menempatkan setiap tanggung jawab di lapisan miliknya. Ini yang membedakan "kode jalan" dari "kode profesional".

| Acara | Pertanyaan yang dijawab | Jawaban singkat |
|---|---|---|
| **10** | Bagaimana controller didirikan rapi & validasi tidak tersebar? | DI container + `BaseController`/`BaseModel` abstract + `validate()` di model |
| **13** | Di mana business logic duduk supaya controller tipis? | **Service Layer** + aturan deklaratif `required\|digits\|min:7` |
| **14** | Bagaimana app aman & tertelusuri? | `password_hash`/`verify`, CSRF, `session_regenerate_id`, Logger |
| **15** | Bagaimana data dipakai program lain? | **API** JSON + status code yang benar |

Arsitektur kumulatif di akhir 4 acara:

```
Request
  │
  ▼
public/index.php  ──► (A15) kalau URI diawali "api"  ──► API branch (ApiResponse + ApiController)
  │                      └─ 200/201/400/404/405 JSON
  │  web(normal)
  │
  ▼
AuthMiddleware (A10)  ──► session_start + proteksi prefix (dashboard, mahasiswa, prodi, matakuliah)
  │
  ▼
Router (A10 DI container / A13+ manual)  ──► instantiate Controller (suntik dependensi)
  │
  ▼
Controller  ──► (A14) onlyPost + verifyCsrf  ──► Service → Validator → Repository → PDO/MySQL
  │                                                │
  └──────── view() / redirect() + flash (A13) ◄────┘
  │                      └─ (A14) Logger merekam INFO/WARN/ERROR sepajang jalan
  ▼
HTML / redirect
```

---

# BAGIAN 1 — ACARA 10: DI Container, Validasi Terpusat, Abstract Base

## 1. Masalah yang dibereskan (maksud)

Di Acara 8–9 muncul penyakit khas kode yang mulai besar:

1. **Controller "menciptakan" sendiri** repo-nya (`new MahasiswaRepository(...)` di dalam method) → dependensi tersebar, satu koneksi tidak dijamin.
2. **Validasi tersebar**: sebagian di controller, sebagian di setter objek, sebagian di SQL `INSERT` yang gagal. Kalau aturan berubah, kita harus mengejar di banyak tempat.
3. **Duplikasi helper**: tiap controller menyalin `view()`/`redirect()`; tiap model menyalin kode `prepare()+execute()`.
4. **Tidak ada "kritik" kompiler**: kelas yang boleh dipakai orang biasa (`new MahasiswaRepository()` tanpa DB) tetap bisa dibikin.

**Maksud Acara 10:** (a) pindahkan validasi ke **satu sumber** — method `validate()` model → repository menolak sebelum SQL; (b) controller **diberi** dependensi dari luar lewat konstruktor (DI), router yang merakitnya (mini container); (c) kunci pola dengan kelas `abstract`; (d) semua helper DB satu tempat di `BaseModel`.

## 2. Alur 1 request → `POST /mahasiswa` (Acara 10)

```
Browser (form create)
  │  POST /webserver/Acara10/.../public/mahasiswa/store
  ▼
[1] public/index.php
      • session_start()
      • normalisasi URI (buang BASE_PATH → "mahasiswa/store")
      • cek prefix: "mahasiswa" ≈ "mahasiswa/store" → AuthMiddleware::requireLogin()
            └─ belum login? → flash_error + redirect /login + exit
      • $routes = routes/web.php
      • new Router($routes)->dispatch("mahasiswa/store", "POST")
  ▼
[2] app/Core/Router.php
      • 'mahasiswa/store' → ['handler'=>'MahasiswaController@store','method'=>'POST']
      • method cocok (POST) → lanjut; GET ke route ini → 405 + Allow header
      • instantiateController('MahasiswaController'):
            └─ ReflectionClass → konstruktor minta (MahasiswaRepository, ProdiRepository)
            └─ make() → 'PDO' = Database::getInstance()->getConnection()
                         'Database' = Database::getInstance()
                         "…Repository" = new $x($this->pdo())
            └─ newInstanceArgs([$repo, $prodiRepo])   ← DI: dua dependensi disuntik!
      • $controller->store()
  ▼
[3] MahasiswaController::__construct → AuthMiddleware::requireLogin($basePath, false); tampung repo
[4] MahasiswaController::store()
      • $data = baca+trim $_POST
      • try  $this->repo->create($data)  → redirect('/mahasiswa', flash_success)
      • catch InvalidArgumentException → redirect('/mahasiswa/create', null, $e->getMessage())
      • catch PDOException             → redirect(..., pesanError($e))
  ▼
[5] MahasiswaRepository::create()
      • $errors = (new MahasiswaModel($pdo))->validate($data)   ← SATU-satunya sumber rule
      • $errors !== []  → throw InvalidArgumentException(errors)
      • INSERT (prepared) → return lastInsertId()
  ▼
[6] Response: 302 ke /mahasiswa + flash; atau 302 kembali ke form + flash_error
```

## 3. Logika kunci

### 3.1 Router sebagai mini DI container — `app/Core/Router.php`

```php
$reflection = new ReflectionClass($name);
$constructor = $reflection->getConstructor();
$dependencies = [];
foreach ($constructor->getParameters() as $param) {
    $type = $param->getType();
    if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
        $dependencies[] = $this->make($type->getName());   // resolve tiap tipe
    }
}
return $reflection->newInstanceArgs($dependencies);        // bangun controller + suntik

// make(): ibu jari resolusi
'Database'     => Database::getInstance(),
'PDO'          => Database::getInstance()->getConnection(),
str_ends_with($name,'Repository') => new $x($this->pdo()),
// selain itu → RuntimeException("kelas tidak dikenal")
```

**Logika & maksud:**
- `ReflectionNamedType` → kita mengambil **tipe** parameter konstruktor (mis. `MahasiswaRepository`) tanpa harus menebak. Inilah "autowiring".
- `make()` = satu pintu resolusi. Mau mengubah cara membuat sesuatu (mis. pakai mock), cukup ubah satu tempat.
- `newInstanceArgs(...)` → sekaligus instansiasi + suntik parameter. *Pemula:* "router yang memanggil `new`, bukan controller." *Expert:* ini meniru *Service Container* Laravel (Reflection-based autowiring).

### 3.2 Controller yang murni "menerima" — `app/Controllers/MahasiswaController.php`

```php
class MahasiswaController extends BaseController
{
    public function __construct(
        private MahasiswaRepository $repo,     // constructor promotion (PHP 8)
        private ProdiRepository $prodiRepo
    ) {
        AuthMiddleware::requireLogin($this->basePath, false);
    }

    public function store(): void
    {
        $data = $this->dataDariForm();
        try {
            $this->repo->create($data);
            $this->redirect('/mahasiswa', 'Data mahasiswa berhasil ditambahkan.');
        } catch (InvalidArgumentException $e) {
            $this->redirect('/mahasiswa/create', null, $e->getMessage());
        } catch (PDOException $e) {
            $this->redirect('/mahasiswa/create', null, 'Gagal menyimpan: ' . $this->pesanError($e));
        }
    }
}
```

**Maksud:**
- Constructor promotion `private MahasiswaRepository $repo` = 1 baris untuk (deklarasi properti + isi `$this->repo`). Tombol pola-ulang 3 properti jadi rapi.
- Controller **tidak pernah `new`** -> DHANYA menerima. Ketergantungan kasat mata ada di signature → mudah dibaca & diuji.
- Proteksi login di konstruktor = semua method otomatis aman (tidak tergantung lupa menaruh middleware per method).
- `catch` dua jenis error dibedakan: **InvalidArgumentException** = data jelek (kesalahan user) → flash_error; **PDOException** = masalah DB (kesalahan sistem) → flash_error yang *manusiawi* lewat `pesanError()`.

### 3.3 BaseController abstract — satu helper untuk semua — `app/Core/BaseController.php`

```php
abstract class BaseController
{
    protected string $basePath = BASE_PATH;

    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);           // array → variabel ($mahasiswa)
        $content = __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $to, ?string $success = null, ?string $error = null): void
    {
        if ($success !== null) $_SESSION['flash_success'] = $success;
        if ($error !== null) $_SESSION['flash_error'] = $error;
        header('Location: ' . $this->basePath . $to);
        exit;
    }

    protected function pesanError(PDOException $e): string
    {
        $m = $e->getMessage();
        if (str_contains($m, 'Duplicate entry')) return 'Data sudah ada (kode/NIM sudah dipakai).';
        if (str_contains($m, 'foreign key constraint fails')) return 'Data masih dipakai tabel lain…';
        if (str_contains($m, 'Data too long')) return 'Data terlalu panjang untuk kolom database.';
        return 'Kesalahan database: ' . $m;
    }
}
```

**Maksud:**
- `EXTR_SKIP` → tidak menimpa variabel yang sudah ada (keamanan ekstra).
- Flash sukses & error **dipisah** (`flash_success` / `flash_error`) → view bisa memberi warna simbol beda.
- `pesanError()` = yang disebut tenaga penting sekali: **jangan pernah menampilkan pesan DB mentah ke user**. Teks seperti `Duplicate entry 'x' for key 'PRIMARY'` bocorkan struktur DB. Map ke kalimat manusia.
- `abstract` → kelas ini **tidak boleh di-`new`**. Dipaksa jadi kerangka → tidak ada controller yang "lupa" punya `view`/`redirect`. Inilah peran *behavior guarantee*.

### 3.4 BaseModel abstract — semua akses DB lewat 5 method — `app/Core/BaseModel.php`

```php
abstract class BaseModel
{
    protected PDO $pdo;
    protected string $table = '';

    protected function db(string $sql, array $params = []): PDOStatement
    { $stmt = $this->pdo->prepare($sql); $stmt->execute($params); return $stmt; }

    protected function fetchAll(string $sql, array $params = []): array
    { return $this->db($sql, $params)->fetchAll(); }

    protected function fetchOne(string $sql, array $params = []): ?array
    { $row = $this->db($sql, $params)->fetch(); return $row === false ? null : $row; }

    protected function execute(string $sql, array $params = []): int
    { return $this->db($sql, $params)->rowCount(); }

    protected function lastInsertId(): int
    { return (int) $this->pdo->lastInsertId(); }

    public function validate(array $data): array { return []; }  // di-override model
}
```

**Maksud:** setiap model (Mahasiswa/Prodi/Matakuliah) mewarisi `fetchAll`/`fetchOne`/`execute` → **tidak ada lagi `prepare()+execute()` ditulis ulang**. `validate()` default mengembalikan `[]` (tidak ada error); model yang butuh aturan **memaksa** override (PHP `abstract` hanya di kelas, method dibiarkan virtual → lupa override = aman tapi tidak memvalidasi). Pola ini = "helper class" yang benar: satu definisi, banyak pemakai.

### 3.5 Model: validasi terpusat dalam array — `app/Models/MahasiswaModel.php`

```php
public function validate(array $data): array
{
    $errors = [];
    if (($data['nim'] ?? '') === '')                 $errors[] = 'NIM wajib diisi.';
    elseif (!ctype_digit((string)$data['nim']))      $errors[] = 'NIM harus berupa angka.';
    if (trim((string)($data['nama'] ?? '')) === '')  $errors[] = 'Nama wajib diisi.';
    if (($data['email'] ?? '') === '')               $errors[] = 'Email wajib diisi.';
    elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
    if ((int)($data['prodi_id'] ?? 0) <= 0)          $errors[] = 'Prodi wajib dipilih.';
    $angkatan = (int)($data['angkatan'] ?? 0);
    if ($angkatan <= 0)                              $errors[] = 'Angkatan wajib diisi.';
    elseif ($angkatan > (int)date('Y') + 1)          $errors[] = 'Angkatan tidak valid.';
    if (!in_array($data['status'] ?? 'aktif', $this->statuses(), true))
                                                     $errors[] = 'Status harus: ' . implode(', ', $this->statuses()) . '.';
    return $errors;
}
```

**Maksud (poin paling penting Acara 10):**
- Method mengembalikan **array semua error sekaligus** (bukan throw pertama) → satu submit dapat satu daftar lengkap → UX lebih baik.
- Dipanggil **di repository** tepat sebelum `INSERT`/`UPDATE` → *"data jelek tidak akan pernah menyentuh DB."* Ini pembatas terakhir dan saking belakang selalu ada (SUT).
- `?? ''` guard → dipanggil dengan data tidak lengkap pun tidak `undefined index` / warning.
- Dua lapisan masih berperan: setter objek (A9, di `new Mahasiswa`) menolak saat *dibangun*, `validate()` menolak saat *disimpan*. Fokus pola: repository = gerbang terakhir.

### 3.6 Repository — memanggil validate, baru SQL — `app/Repositories/MahasiswaRepository.php`

```php
public function create(array $data): int
{
    $errors = $this->model->validate($data);          // 1
    if ($errors !== []) {                             // 2
        throw new InvalidArgumentException(implode(' ', $errors));  // 3
    }
    // 4: INSERT ... ; return $this->lastInsertId();
}
```

Alurnya: **validasi → kalau jelek lempar exception → baru INSERT.** Controller menangkap `InvalidArgumentException` (data jelek) vs `PDOException` (sistem) — dua dunia, dua pesan.

## 4. Maksud-maksud (mengapa Acara 10 disusun begini)

1. **"Satu sumber kebenaran" untuk rule** — kalau besok NIM jadi 10 digit, cukup edit `MahasiswaModel::validate()`. Di A8–9 harus mengejar 3–4 tempat.
2. **"Memaksa ketertiban dengan tipe"** — `private MahasiswaRepository $repo` membuat pemakaian yang salah gagal saat konstruksi, bukan saat runtime diam-diam menghasilkan bug.
3. **`abstract` = kontrak** — tidak bisa instansiasi = mencegah pemakaian tidak konsisten; semua turunan pasti diisi lewat inherited helper.
4. **DI memisahkan "membangun" dari "menggunakan"** — controller tidak peduli repo butuh PDO apa. Kalau kita pindah DB, hanya `make()`/`Database` yang berubah.
5. **Pembatas terakhir (defense in depth)** — validasi controller/setter boleh lompat, tapi repository `create` selalu menecek.

## 5. Pemula → Expert (Acara 10)

| Level | Caramu melihat |
|---|---|
| **Pemula** | "Router memakai Reflection supaya `new MahasiswaController($repo, $prodiRepo)` bisa diisi otomatis. Model punya `validate()` yang mengembalikan daftar error. Kalau ada error, repository `throw`." |
| **Menengah** | "DI membuat dependensi terlihat di signature konstruktor; `make()` adalah satu titik konfigurasi pembuatan objek; validasi ada di lapisan paling dekat DB supaya mustahil data rusak masuk." |
| **Expert** | "Ini Service Container miniature: Reflection autowiring + type-hint = kontrak. Trade-off: Reflection lambat (bisa di-cache), dan 'auto' menyembunyikan grafik dependensi. Mengembangkan ini berarti menambah binding resolution, deeper graph (rekursif), dan `newInstanceArgs` untuk siklus hidup (singleton per request). Itu persis mesin di balik Laravel Container." |

## 6. Latihan cek pemahaman (Acara 10)

1. Apa perbedaan efek `throw InvalidArgumentException` vs `return ['errors'=>...]` pada Acara 10?
2. Kenapa properti di konstruktor perlu tipe `private MahasiswaRepository $repo`, bukan `MahasiswaRepository $repo` (hash ekstra)?
3. Kalau ada controller baru `FooController` yang minta `Database $db` di konstruktornya, apa yang akan dilakukan `make()`? (jawab berdasar `make()` di atas)
4. Dalam roda `pesanError`, mengapa tidak boleh `return $e->getMessage()` begitu saja?

---

# BAGIAN 2 — ACARA 13: Service Layer & Validator Deklaratif

## 1. Masalah yang dibereskan (maksud)

Acara 10 meninggalkan controller dengan 2 kekurangan:

1. **Controller masih berisi *business logic*** — di `store()`, validasi `InvalidArgumentException` + cek-duplikat etc. mencampur:
   - *cara memvalidasi* (urutan, aturan) dengan
   - *arah halaman* (redirect mana).
2. **Aturan validasi hardcode** sebagai `if/else` di model → menulis validator baru untuk field lain = menyalin blok, rawan tidak konsisten.

**Maksud Acara 13:** (a) kenalkan **Service Layer** — lapisan yang memegang semua business logic (validasi + cek unik + hasil terstruktur) sehingga controller tinggal membaca `['ok' => bool, 'errors' => [...]]`; (b) buat **Validator deklaratif** — tulis aturan sebagai *string* `"required|digits|min:7|max:15"`, satu parser yang sama untuk semua form; (c) sempurnakan pengalaman form dengan **old input** (isian tidak hilang saat salah).

## 2. Alur 1 request → `POST /mahasiswa` (Acara 13, kode asli)

```
Browser
  │  POST .../public/mahasiswa/store
  ▼
[1] public/index.php
      • session_start() (via Session::start() di index)
      • normalisasi URI → "mahasiswa/store"
      • prefix "mahasiswa" → AuthMiddleware::requireLogin($base_path)  → belum login? redirect + exit
      • $routes → new Router($routes)->dispatch("mahasiswa/store")
  ▼
[2] app/Core/Router.php (A13: tanpa DI, tanpa cek method)
      • pola "mahasiswa/store" cocok → 'MahasiswaController@store'
      • require controller → new MahasiswaController() → $controller->store()
  ▼
[3] MahasiswaController::__construct
      • AuthMiddleware::requireLogin($this->base_path, false)
      • $this->service = $service ?? new MahasiswaService(Database::getInstance())
  ▼
[4] MahasiswaController::store()                     ← controller = 6 baris, semua tentang ARAH
      $result = $this->service->create($_POST);       ← semua LOGIKA di Service
      if ($result['ok'])
          $this->redirect('/mahasiswa', 'Data mahasiswa berhasil ditambahkan.');
      $_SESSION['errors'] = $result['errors'];
      Session::withOldInput($_POST);                  ← isian lama disimpan
      $this->redirect('/mahasiswa/create', 'Periksa kembali isian form Anda.');
  ▼
[5] app/Services/MahasiswaService::create()
      $errors = $this->validator->validate($data, $this->rules());   ← aturan deklaratif
      if ($errors === [] && $this->repository->findByNim($nim))      ← business rule: unik
          $errors['nim'][] = 'NIM sudah terdaftar. Gunakan NIM lain.';
      if ($errors !== []) return ['ok' => false, 'errors' => $errors];
      $this->repository->create($data);
      return ['ok' => true, 'errors' => []];
  ▼
[6] app/Core/Validator::validate()
      foreach ($rules as $field => $ruleStr)
          foreach (explode('|', $ruleStr) as $rule) applyRule($field, $data[$field] ?? null, $rule);
        • rule "digits" → ctype_digit; "min:7" → strlen>=7; "in:aktif,nonaktif" → in_array ...
        • hasil: ['nim' => [msg,...], 'nama' => [...], ...]
  ▼
[7] MahasiswaRepository::create()  → INSERT (prepared) → boolean
  ▼
[8] Redirect 302 (+flash) atau 302 kembali ke form (+errors di $_SESSION, isian di $_SESSION['_old'])
```

## 3. Logika kunci

### 3.1 Controller tipis — `app/Controllers/MahasiswaController.php` (Acara 13)

```php
public function store(): void
{
    $result = $this->service->create($_POST);

    if ($result['ok']) {
        $this->redirect('/mahasiswa', 'Data mahasiswa berhasil ditambahkan.');
    }

    $_SESSION['errors'] = $result['errors'];
    Session::withOldInput($_POST);
    $this->redirect('/mahasiswa/create', 'Periksa kembali isian form Anda.');
}
```

**Maksud:**
- Controller hanya memiliki **dua keputusan arah**: sukses → daftar; gagal → kembali ke form + bawa error & isian lama. Semua *decision logistics* (kenapa gagal) pindah ke Service.
- `Session::withOldInput($_POST)` menyimpan isian ke `$_SESSION['_old']`; view memakai `Session::old('nama')` untuk mengisi nilai `value="..."` → **isian user tidak hilang** saat validasi gagal (anti-eksperimen).
- Semua method controller mengikuti 1 pola → belajar 1 method = tahu semua.

### 3.2 Service — jantung business logic — `app/Services/MahasiswaService.php`

```php
private function rules(): array
{
    return [
        'nim'      => 'required|digits|min:7|max:15',
        'nama'     => 'required|min:3|max:100',
        'prodi_id' => 'required|numeric',
        'status'   => 'required|in:aktif,nonaktif',
        'alamat'   => 'max:255',
    ];
}

public function create(array $data): array
{
    $errors = $this->validator->validate($data, $this->rules());
    if ($errors === [] && $this->repository->findByNim($data['nim'] ?? '')) {
        $errors['nim'][] = 'NIM sudah terdaftar. Gunakan NIM lain.';
    }
    if ($errors !== []) return ['ok' => false, 'errors' => $errors];
    $this->repository->create($data);
    return ['ok' => true, 'errors' => []];
}

public function update(int $id, array $data): array
{
    $errors = $this->validator->validate($data, $this->rules());
    $existing = $this->repository->findByNim($data['nim'] ?? '');
    if ($errors === [] && $existing !== null && $existing->getId() !== $id) {
        $errors['nim'][] = 'NIM sudah terdaftar untuk mahasiswa lain.';
    }
    ...
}
```

**Maksud:**
- Kontrak return selalu `['ok'=>bool, 'errors'=>array]` → pemanggil (controller) tidak perlu tahu detail.
- Operasi **unik NIM** beda untuk create vs update: saat update, NIM milik dirinya sendiri (`getId() === $id`) tidak dianggap duplikat — logic yang dulu mudah salah, sekarang jelas.
- `delete()` cek `find()===null` dulu → kalau ada, hapus; kalau tidak, kembalikan `ok=false` (bukan SQL error).
- Service menyimpan repository + validator → "satpam yang mengkoordinir" data.

### 3.3 Validator deklaratif — `app/Core/Validator.php`

```php
foreach ($rules as $field => $ruleStr) {
    foreach (explode('|', $ruleStr) as $rule) {
        $this->applyRule($field, $data[$field] ?? null, $rule);
    }
}
// dalam applyRule():
if (str_contains($rule, ':')) {
    [$rule, $raw] = explode(':', $rule, 2);
    $params = explode(',', $raw);            // 'min:7' → ['7']; 'range:1,10' → ['1','10']
}
switch ($rule) {
    case 'required': if (trim((string)$value) === '') $this->errors[$field][] = label.' wajib diisi.'; break;
    case 'min':      if (mb_strlen((string)$value) < (int)$params[0]) ...   break;
    case 'digits':   if (!ctype_digit((string)$value)) ...                  break;
    case 'numeric':  if (!is_numeric($value)) ...                           break;
    case 'range':    if (!is_numeric($value) || $value < $params[0] || $value > $params[1]) ...; break;
    case 'in':       if (!in_array((string)$value, $params, true)) ...      break;
}
```

**Maksud:**
- Aturan = **data, bukan kode**. Menambah form baru = cukup menulis string aturan; parser `switch` tidak berubah. Ini alasan kenapa framework besar memakai aturan deklaratif (Laravel validation, HTML `required`, dll).
- `$data[$field] ?? null` → field dari form yang tidak ada tidak memicu `warning`.
- `label()` memetakan nama field → nama Indonesia untuk pesan.

## 4. Maksud-maksud (mengapa Acara 13 disusun begini)

1. **Pemisahan dua "jenis" kode**: *keputusan bisnis* (Service) vs *koordinasi halaman* (Controller). Bila dua-duanya di controller, method tumbuh tak terkendali; bila dua-duanya di kurikulum berikutnya campur, sulit diuji.
2. **Kontrak `['ok', 'errors']`** — controller dan Service berkomunikasi lewat "API kecil" → keduanya bisa diubah internal tanpa saling rusak.
3. **Validator sekali tulis, dipakai semua** — `required|digits`, `in:aktif,nonaktif` dipakai form mahasiswa, form prodi, form matakuliah. Konsistensi pesan error terjaga.
4. **Old input** — UX kelas profesional: user yang salah isi tidak mengetik ulang dari awal.

## 5. Pemula → Expert (Acara 13)

| Level | Caramu melihat |
|---|---|
| **Pemula** | "Controller menyuruh Service: 'tolong buat mahasiswa'. Service memeriksa aturan `nim|digits|...`, mengecek NIM unik, then menyimpan atau mengembalikan error. Controller hanya mengarahkan halaman." |
| **Menengah** | "Service layer = tempat satu-satunya business logic; controller membaca kontrak hasil; validator deklaratif menekan duplikasi aturan." |
| **Expert** | "Ini pola *Application Service* / *Use Case*: objek stateless yang mengeksekusi satu use case. Trade-off: Service bisa bias 'satu entitas' → di sistem besar pecah jadi *transactional* vs *query* service; validasi cek unik tetap rawan race → di DB simpan `UNIQUE` dan tangani `Duplicate entry` sebagai lapisan terakhir (mengapa A14 menambahkan ExceptionHandler & pengolahan PDOException). DRY vs 'controller tujuan' adalah keputusan arsitektur, bukan dogma." |

## 6. Latihan cek pemahaman (Acara 13)

1. Kenapa `update()` perlu cek `$existing->getId() !== $id`? Ambil contoh angka.
2. Apa isi `$result` yang diterima controller saat NIM duplikat? Tulis JSON-nya.
3. Bedanya menaruh aturan `'nim'=>'required|digits|min:7|max:15'` dibanding `if` di controller?
4. `Session::withOldInput` menyimpan ke mana? Apa fungsi view `Session::old('nama')`?

---

# BAGIAN 3 — ACARA 14: Keamanan & Logging

## 1. Masalah yang dibereskan (maksud)

Acara 6-13 login masih berbasis `const USERNAME/PASSWORD` di kode, dan tidak ada lapisan keamanan. Bahaya nyata:

1. **Password plaintext / hardcode di kode** → sekali source code bocor, kredensial terbaca.
2. **Session fixation** → penyerang memaka padamu session ID lama.
3. **CSRF (Cross-Site Request Forgery)** → situs jahat menyuruh browser korban mengirim `POST` atas nama aplikasi kita (cookie ikut dikirim).
4. **Error detail tampil ke layar** → bocorkan struktur DB.
5. **Tidak ada jejak** → sulit tahu siapa coba login / gagal bagaimana.

**Maksud Acara 14:** (a) kredensial pindah ke **tabel `users`** dengan password **bcrypt hash** + `password_verify`; (b) **regenerate session ID** saat login; (c) **token CSRF** di tiap form POST + verifikasi; (d) `onlyPost` (tolak GET untuk operasi mutasi); (e) **ExceptionHandler** global; (f) **Logger** file audit.

## 2. Alur login (Acara 14, kode asli)

```
GET /login → view form (contains Csrf::field() = <input _token="...">)
  │
  ▼
POST /login (body: username, password, _token)
  │
  ▼
[1] public/index.php → session_start; ExceptionHandler::register(); normalisasi URI
                       (login tidak di-protected → tidak kena redirect)
[2] Router → AuthController@login
[3] AuthController::login()
      $this->onlyPost('/login');        ← kalau method ≠ POST → 405 + redirect + exit
      $this->verifyCsrf();              ← Csrf::verify(): hash_equals(_token, $_SESSION['_csrf'])
                                          └─ tidak cocok → http_response_code(419) + exit
      $user = $this->users->findByUsername($username);     ← prepared SELECT users
      if ($user !== null && password_verify($password, $user['password'])) {
          session_regenerate_id(true);                     ← ganti ID sesi!
          $_SESSION['user'] = $user['username'];
          $_SESSION['user_id'] = (int)$user['id'];
          $_SESSION['user_name'] = $user['nama'];
          $logger->info('LOGIN BERHASIL: '.$username.' dari IP '.REMOTE_ADDR);
          $this->setFlash('Login berhasil...'); $this->redirect('/dashboard');
      }
      $logger->warning('LOGIN GAGAL: username "'.$username.'"');
      $this->setFlash('Username atau password salah.');
      $this->view('auth/login.php');    ← tetap di halaman login + flash_error
```

POST lain (mis. `mahasiswa/store`) → `onlyPost()` + `verifyCsrf()` di awal lalu Service → Repository, seperti Acara 13, tetapi dengan penjaga CSRF di depan.

## 3. Logika kunci

### 3.1 Hash password & verifikasi — `app/Controllers/AuthController.php` + `users`

```php
$user = $this->users->findByUsername($username);
if ($user !== null && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    ...
}
```

- `database/si_akademik.sql` seed: username `admin`, password hash
  `$2y$10$jZPmrz463azopmrCmZldYu1OdYZ3gq2jLqO.S/nVt/HhmDOePwrNe` = bcrypt dari `admin123`.
- `password_hash($password, PASSWORD_DEFAULT)` menghasilkan string acak termasuk salt; `password_verify()` memeriksa **tanpa tahu plaintext**. Server tidak pernah menyimpan `admin123` → kebocoran DB ≠ kebocoran password.
- **Maksud:** jangan pernah simpan password mentah; jangan pernah `sha1($pass)` tanpa salt; bcrypt/argon2 punya built-in salt + cost faktor (lambat dengan sengaja → sulit di-brute-force).

### 3.2 session_regenerate_id(true) — `AuthController::login()` baris 43

- Saat user `admin123` baru login, PHP membuang SID lama & membuat SID baru. Kenapa penting? Serangan **session fixation**: penyerang *memberikan* korban SID tertentu ("login pakai link ini"), korban login dengannya, lalu penyerang ikut pakai SID itu = ikut login sebagai korban. Dengan regenerate: SID yang "diberikan" penyerang tidak berlaku lagi setelah login.

### 3.3 CSRF — `app/Core/Csrf.php`

```php
public static function token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));   // acak kriptografis
    }
    return $_SESSION['_csrf'];
}
public static function field(): string
{
    return '<input type="hidden" name="_token" value="' . self::token() . '">';
}
public static function verify(): void
{
    $sent = $_POST['_token'] ?? '';
    if (!is_string($sent) || !hash_equals(self::token(), $sent)) {
        http_response_code(419);
        exit('Token CSRF tidak valid. Silakan muat ulang halaman.');
    }
}
```

- `Cybersecurity` cara: form di-render dengan `Csrf::field()`. Saat POST, `verify()` membandingkan `$_POST['_token']` dengan token di session via **`hash_equals`** (perbandingan konstan-waktu → tidak bisa timing-attack).
- Situs jahat tidak tahu token kita → `POST` palsu selalu gagal. (Catatan lab: di lingkungan ini intent adalah 419, bisa tampil sebagai 500 pada konfigurasi CGI tertentu — konsepnya yang penting: request ditolak.)
- Dipanggil lewat `$this->verifyCsrf()` pada base Controller (A14).

### 3.4 onlyPost — `app/Core/Controller.php` (baris 29-35)

```php
protected function onlyPost(string $fallback = '/'): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        http_response_code(405);
        $this->redirect($fallback, 'Metode HTTP tidak diizinkan.');
    }
}
```
- Mengubah data (insert/update/delete/login) HANYA via POST. Mengakses dengan GET → 405 + redirect. Paksa browser hanya lewat form/PRG.

### 3.5 ExceptionHandler — `app/Core/ExceptionHandler.php` + `AppException`

- `ExceptionHandler::register()` → `set_exception_handler` global. Semua `Throwable` masuk ke satu pengolahan:
  - **AppException** (data jelek / NotFound) → tampilkan pesan yang aman.
  - **PDOException / lainnya** → log detail ke file, tampilkan pesan generik ke user.
- `app/Core/AppException.php` → exception khusus aplikasi (mis. "Mahasiswa tidak ditemukan") dengan status.

### 3.6 Logger — `app/Core/Logger.php`

```php
private function write(string $level, string $message): void
{
    if (!is_dir($this->dir)) @mkdir($this->dir, 0777, true);
    $line = sprintf("[%s] %-5s %s%s", date('Y-m-d H:i:s'), $level, $message, PHP_EOL);
    file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
}
public function info(string $m): void    { $this->write('INFO', $m); }
public function warning(string $m): void { $this->write('WARN', $m); }
public function error(string $m): void   { $this->write('ERROR', $m); }
```

- Menulis ke `storage/logs/app.log` (Acara 14) — hasil demo terlihat `[2026-10-08] INFO LOGIN BERHASIL: admin dari IP 127.0.0.1`.
- **Maksud:** user dapat pesan generik; admin dapat jejak lengkap (IP, username yang gagal). Detail exception & request tetap ada untuk investigasi — ini prinsip *"jangan bocorkan detail ke user, tapi catat untuk yang berwenang"*.

## 4. Maksud-maksud (mengapa Acara 14 disusun begini)

1. **Keamanan berlapis (defense in depth)**: hash password (mencegah bocor) + regenerasi SID (mencegah penyusupan sesi) + CSRF (mencegah pemalsuan permintaan) + onlyPost (mencegah GET mutasi) + 419.
2. **Prinsip jangan percaya input**: `_token` dari form diperiksa; method dipaksa POST; password tidak pernah dibandingkan plaintext.
3. **Observability**: Logger memberi kata data untuk "siapa, kapan, dari mana" — tak tergantikan saat debugging & audit keamanan.
4. **User-friendly**: error DB tidak lagi bocor; pesan generik "Username atau password salah." (tanpa menginfokan "username tidak terdaftar" — supaya penyerang tidak bisa enumerasi).

## 5. Pemula → Expert (Acara 14)

| Level | Caramu melihat |
|---|---|
| **Pemula** | "Login dibandingkan pakai `password_verify`, sesi diregenerate, tiap POST dicek `_token`, activity dicatat ke `app.log` dengan level INFO/WARN/ERROR." |
| **Menengah** | "Setiap ancaman punya satu jawaban: password plaintext→hash+verify; fixation→regenerate_id; CSRF→token per-sesi; error bocor→ExceptionHandler+pesan generik; tanpa jejak→Logger." |
| **Expert** | "Amankan lebih dari rutinitas: rate limiting login (anti brute-force), timeout sesi, `SameSite`/`Secure` cookie flag, header CSP, hash cost sesuai hardware, log sensitif (jangan tercatat password!). Trade-off menjaga UX vs keamanan. Yang menjadi ciri mahir: mengurutkan lapisan — verifikasi CSRF SEBELUM membuka resource, jangan pernah log password, dan pisahkan exception 'keputusan bisnis' (AppException, bisa ditampilkan) vs 'kesalahan sistem' (PDOException, disembunyikan+di-log)." |

## 6. Latihan cek pemahaman (Acara 14)

1. Jelaskan 3 lapisan keamanan yang dilewati `POST /auth` di Acara 14, urut dari luar ke dalam.
2. Apa yang terjadi kalau `_token` di form tidak sama dengan `$_SESSION['_csrf']`? (status code & perilaku)
3. Kenapa `session_regenerate_id(true)` dilakukan SETELAH password benar, bukan sebelum?
4. Apa perbedaan perlakuan `AppException` vs `PDOException` oleh ExceptionHandler?

---

# BAGIAN 4 — ACARA 15: API, JSON & Status Code

## 1. Masalah yang dibereskan (maksud)

Sampai Acara 14, aplikasi melayani **manusia** (HTML). Sekarang ingin memberi data ke **program lain**:
mobile app, dashboard, atau website lain. Tuntutan berbeda: respons harus **terstruktur (JSON)**, **status code benar (200/201/400/404/405)**, dan bisa dipanggil dari luar browser (Postman, curl), lewat **body JSON** (bukan hanya `$_POST` dari form).

**Maksud Acara 15:** properti API — jalur sendiri (`routes/api.php`) + `ApiController` terpisah dari web, **kontrak respons** seragam `{success, message, data/errors}`, baca input JSON via `php://input`, dan **status code sebagai bahasa mesin** tiap kondisi.

## 2. Alur 1 request → API (Acara 15, kode asli)

```
Client (curl / Postman / app lain)
  │  POST http://localhost/.../public/api/mahasiswa
  │  Content-Type: application/json
  │  Body: {"nim":"7099999","nama":"Budi","prodi_id":1,"status":"aktif"}
  ▼
[1] public/index.php (baris awal)
      • jika $uri === 'api' ATAU str_starts_with($uri,'api/'):
      • require ApiResponse + routes/api.php + REPOS (via controller)
      • foreach routes as $route => $handler:
            [$routeMethod,$routePath] = explode(' ', $route, 2)
            pattern: {nim} → (?P<nim>[a-zA-Z0-9_]+)
      • path "api/mahasiswa" cocok:
            - method route "POST" === REQUEST_METHOD "POST" → panggil ApiController@mahasiswaStore; exit
            - kalau semua route cocok path tapi method salah → ApiResponse::error(...,405)
            - kalau path tak ada → ApiResponse::error('Endpoint API tidak ditemukan.', 404)
      • (web TIDAK dieksekusi — return dulu; jadi API tak kena Session/AuthMiddleware web)
  ▼
[2] ApiController::mahasiswaStore() (baris 60-122)
      $raw   = file_get_contents('php://input');     ← body JSON mentah
      $input = json_decode($raw ?: '', true);
      if (!is_array($input)) → ApiResponse::error('Body JSON tidak valid.', 400)
      // validasi per field:
      nim: kosong/tdk-digit → 400; terdaftar → 400 'NIM sudah terdaftar.'
      nama: kosong → 400
      prodi_id: <=0 atau prodi tak ada → 400
      status: selain aktif/nonaktif → 400
      if (!empty($errors)) → ApiResponse::error('Data tidak valid.', 400, $errors)
      try { $created = $this->mahasiswa->create([...]); }
      catch (Throwable $e) → 500
      if (!$created) → 500
      → ApiResponse::success($this->mahasiswa->findByNim($nim), 'Data mahasiswa berhasil ditambahkan', 201)
  ▼
[3] ApiResponse::success(...) → json_encode + header + http_response_code + exit
```

## 3. Logika kunci

### 3.1 Percabangan API di front controller — `public/index.php`

```php
if ($uri === 'api' || str_starts_with($uri, 'api/')) {
    require_once __DIR__ . '/../app/Core/ApiResponse.php';
    $apiRoutes = require __DIR__ . '/../routes/api.php';
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $pathFound = false;
    foreach ($apiRoutes as $route => $handler) {
        [$routeMethod, $routePath] = explode(' ', $route, 2);
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[a-zA-Z0-9_]+)', trim($routePath, '/')); 
        if (!preg_match('#^' . $pattern . '$#', $uri, $m)) continue;
        $pathFound = true;
        if ($routeMethod !== $requestMethod) continue;   // path cocok, method belum
        // ... instantiate controller, panggil method, exit
    }
    if ($pathFound) ApiResponse::error('Metode ' . $requestMethod . ' tidak didukung untuk endpoint ini.', 405);
    ApiResponse::error('Endpoint API tidak ditemukan.', 404);
}
```

**Maksud:**
- API diperiksa **sebelum** middleware web → memanggil `POST /api` tidak di-redirect login (API bisa dipakai tanpa sesi).
- Router `routes/api.php` dengan kunci `"METHOD path"` → kita bisa punya `GET api/mahasiswa` dan `POST api/mahasiswa` berbeda handler.
- **Status code yang tepat**: 404 (alamat salah) vs 405 (alamat benar, method salah) — bahasa standar antar mesin.

### 3.2 Daftar endpoint — `routes/api.php`

```php
return [
    'GET api'                  => 'ApiController@info',
    'GET api/mahasiswa'        => 'ApiController@mahasiswa',
    'GET api/mahasiswa/{nim}'  => 'ApiController@mahasiswaDetail',
    'POST api/mahasiswa'       => 'ApiController@mahasiswaStore',
    'GET api/prodi'            => 'ApiController@prodi',
    'GET api/matakuliah'       => 'ApiController@matakuliah',
];
```

### 3.3 Kontrak respons — `app/Core/ApiResponse.php`

```php
public static function json(mixed $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
public static function success(mixed $data = [], ?string $message = null, int $status = 200): void
{ self::json(['success' => true,  'message' => $message ?? 'OK', 'data' => $data], $status); }
public static function error(string $message, int $status = 400, array $errors = []): void
{ self::json(['success' => false, 'message' => $message, 'errors' => $errors], $status); }
```

**Maksud:**
- Satu bentuk untuk semua endpoint: sukses = `{success:true,message,data}`; gagal = `{success:false,message,errors}`. Pemakai (frontend developer) bisa mengharapkan struktur tetap.
- `JSON_UNESCAPED_UNICODE` → karakter Indonesia tampil normal; `JSON_PRETTY_PRINT` → mudah dibaca saat debugging; `exit` → tidak ada HTML menempel di respons JSON.
- `json_encode` ada di **satu titik** (ApiResponse) → tidak ada endpoint yang lupa set Content-Type.

### 3.4 Baca & tulis JSON — `app/Controllers/ApiController.php`

```php
// BACA (dengan parameter opsional)
public function mahasiswa(): void
{
    $id = $_GET['id'] ?? null;
    if ($id !== null && $id !== '') {
        if (!ctype_digit((string)$id)) { ApiResponse::error('Parameter id tidak valid.', 400); return; }
        $mhs = $this->mahasiswa->find((int)$id);
        if ($mhs === null) { ApiResponse::error('Mahasiswa dengan id ' . $id . ' tidak ditemukan.', 404); }
        ApiResponse::success($mhs);
    }
    ApiResponse::success($this->mahasiswa->all());   // tanpa ?id → semua
}

// TULIS (body JSON)
$raw = file_get_contents('php://input');        // ← kunci: body mentah, bukan $_POST
$input = json_decode($raw ?: '', true);
if (!is_array($input)) { ApiResponse::error('Body JSON tidak valid.', 400); return; }
```

**Maksud:**
- `php://input` membaca **stream body request** → untuk POST JSON, data TIDAK ada di `$_POST` (form-urlencoded). Inilah perbedaan wujud interaksi aplikasi vs API.
- Validasi `?id` → id bukan angka = 400 (input salah) vs `find()` null = 404 (tidak ketemu): dua kondisi berbeda, dua kode berbeda.
- `try/catch Throwable` penulisan → kegagalan DB = 500 (bukan detail ke client).

## 4. Maksud-maksud (mengapa Acara 15 disusun begini)

1. **Pisahkan jalur API & web**: API punya kebutuhan hidup berbeda (response JSON, tanpa sesi). Satu `routes/api.php` + satu `public/index.php` branch → tidak mengotori router web.
2. **Jadikan status code "bahasa"**: 200 dibaca, 201 dibuat, 400 input salah, 404 tidak ada, 405 method salah, 500 gagal sistem. Program lain bisa bereaksi tanpa parsing teks.
3. **Seragam = mudah dikonsumsi**: pertimbangan terbesar API bukan kode, tapi pengalaman developer pemakai API (`developer experience`). Kontrak tetap = nilai jual.
4. **Melanjutkan pola**: controller API tetap tipis (=> langsung Repository), validasi tetap per-field seperti Service, error tetap tidak bocor detail (500 generik).

## 5. Pemula → Expert (Acara 15)

| Level | Caramu melihat |
|---|---|
| **Pemula** | "`POST /api/mahasiswa` membaca body JSON di `php://input`, divalidasi, lalu menyimpan. Respons `{success, message, data}` dengan status 201. Tanpa body JSON → 400." |
| **Menengah** | "API adalah produk: kontrak tetap, status code bermakna, Content-Type JSON konsisten, endpoint terpisah dari web dan tidak kena login sesi." |
| **Expert** | "Ini REST ramah-RESTful; untuk produksi tambah: autentikasi (token/Bearer, bukan sesi), rate limiting, versioning (`/api/v1`), pagination & HATEOAS opsional, validasi yang konsisten dengan web (layanan yang sama!), idempotency utk retry, serta OpenAPI spec. Keahlian = konsistensi, HTTP semantics benar (PUT vs PATCH, 409 unik), dan menyimpan 'kontrak' sebagai dokumen yang dapat dieksekusi (swagger). Detail: `json_decode` body → depth guard; selalu tetapkan `exit` setelah respons agar tidak ada teks lanjutan." |

## 6. Latihan cek pemahaman (Acara 15)

1. `POST /api/mahasiswa` dengan body `"hallo"` → status apa? Kenapa bukan 400? (petunjuk: `json_decode` gagal → `$input` = null → `!is_array`).
2. `GET /api/mahasiswa/999999` (nim tidak ada) → status/body apa?
3. `POST /api/prodi` → apa responsnya dan statusnya? (petunjuk: daftar route)
4. Apa beda `$_POST` dan `file_get_contents('php://input')` saat body JSON?

---

# BAGIAN 5 — JEMBATAN PEMULA → EXPERT (Integrasi 4 Acara)

## 1. Layering utuh setelah 4 acara

```
┌──────────────────────────────────────────────────────────────┐
│  Lapisan                │ Tanggung jawab            │ Asal   │
├─────────────────────────┼───────────────────────────┼────────┤
│  Front controller       │ Normalisasi URI, pilih    │ A10-A15│
│  (public/index.php)     │ jalur web/API, auth awal  │        │
│  Middleware (Auth)      │ Siapa boleh masuk         │ A10    │
│  Router + DI container  │ Rakit controller, suntik  │ A10    │
│                         │ dependensi                │        │
│  Controller             │ onlyPost+verifyCsrf (A14) │ A10/A13│
│                         │ arah halaman (redirect)   │        │
│  Service                │ BUSINESS LOGIC + validasi │ A13    │
│  Validator (deklaratif) │ Parse aturan string       │ A13    │
│  Repository             │ SQL; mapping row→objek    │ A10/A13│
│  Model                  │ validate() rule kunci     │ A10    │
│  Security (A14)         │ hash, CSRF, regenerasi,   │ A14    │
│                         │ onlyPost, ExceptionHandler│        │
│  Logger (A14)           │ jejak INFO/WARN/ERROR     │ A14    │
│  API (A15)              │ ApiResponse JSON +        │ A15    │
│                         │ status code + php://input │        │
└──────────────────────────────────────────────────────────────┘
```

## 2. Peta ke framework nyata (kenapa ini "expert foundation")

| Pola kita | Terjemahan di dunia nyata (mis. Laravel) |
|---|---|
| Router + DI container (A10) | Service Container, routes (`Route::post(...)`) |
| BaseController `view`/`redirect`/`pesanError` | Controller trait / `RedirectResponse` / exception → view |
| Service + Validator deklaratif (A13) | Form Request (`rules()`) + Validator |
| Repository (A10/A13) | Repository pattern / Query builder |
| Session helper + old input (A13) | `session()`, `old()`, redirect `withInput()` |
| CSRF + onlyPost (A14) | `@csrf`, method spoofing, middleware `web` |
| Logger (A14) | `Log::info()` |
| ExceptionHandler + AppException (A14) | `App\Exceptions\Handler`, custom exceptions |
| ApiResponse + routes/api.php (A15) | `route('api')`, `response()->json()`, StatusCodes |

→ Memahami 4 acara ini = memahami **garis besar arsitektur Laravel** tanpa framework.

## 3. Pola pikir pemula → expert (ringkasan)

**Pemula bertanya:** *"Gimana cara menyimpan data?"*
**Menengah bertanya:** *"Di lapisan mana setiap keputusan duduk, dan siapa yang bertanggung jawab kalau aturan berubah?"*
**Expert bertanya:** *"Apa konteks & trade-off? Apakah memakai layanan yang sama untuk web+API, di mana keamanan berlapis, bagaimana saya memverifikasi & mengukur — serta apa yang tidak boleh saya percaya?"*

Ciri khas code di 4 acara ini yang perlu kamu pegang sebagai kriteria "kode profesional":
1. **Satu sumber kebenaran** untuk rule (A10 model, A13 validators, √).
2. **Controller tidak tahu business logic** — hanya arah halaman (A13).
3. **Data jelek tidak pernah sampai DB** — validasi jarang terlewat (A10).
4. **Keamanan berlapis & pesan tidak bocor** — hash, CSRF, onlyPost, 419 (A14).
5. **Pembaca lain tahu status** — status code benar, respons JSON seragam (A15).

## 4. Tantangan akhir (praktik mandiri — jawab sendiri dulu, baru tanya)

Ambil struktur Acara 15 lalu tambahkan **endpoint terbaru**, misal:

- `PUT api/mahasiswa/{nim}` untuk update (method API tambahan + pembacaan body JSON + cek unik saat update seperti A13), dan
- `DELETE api/mahasiswa/{nim}` untuk hapus.
- Lalu proteksi `POST/PUT/DELETE` dengan **token header** sederhana (`X-API-Key` vs konstanta) — obrolan lanjutan menuju "auth API" (acara 16/REST).

Petunjuk jalan yang harus dilalui: `routes/api.php` → tambah baris → `ApiController` → method baru → repository → `ApiResponse` → uji dengan curl/Postman → status code yang tepat (200/201/204/400/401/404/405).

---

## Lampiran — mini "cheat sheet" untuk presentasi

- **A10:** `ReflectionClass` → autowiring DI. `BaseController`/`BaseModel` abstract = kontrak. `validate()` mengembalikan array semua error; repository `throw` sebelum INSERT.
- **A13:** Controller = koordinator arah; Service = business logic; `"required|digits|min:7|max:15"` = aturan data; `['ok'=>bool,'errors'=>array]` = kontrak hasil; `Session::withOldInput` = isian tak hilang.
- **A14:** `password_verify` (bukan plaintext), `session_regenerate_id(true)` setelah login (anti fixation), `Csrf::field/verify` + 419 (anti CSRF), `onlyPost` → 405, ExceptionHandler tak bocor detail, Logger → `storage/logs/app.log`.
- **A15:** jalur `api/` dipisah di `public/index.php`; kunci route `"METHOD path"`; `php://input` + `json_decode`; `{success, message, data/errors}`; 200/201/400/404/405/500; `exit` setelah respons agar tidak ada HTML menempel.
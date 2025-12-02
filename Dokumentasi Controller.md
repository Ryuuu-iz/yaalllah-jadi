# Dokumentasi Controller LMS Laravel 12

## Daftar Controller yang Telah Dibuat

### 1. Admin Controllers

#### AdminDashboardController
**Path:** `App\Http\Controllers\Admin\DashboardController`

**Methods:**
- `index()` - Menampilkan dashboard admin dengan statistik:
  - Total users
  - Total guru
  - Total siswa
  - Total courses

#### AdminUserController
**Path:** `App\Http\Controllers\Admin\UserController`

**Methods:**
- `index()` - Menampilkan daftar semua users
- `create()` - Form tambah user baru
- `store()` - Menyimpan user baru (admin/guru/siswa)
- `edit($user)` - Form edit user
- `update($user)` - Update data user
- `destroy($user)` - Hapus user

**Fitur:**
- CRUD lengkap untuk user management
- Otomatis membuat data_siswa atau data_guru sesuai role
- Validasi unique untuk username, nisn, nip
- Hash password otomatis

#### AdminCourseController
**Path:** `App\Http\Controllers\Admin\CourseController`

**Methods:**
- `index()` - Menampilkan daftar semua courses dengan pagination
- `create()` - Form tambah course baru
- `store()` - Menyimpan course baru
- `show($course)` - Detail course dengan siswa, materi, tugas
- `edit($course)` - Form edit course
- `update($course)` - Update data course
- `destroy($course)` - Hapus course

**Fitur:**
- CRUD lengkap untuk course management
- Relasi dengan mata pelajaran, kelas, dan guru
- Auto-generate enrollment key

---

### 2. Guru Controllers

#### GuruDashboardController
**Path:** `App\Http\Controllers\Guru\DashboardController`

**Methods:**
- `index()` - Menampilkan dashboard guru dengan:
  - Total courses yang diajar
  - Total tugas yang dibuat
  - Total pengumpulan tugas
  - Pengumpulan belum dinilai
  - 5 course terbaru
  - 5 tugas dengan deadline terdekat

#### GuruCourseController
**Path:** `App\Http\Controllers\Guru\CourseController`

**Methods:**
- `index()` - Daftar courses yang diajar guru
- `create()` - Form tambah course baru
- `store()` - Menyimpan course baru
- `show($course)` - Detail course dengan siswa, materi, tugas
- `enrollStudent($course)` - Menambahkan siswa ke course secara manual

**Fitur:**
- Hanya menampilkan courses milik guru yang login
- Auto-generate enrollment key
- Manual enrollment siswa

#### GuruMateriController
**Path:** `App\Http\Controllers\Guru\MateriController`

**Methods:**
- `index()` - Daftar semua materi yang dibuat guru
- `create()` - Form tambah materi baru
- `store()` - Menyimpan materi baru dengan upload file
- `show($materi)` - Detail materi dengan tugas terkait
- `edit($materi)` - Form edit materi
- `update($materi)` - Update materi dengan upload file baru
- `destroy($materi)` - Hapus materi dan file

**Fitur:**
- Upload file materi (PDF, DOC, DOCX, PPT, PPTX, ZIP, RAR)
- Max file size: 20MB
- Auto-delete file lama saat update
- Verifikasi akses (hanya guru pemilik course)

#### GuruTugasController
**Path:** `App\Http\Controllers\Guru\TugasController`

**Methods:**
- `index()` - Daftar semua tugas yang dibuat guru
- `create()` - Form tambah tugas baru
- `store()` - Menyimpan tugas baru dengan upload file
- `show($tugas)` - Detail tugas dengan daftar pengumpulan siswa
- `gradeSubmission($pengumpulan)` - Memberikan nilai dan feedback

**Fitur:**
- Upload file tugas (PDF, DOC, DOCX, PPT, PPTX)
- Max file size: 10MB
- Set deadline tugas
- Grading system (0-100)
- Feedback untuk siswa

#### GuruAbsensiController
**Path:** `App\Http\Controllers\Guru\AbsensiController`

**Methods:**
- `index()` - Daftar absensi dengan filter course dan tanggal
- `create()` - Form input absensi untuk course tertentu
- `store()` - Menyimpan absensi untuk semua siswa dalam course
- `edit()` - Form edit absensi berdasarkan course dan tanggal
- `update()` - Update data absensi
- `destroy()` - Hapus absensi berdasarkan course dan tanggal

**Fitur:**
- Input absensi per course
- Status: hadir, izin, sakit, alpha
- Validasi duplikasi (tidak bisa input 2x untuk tanggal yang sama)
- Bulk input untuk semua siswa dalam course
- Edit dan hapus absensi

---

### 3. Siswa Controllers

#### SiswaDashboardController
**Path:** `App\Http\Controllers\Siswa\DashboardController`

**Methods:**
- `index()` - Menampilkan dashboard siswa dengan:
  - Total courses yang diikuti
  - Total tugas
  - Tugas yang sudah dikumpulkan
  - Tugas belum dikumpulkan
  - 5 courses yang diikuti
  - 5 tugas dengan deadline terdekat
  - Statistik absensi bulan ini

#### SiswaCourseController
**Path:** `App\Http\Controllers\Siswa\CourseController`

**Methods:**
- `index()` - Daftar courses yang diikuti siswa
- `enroll()` - Mendaftar ke course dengan enrollment key
- `show($course)` - Detail course dengan materi dan tugas
- `leave($course)` - Keluar dari course

**Fitur:**
- Enrollment dengan key
- Validasi sudah terdaftar atau belum
- Akses materi dan tugas course
- Leave course

#### SiswaTugasController
**Path:** `App\Http\Controllers\Siswa\TugasController`

**Methods:**
- `index()` - Daftar semua tugas dari courses yang diikuti
- `submit($tugas)` - Submit tugas dengan upload file

**Fitur:**
- Upload file pengumpulan (PDF, DOC, DOCX, ZIP, RAR)
- Max file size: 10MB
- Tambah keterangan/catatan
- Auto-detect status (tepat_waktu/terlambat)
- Validasi: tidak bisa submit 2x untuk tugas yang sama
- Melihat nilai dan feedback dari guru

#### SiswaAbsensiController
**Path:** `App\Http\Controllers\Siswa\AbsensiController`

**Methods:**
- `index()` - Daftar absensi siswa dengan filter bulan/tahun
  - Statistik keseluruhan
  - Statistik per bulan (6 bulan terakhir)

**Fitur:**
- View riwayat absensi
- Filter berdasarkan bulan dan tahun
- Statistik absensi (hadir, izin, sakit, alpha)
- Chart statistik per bulan

---

## Fitur Keamanan di Semua Controller

### 1. Authentication
Semua controller dilindungi middleware `auth` di routes

### 2. Authorization
- **Role-based access**: Middleware `role:admin`, `role:guru`, `role:siswa`
- **Ownership verification**: Guru hanya bisa akses data course/materi/tugas miliknya
- **Enrollment verification**: Siswa hanya bisa akses course yang diikuti

### 3. Validation
- Input validation menggunakan `$request->validate()`
- File upload validation (type, size)
- Unique constraint validation

### 4. File Security
- File disimpan di storage/app/public
- Symbolic link ke public/storage
- Auto-delete file lama saat update/delete

---

## Cara Penggunaan

### Install Dependencies
```bash
composer install
npm install && npm run build
```

### Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### Setup Storage
```bash
php artisan storage:link
```

### Jalankan Server
```bash
php artisan serve
```

### Login Credentials
- Admin: `admin` / `admin123`
- Guru: `guru1` / `guru123`
- Siswa: `siswa1` / `siswa123`

---

## Routes Summary

### Admin Routes
```
GET  /admin/dashboard
GET  /admin/users
POST /admin/users
GET  /admin/users/create
GET  /admin/users/{user}/edit
PUT  /admin/users/{user}
DELETE /admin/users/{user}
GET  /admin/courses
POST /admin/courses
GET  /admin/courses/create
GET  /admin/courses/{course}
GET  /admin/courses/{course}/edit
PUT  /admin/courses/{course}
DELETE /admin/courses/{course}
```

### Guru Routes
```
GET  /guru/dashboard
GET  /guru/courses
POST /guru/courses
GET  /guru/courses/create
GET  /guru/courses/{course}
POST /guru/courses/{course}/enroll
GET  /guru/materi
POST /guru/materi
GET  /guru/materi/create
GET  /guru/materi/{materi}
GET  /guru/materi/{materi}/edit
PUT  /guru/materi/{materi}
DELETE /guru/materi/{materi}
GET  /guru/tugas
POST /guru/tugas
GET  /guru/tugas/create
GET  /guru/tugas/{tugas}
POST /guru/tugas/pengumpulan/{pengumpulan}/grade
GET  /guru/absensi
POST /guru/absensi
GET  /guru/absensi/create
GET  /guru/absensi/edit
PUT  /guru/absensi/update
DELETE /guru/absensi/destroy
```

### Siswa Routes
```
GET  /siswa/dashboard
GET  /siswa/courses
POST /siswa/courses/enroll
GET  /siswa/courses/{course}
DELETE /siswa/courses/{course}/leave
GET  /siswa/tugas
POST /siswa/tugas/{tugas}/submit
GET  /siswa/absensi
```

---

## File Structure
```
app/
├── Http/
│   └── Controllers/
│       ├── Auth/
│       │   └── LoginController.php
│       ├── Admin/
│       │   ├── DashboardController.php
│       │   ├── UserController.php
│       │   └── CourseController.php
│       ├── Guru/
│       │   ├── DashboardController.php
│       │   ├── CourseController.php
│       │   ├── MateriController.php
│       │   ├── TugasController.php
│       │   └── AbsensiController.php
│       └── Siswa/
│           ├── DashboardController.php
│           ├── CourseController.php
│           ├── TugasController.php
│           └── AbsensiController.php
```

---

## Next Steps

1. **Buat Views (Blade Templates)** untuk semua controller
2. **Implementasi Policies** untuk authorization yang lebih detail
3. **Tambahkan API** untuk mobile app (optional)
4. **Implementasi Notifications** untuk tugas baru, deadline, dll
5. **Export Reports** (PDF/Excel) untuk absensi dan nilai
6. **Real-time Updates** dengan Laravel Echo (optional)
7. **Testing** - Unit tests dan Feature tests
8. **Deployment** ke production server

---

## Support

Untuk pertanyaan atau issues, silakan hubungi tim development.
# Sistem Absensi Apel Pagi BPKAD Kota Bontang

Sistem Absensi Apel Pagi BPKAD Kota Bontang merupakan aplikasi web internal yang digunakan untuk membantu proses pencatatan dan pengelolaan kehadiran pegawai dalam kegiatan apel pagi.

Aplikasi ini dibangun menggunakan **Laravel 12** dan menyediakan sistem absensi berbasis **QR Code**, **selfie**, serta **verifikasi lokasi** untuk membantu memastikan proses absensi dilakukan oleh pegawai yang bersangkutan dan berada di lingkungan kantor.

---

## Fitur Utama

### Admin

- Dashboard informasi kehadiran pegawai
- Manajemen data pegawai
- Tambah dan edit data pegawai
- Aktivasi dan nonaktifkan akun pegawai
- Import data pegawai dari Excel
- Generate QR Code apel pagi
- Melihat riwayat apel pegawai
- Melihat detail absensi pegawai
- Mengelola pengajuan ketidakhadiran
- Filter laporan berdasarkan periode dan status
- Export laporan absensi ke PDF
- Rekap status kehadiran pegawai
- Pembuatan status Alpha otomatis

### Pegawai

- Login menggunakan NIP
- Dashboard pegawai
- Scan QR Code apel pagi
- Verifikasi kehadiran
- Pengambilan selfie
- Verifikasi lokasi
- Melihat status apel
- Melihat riwayat apel
- Mengajukan keterangan ketidakhadiran
- Mengubah password akun

### Status Kehadiran

Sistem mendukung beberapa status kehadiran:

- Hadir
- Terlambat
- Izin
- Sakit
- Dinas Luar
- Lainnya
- Alpha

---

## Teknologi yang Digunakan

Aplikasi dikembangkan menggunakan:

- Laravel 12
- PHP 8.2+
- MySQL / MariaDB
- Blade Template
- Tailwind CSS
- Alpine.js
- JavaScript
- Vite
- Composer
- Node.js
- NPM
- Maatwebsite Laravel Excel
- Git

---

## Persyaratan Sistem

Sebelum melakukan instalasi, pastikan perangkat/server sudah memiliki:

- PHP 8.2 atau lebih baru
- Composer
- MySQL atau MariaDB
- Node.js
- NPM
- Git
- Apache atau Nginx untuk production server

Untuk lingkungan Windows dapat menggunakan:

- XAMPP
- Laragon

---

# Instalasi Project

## 1. Clone Repository

Clone repository menggunakan Git:

```bash
git clone https://github.com/keyyboy21/absensi-bpkad.git
```

Kemudian masuk ke folder project:

```bash
cd absensi-bpkad
```

---

## 2. Install Dependency Laravel

Jalankan:

```bash
composer install
```

Tunggu hingga seluruh dependency Laravel selesai di-install.

---

## 3. Install Dependency Frontend

Jalankan:

```bash
npm install
```

---

## 4. Membuat File Environment

Project tidak menyimpan file `.env` di GitHub karena file tersebut berisi konfigurasi khusus server.

Salin `.env.example` menjadi `.env`.

### Windows

```bash
copy .env.example .env
```

### Linux

```bash
cp .env.example .env
```

---

## 5. Generate Application Key

Jalankan:

```bash
php artisan key:generate
```

Laravel akan membuat `APP_KEY` secara otomatis pada file `.env`.

---

# Konfigurasi Database

## 1. Buat Database

Buat database MySQL/MariaDB dengan nama:

```text
db_absensi_bpkad
```

Database dapat dibuat menggunakan:

- phpMyAdmin
- MySQL CLI
- HeidiSQL
- Database manager lainnya

---

## 2. Konfigurasi `.env`

Buka file:

```text
.env
```

Kemudian sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_absensi_bpkad
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan:

```text
DB_USERNAME
DB_PASSWORD
```

dengan akun database yang digunakan pada server.

---

## 3. Membuat Struktur Database

Jika menggunakan database baru/kosong, jalankan:

```bash
php artisan migrate
```

Perintah tersebut akan membuat tabel yang diperlukan aplikasi.

---

## Menggunakan Database yang Sudah Ada

Jika database dari perangkat development sudah diexport, database tersebut dapat langsung diimport ke server.

Database dapat diimport menggunakan:

- phpMyAdmin
- MySQL CLI
- HeidiSQL

Dengan cara ini, data yang sudah tersedia pada database development dapat dipindahkan ke server.

> Jangan menjalankan proses yang dapat menghapus data production tanpa melakukan backup terlebih dahulu.

---

# Konfigurasi Waktu Apel

Konfigurasi waktu apel berada pada file:

```text
.env
```

Contoh:

```env
APP_TIMEZONE=Asia/Makassar

ATTENDANCE_START_TIME=07:30
ATTENDANCE_END_TIME=07:45
```

Keterangan:

```text
ATTENDANCE_START_TIME
```

merupakan waktu mulai absensi apel.

Sedangkan:

```text
ATTENDANCE_END_TIME
```

merupakan batas waktu absensi apel.

---

# Mode Testing Absensi

Aplikasi menyediakan konfigurasi testing untuk membantu proses pengembangan.

Contoh:

```env
ATTENDANCE_TEST_MODE=false
ATTENDANCE_TEST_DATE=
ATTENDANCE_TEST_TIME=
```

Untuk penggunaan sebenarnya pada server kantor, gunakan:

```env
ATTENDANCE_TEST_MODE=false
```

Mode testing sebaiknya tidak diaktifkan pada production.

---

# Konfigurasi Lokasi Kantor

Aplikasi menggunakan lokasi perangkat untuk membantu melakukan verifikasi kehadiran.

Konfigurasi berada pada `.env`:

```env
OFFICE_LATITUDE=
OFFICE_LONGITUDE=
OFFICE_RADIUS=150
```

Isi:

```text
OFFICE_LATITUDE
```

dengan latitude kantor.

Isi:

```text
OFFICE_LONGITUDE
```

dengan longitude kantor.

Sedangkan:

```text
OFFICE_RADIUS
```

merupakan radius absensi dalam satuan meter.

Contoh:

```env
OFFICE_RADIUS=150
```

berarti pegawai diperbolehkan melakukan proses absensi dalam radius sekitar 150 meter dari titik kantor yang telah ditentukan.

---

# Build Frontend

Untuk development:

```bash
npm run dev
```

Perintah tersebut menjalankan Vite Development Server.

Untuk production:

```bash
npm run build
```

Setelah build selesai, asset production akan dibuat oleh Vite.

Pada server production gunakan:

```bash
npm run build
```

dan tidak perlu menjalankan `npm run dev` secara terus-menerus.

---

# Storage Link

Untuk membuat symbolic link storage Laravel, jalankan:

```bash
php artisan storage:link
```

Perintah ini diperlukan apabila aplikasi menggunakan file yang disimpan pada public storage.

---

# Laravel Scheduler

Aplikasi menggunakan Laravel Scheduler untuk menjalankan proses otomatis, termasuk pembuatan status **Alpha** bagi pegawai yang tidak melakukan absensi sampai batas waktu yang telah ditentukan.

## Development

Untuk development dapat menjalankan:

```bash
php artisan schedule:work
```

Terminal tersebut harus tetap berjalan selama scheduler digunakan.

---

## Production

Pada production, Laravel Scheduler harus dijalankan secara otomatis oleh server.

### Linux

Gunakan Cron untuk menjalankan:

```bash
php artisan schedule:run
```

setiap satu menit.

Contoh Cron:

```text
* * * * * cd /path/ke/absensi-bpkad && php artisan schedule:run >> /dev/null 2>&1
```

Sesuaikan:

```text
/path/ke/absensi-bpkad
```

dengan lokasi project pada server.

### Windows

Jika server menggunakan Windows, Laravel Scheduler dapat dijalankan menggunakan **Windows Task Scheduler**.

Task Scheduler perlu menjalankan:

```bash
php artisan schedule:run
```

secara berkala agar proses otomatis Laravel tetap berjalan.

---

# Menjalankan Project untuk Development

Untuk menjalankan project menggunakan development server Laravel:

```bash
php artisan serve
```

Secara default aplikasi dapat diakses melalui:

```text
http://127.0.0.1:8000
```

Perintah tersebut digunakan untuk development/testing.

---

# Konfigurasi Production

Untuk production, web server seperti Apache atau Nginx harus diarahkan ke folder:

```text
public
```

Contoh:

```text
absensi-bpkad/public
```

Jangan mengarahkan document root web server langsung ke root project Laravel.

---

# Cache Laravel

Setelah melakukan perubahan konfigurasi, dapat menjalankan:

```bash
php artisan optimize:clear
```

Untuk production, setelah konfigurasi sudah benar dapat menjalankan:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika konfigurasi `.env` diubah kembali, jalankan:

```bash
php artisan optimize:clear
```

sebelum membuat cache kembali.

---

# Update Project dari GitHub

Jika terdapat perubahan terbaru pada repository, masuk ke folder project server kemudian jalankan:

```bash
git pull
```

Jika terdapat perubahan dependency PHP:

```bash
composer install
```

Jika terdapat perubahan dependency frontend:

```bash
npm install
npm run build
```

Jika terdapat migration baru:

```bash
php artisan migrate --force
```

Kemudian bersihkan cache:

```bash
php artisan optimize:clear
```

---

# Workflow Update Project

Pada perangkat development, setelah melakukan perubahan:

```bash
git status
```

Tambahkan perubahan:

```bash
git add .
```

Buat commit:

```bash
git commit -m "Keterangan perubahan"
```

Kemudian push:

```bash
git push
```

Pada server kantor, ambil perubahan terbaru menggunakan:

```bash
git pull
```

---

# Struktur Singkat Project

```text
absensi-bpkad/
│
├── app/
│   ├── Console/
│   ├── Helpers/
│   ├── Http/
│   ├── Imports/
│   └── Models/
│
├── bootstrap/
│
├── config/
│   └── attendance.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── public/
│   └── images/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   ├── auth.php
│   ├── console.php
│   └── web.php
│
├── storage/
│
├── tests/
│
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
└── README.md
```

---

# Keamanan

Beberapa hal yang perlu diperhatikan:

1. Jangan meng-upload file `.env` ke repository.
2. Jangan menyimpan password database di source code.
3. Gunakan repository **Private** untuk penggunaan internal instansi.
4. Nonaktifkan mode testing ketika aplikasi digunakan pada production.
5. Gunakan konfigurasi database yang berbeda antara development dan production.
6. Lakukan backup database secara berkala.
7. Pastikan hanya administrator yang memiliki akses ke fungsi administrasi.
8. Jangan membagikan kredensial server melalui repository.

File berikut tidak boleh dimasukkan ke repository:

```text
.env
/vendor
/node_modules
```

File tersebut sudah seharusnya ditangani melalui `.gitignore`.

---

# Deployment Singkat

Urutan deployment pada server baru:

```bash
git clone https://github.com/keyyboy21/absensi-bpkad.git
cd absensi-bpkad

composer install

copy .env.example .env

php artisan key:generate

npm install
npm run build

php artisan storage:link
```

Selanjutnya:

1. Buat database `db_absensi_bpkad`.
2. Konfigurasi database pada `.env`.
3. Import database lama atau jalankan migration.
4. Konfigurasi lokasi kantor.
5. Pastikan `ATTENDANCE_TEST_MODE=false`.
6. Konfigurasi web server ke folder `public`.
7. Aktifkan Laravel Scheduler.
8. Uji login Admin.
9. Uji login Pegawai.
10. Uji QR Code, selfie, dan verifikasi lokasi.
11. Uji laporan.
12. Uji proses Alpha otomatis.

---

# Repository

Repository project:

```text
https://github.com/keyyboy21/absensi-bpkad
```

---

# Developer

**Muhamad Rizky**
**202312070**

Program Studi Teknik Informatika STITEK Bontang

Project Magang / Kerja Praktek
BPKAD Kota Bontang

---

## BPKAD Kota Bontang

**Sistem Absensi Apel Pagi Pegawai**

Dikembangkan untuk membantu proses pencatatan dan pengelolaan kehadiran apel pagi pegawai secara terkomputerisasi.

# Product Requirements Document (PRD)

## TemuBarang – Platform Lost & Found Berbasis Website

---

# 1. Informasi Dokumen

| Item            | Keterangan                                                                  |
| --------------- | --------------------------------------------------------------------------- |
| Nama Produk     | TemuBarang                                                                  |
| Jenis Produk    | Website (Web Application)                                                   |
| Versi           | 1.0                                                                         |
| Target Pengguna | Masyarakat umum, sekolah, kampus, kantor, tempat ibadah, pusat perbelanjaan |
| Platform        | Web Responsive                                                              |
| Status          | Product Requirement Document                                                |

---

# 2. Latar Belakang

Di Indonesia, ketika seseorang kehilangan barang atau menemukan barang milik orang lain, informasi biasanya disebarkan melalui media sosial seperti WhatsApp, Facebook, atau Instagram. Cara ini memiliki beberapa kelemahan, seperti informasi yang cepat tenggelam, jangkauan yang terbatas, dan tidak adanya sistem yang dapat mempertemukan laporan barang hilang dengan laporan barang ditemukan.

Akibatnya, banyak barang yang sebenarnya telah ditemukan tidak pernah kembali kepada pemiliknya karena tidak ada media yang terpusat.

TemuBarang hadir sebagai platform yang menghubungkan orang yang kehilangan barang dengan orang yang menemukannya melalui sistem pencarian, pencocokan data, dan verifikasi kepemilikan.

---

# 3. Identifikasi Masalah

## Permasalahan

1. Tidak ada platform lokal yang terpusat untuk melaporkan barang hilang dan barang ditemukan.
2. Informasi kehilangan barang tersebar di berbagai media sosial sehingga sulit ditemukan kembali.
3. Orang yang menemukan barang sering tidak mengetahui cara mencari pemiliknya.
4. Pemilik barang kesulitan mengetahui apakah barangnya telah ditemukan.
5. Tidak ada sistem yang membantu mencocokkan laporan kehilangan dengan laporan penemuan.
6. Sulit memverifikasi kepemilikan barang sehingga berpotensi terjadi klaim palsu.

---

# 4. Tujuan Produk

Membangun platform berbasis website yang dapat:

- Menjadi pusat informasi barang hilang dan ditemukan.
- Mempermudah masyarakat melaporkan kehilangan maupun penemuan barang.
- Mempercepat proses pencocokan antara pemilik dan penemu.
- Mengurangi jumlah barang yang tidak kembali kepada pemilik.

---

# 5. Target Pengguna

## Primary User

- Masyarakat umum
- Pelajar
- Mahasiswa
- Karyawan

## Secondary User

- Sekolah
- Kampus
- Masjid
- Gereja
- Kantor
- Mall
- Terminal
- Stasiun

## Administrator

- Mengelola sistem
- Memoderasi laporan
- Memverifikasi laporan

---

# 6. Scope Produk

## In Scope

- Registrasi akun
- Login
- Laporan barang hilang
- Laporan barang ditemukan
- Upload foto
- Pencarian
- Filter
- Dashboard
- Riwayat laporan
- Notifikasi
- Verifikasi kepemilikan
- Status laporan

## Out of Scope (Versi 1)

- Mobile App
- AI Image Recognition
- Chat langsung
- Integrasi WhatsApp
- GPS Tracking
- QR Code
- IoT

---

# 7. User Persona

## Persona 1

Nama:
Andi

Umur:
20 Tahun

Status:
Mahasiswa

Masalah:
Kehilangan dompet di kampus.

Harapan:
Barang dapat ditemukan dengan cepat.

---

## Persona 2

Nama:
Siti

Umur:
35 Tahun

Status:
Guru

Masalah:
Menemukan tas yang tertinggal.

Harapan:
Menemukan pemilik tas.

---

# 8. User Journey

## Barang Hilang

Login

↓

Klik "Laporkan Kehilangan"

↓

Isi Form

↓

Upload Foto

↓

Publikasi

↓

Menunggu Kecocokan

↓

Barang Ditemukan

↓

Verifikasi

↓

Status Selesai

---

## Barang Ditemukan

Login

↓

Klik "Laporkan Penemuan"

↓

Isi Form

↓

Upload Foto

↓

Publikasi

↓

Menunggu Pemilik

↓

Verifikasi

↓

Barang Diserahkan

↓

Status Selesai

---

# 9. Functional Requirements

## Authentication

### FR-001

Pengguna dapat registrasi.

---

### FR-002

Pengguna dapat login.

---

### FR-003

Pengguna dapat logout.

---

## Profil

### FR-004

Melihat profil.

---

### FR-005

Mengubah profil.

---

## Barang Hilang

### FR-006

Menambah laporan kehilangan.

Data:

- Nama barang
- Kategori
- Warna
- Merk
- Deskripsi
- Lokasi hilang
- Tanggal
- Foto

---

### FR-007

Mengubah laporan.

---

### FR-008

Menghapus laporan.

---

## Barang Ditemukan

### FR-009

Menambah laporan penemuan.

Data:

- Nama barang
- Lokasi ditemukan
- Tanggal
- Foto
- Deskripsi

---

### FR-010

Mengubah laporan.

---

### FR-011

Menghapus laporan.

---

## Pencarian

### FR-012

Cari berdasarkan:

- Nama
- Merk
- Warna
- Lokasi

---

### FR-013

Filter:

- Kategori
- Status
- Tanggal

---

## Dashboard

### FR-014

Menampilkan statistik:

- Barang hilang
- Barang ditemukan
- Barang selesai

---

## Status

### FR-015

Status:

- Hilang
- Ditemukan
- Diproses
- Selesai

---

## Verifikasi

### FR-016

Admin memverifikasi laporan.

---

### FR-017

Pemilik melakukan klaim.

---

### FR-018

Admin menyetujui klaim.

---

## Riwayat

### FR-019

Pengguna melihat riwayat laporan.

---

## Notifikasi

### FR-020

Pengguna menerima notifikasi ketika status berubah.

---

# 10. Non Functional Requirements

## Performance

- Waktu loading < 3 detik.

---

## Security

- Password Hash.
- HTTPS.
- Validasi Input.
- CSRF Protection.

---

## Availability

- Website berjalan 24 jam.

---

## Responsive

Berjalan pada:

- Desktop
- Laptop
- Tablet
- Smartphone

---

## Compatibility

Browser:

- Chrome
- Edge
- Firefox
- Safari

---

# 11. Role User

## Guest

- Melihat daftar barang
- Melakukan pencarian

---

## User

- Registrasi
- Login
- Melapor kehilangan
- Melapor penemuan
- Mengubah profil
- Melihat riwayat

---

## Admin

- Mengelola user
- Mengelola laporan
- Memverifikasi laporan
- Menghapus spam
- Dashboard

---

# 12. Database (Entitas)

- Users
- Reports
- Categories
- Photos
- Notifications
- Claims
- Statuses

---

# 13. Use Case

### Guest

- Melihat daftar barang
- Melakukan pencarian
- Registrasi

---

### User

- Login
- Melapor kehilangan
- Melapor penemuan
- Mengelola laporan
- Melihat notifikasi
- Mengajukan klaim

---

### Admin

- Login
- Verifikasi laporan
- Kelola pengguna
- Kelola kategori
- Moderasi laporan
- Dashboard

---

# 14. Halaman Website

- Landing Page
- Login
- Register
- Dashboard
- Daftar Barang Hilang
- Daftar Barang Ditemukan
- Detail Barang
- Form Laporan Kehilangan
- Form Laporan Penemuan
- Riwayat Laporan
- Profil
- Notifikasi
- Dashboard Admin
- Manajemen Pengguna
- Manajemen Kategori
- Moderasi Laporan

---

# 15. Tech Stack

## Frontend

- HTML5
- CSS3
- JavaScript
- Bootstrap atau Tailwind CSS

## Backend

- Laravel 12 (PHP 8.3+)

## Database

- MySQL atau MariaDB

## Storage

- Local Storage (development)
- Cloud Storage (production)

## Authentication

- Laravel Breeze atau Laravel Starter Kit

## Maps

- Leaflet.js + OpenStreetMap (opsional pada versi berikutnya)

---

# 16. MVP (Minimum Viable Product)

Fitur yang harus tersedia pada versi pertama:

- Registrasi & Login
- Laporan Barang Hilang
- Laporan Barang Ditemukan
- Upload Foto
- Pencarian
- Filter
- Detail Barang
- Dashboard Statistik Sederhana
- Notifikasi Status
- Riwayat Laporan
- Panel Admin untuk moderasi

---

# 17. Pengembangan Versi Selanjutnya (Roadmap)

### Versi 1.1

- Peta lokasi kehilangan/penemuan.
- Sistem bookmark atau simpan laporan.
- Berbagi laporan ke media sosial.

### Versi 1.2

- Pencocokan otomatis berdasarkan kategori, warna, merek, lokasi, dan rentang waktu.
- Sistem reputasi bagi pengguna yang aktif mengembalikan barang.

### Versi 2.0

- AI untuk membantu mencocokkan foto barang.
- Integrasi email dan WhatsApp untuk notifikasi.
- Portal khusus bagi sekolah, kampus, terminal, pusat perbelanjaan, dan instansi sebagai mitra.

---

# 18. Indikator Keberhasilan (Success Metrics)

| Indikator                         | Target MVP                                          |
| --------------------------------- | --------------------------------------------------- |
| Pengguna terdaftar                | ≥ 500 pengguna                                      |
| Laporan barang hilang             | ≥ 200 laporan                                       |
| Laporan barang ditemukan          | ≥ 150 laporan                                       |
| Tingkat keberhasilan pencocokan   | ≥ 40% dari laporan yang memiliki pasangan potensial |
| Waktu rata-rata publikasi laporan | < 2 menit                                           |
| Kepuasan pengguna (survei)        | ≥ 80% pengguna menyatakan aplikasi mudah digunakan  |

## Catatan Pengembangan

Agar produk ini memiliki **nilai inovasi** dibanding platform lost & found lainnya, jangan berhenti pada fungsi "posting barang hilang". Fokuskan pengembangan pada **membantu proses mempertemukan pemilik dan penemu**. Salah satu fitur pembeda yang dapat direncanakan sejak awal adalah **mesin pencocokan otomatis** yang memberikan rekomendasi berdasarkan kemiripan data (kategori, warna, merek, lokasi, dan waktu), sehingga pengguna tidak perlu mencari secara manual di ratusan laporan. Ini akan menjadi nilai jual utama aplikasi sekaligus topik penelitian yang menarik.

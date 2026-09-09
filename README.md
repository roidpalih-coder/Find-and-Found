# Find & Found

Platform web terpusat untuk melaporkan, mencari, mengklaim, dan mengembalikan barang hilang di wilayah Kabupaten Pati dan sekitarnya.

## Deskripsi

Find & Found menghubungkan orang yang kehilangan barang dengan orang yang menemukannya melalui sistem pelaporan, pencarian, verifikasi kepemilikan, dan koordinasi serah terima via WhatsApp/Instagram.

## Tech Stack

| Bagian | Teknologi |
|---|---|
| Frontend | Vue.js 3 (Composition API), Vue Router 4, Pinia, Tailwind CSS, Leaflet.js |
| Backend | Laravel 12 (PHP 8.3+), Laravel Sanctum |
| Database | MySQL / MariaDB |
| Storage | Local Disk (dev) / S3-compatible (prod) |
| Maps | Leaflet.js + OpenStreetMap |

## Struktur Repository

```
Find&Found/
├── README.md                   # Panduan project ini
├── backend/                    # Laravel 12 REST API
├── frontend/                   # Vue.js 3 SPA
├── docs/
│   ├── PRD.md                  # Product Requirement Document (final)
│   ├── SURVEY_ANALYSIS.md      # Analisis data survei 50 responden
│   ├── DATABASE_SCHEMA.md      # Skema database lengkap + ERD
│   ├── API_ENDPOINTS.md        # Dokumentasi semua endpoint API
│   ├── DESIGN_SYSTEM.md        # Design system, warna, tipografi, komponen
│   └── CHANGELOG.md            # Riwayat perubahan dokumen
├── diagrams/
│   ├── userflow.md             # User flow diagram (Mermaid)
│   ├── sitemap.md              # Sitemap diagram (Mermaid)
│   ├── class-diagram.md        # UML Class diagram (Mermaid)
│   ├── sequence-diagram.md     # Sequence diagram klaim (Mermaid)
│   └── activity-diagram.md     # Activity diagram pelaporan (Mermaid)
├── wireframes/
│   ├── low-fidelity/           # Wireframe lo-fi (20 halaman)
│   └── high-fidelity/          # Wireframe hi-fi (20 halaman)
├── data/                       # Data mentah survei
└── prototypes/                 # Prototype HTML
```

## Halaman Aplikasi

### Public (Tanpa Login)
| Route | Halaman |
|---|---|
| `/` | Home / Landing Page |
| `/explore` | Eksplorasi & Filter Barang |
| `/items/:id` | Detail Barang |
| `/priority-documents` | Dokumen Prioritas (KTP/SIM/Paspor) |
| `/about` | Tentang Platform |
| `/login` | Login |
| `/register` | Registrasi |

### User (Setelah Login)
| Route | Halaman |
|---|---|
| `/dashboard` | Dashboard Aktivitas |
| `/report/lost` | Form Lapor Barang Hilang |
| `/report/found` | Form Lapor Barang Ditemukan |
| `/my-reports` | Riwayat Laporan Saya |
| `/my-claims` | Klaim yang Saya Ajukan |
| `/incoming-claims` | Klaim Masuk atas Barang Temuan Saya |
| `/notifications` | Notifikasi |
| `/profile` | Profil & Reputasi |

### Admin
| Route | Halaman |
|---|---|
| `/admin/dashboard` | Statistik Global |
| `/admin/items` | Moderasi Laporan |
| `/admin/claims` | Pengawasan Klaim |
| `/admin/categories` | Kelola Kategori |
| `/admin/users` | Kelola Pengguna |

## Dokumentasi

- [PRD Lengkap](docs/PRD.md)
- [Skema Database](docs/DATABASE_SCHEMA.md)
- [API Endpoints](docs/API_ENDPOINTS.md)
- [Design System](docs/DESIGN_SYSTEM.md)
- [Analisis Survei](docs/SURVEY_ANALYSIS.md)

## Setup Development

### Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend (Vue)
```bash
cd frontend
npm install
npm run dev
```

## Tim

**Kelompok — XII TJKT 1**
1. Muhammad Alqaus Sigit Widodo
2. Muhammad Roid Falih
3. Nabila Aufa Bilqis Ardiyani
4. Sekar Dwi Wulandari
5. Shaza Nurmalak

# Find & Found

Platform web terpusat untuk melaporkan, mencari, mengklaim, dan mengembalikan barang hilang di wilayah Kabupaten Pati dan sekitarnya.

## Deskripsi

Find & Found menghubungkan orang yang kehilangan barang dengan orang yang menemukannya melalui sistem pelaporan, pencarian, verifikasi kepemilikan, dan koordinasi serah terima via WhatsApp/Instagram.

## Tech Stack

| Bagian | Teknologi |
|---|---|
| Frontend | Vue.js 3 (Composition API), Vue Router 4, Pinia, Tailwind CSS, Leaflet.js |
| Backend | Laravel 12 (PHP 8.3+), REST API, Laravel Sanctum |
| Database | MySQL / MariaDB |
| Storage | Local Disk (dev) / S3-compatible (prod) |
| Maps | Leaflet.js + OpenStreetMap |

## Struktur Repository

```text
Find&Found/
├── README.md                   # Panduan utama project
├── backend/                    # Backend API (Laravel 12 REST API)
├── frontend/                   # Frontend SPA (Vue.js 3 + Vite)
├── docs/                       # Dokumentasi teknis & requirement
│   ├── PRD.md                  # Product Requirement Document (final)
│   ├── SURVEY_ANALYSIS.md      # Analisis data survei 50 responden
│   ├── DATABASE_SCHEMA.md      # Skema database lengkap + ERD
│   ├── API_ENDPOINTS.md        # Dokumentasi semua endpoint API
│   ├── DESIGN_SYSTEM.md        # Aturan desain, warna, tipografi, komponen
│   ├── FRONTEND_PROMPT.md      # Panduan/prompt untuk setup frontend
│   └── CHANGELOG.md            # Riwayat perubahan dokumentasi
├── diagrams/                   # Semua diagram sistem (Mermaid & PlantUML)
│   ├── userflow.md             # Flow pengguna secara umum
│   ├── sitemap.md              # Peta hierarki navigasi
│   ├── use-case-diagram.md     # UML Use Case Diagram (Mermaid)
│   ├── use-case-diagram.puml   # UML Use Case Diagram (PlantUML)
│   ├── class-diagram.md        # UML Class diagram & struktur database
│   ├── sequence-diagram.md     # Sequence diagram proses klaim
│   └── activity-diagram.md     # Activity diagram alur pelaporan
├── wireframes/                 # Lo-Fi dan Hi-Fi desain antarmuka
│   ├── low-fidelity/           # Mockup kasar (20 halaman)
│   └── high-fidelity/          # Visual akhir UI (20 halaman)
├── data/                       # Data mentah
│   └── survey-responses.csv    # Hasil raw form kuesioner awal
├── prototypes/                 # Prototype klik interaktif & export desain
└── tools/                      # Skrip bantuan/utilitas project
```

## Anggota Tim Pengembang — XII TJKT 1

1. Muhammad Alqaus Sigit Widodo
2. Muhammad Roid Falih
3. Nabila Aufa Bilqis Ardiyani
4. Sekar Dwi Wulandari
5. Shaza Nurmalak

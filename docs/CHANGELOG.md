# Changelog — Find & Found

Semua perubahan signifikan pada dokumen proyek dicatat di sini.
Format mengikuti [Keep a Changelog](https://keepachangelog.com/).

---

## [2.1.0] — 2026-08-23

### Perubahan Besar dari PRD Lama (TemuBarang → Find & Found)

**Diubah:**
- Nama produk dari *TemuBarang* menjadi **Find & Found**
- Tech stack frontend dari HTML/CSS/Bootstrap murni menjadi **Vue.js 3 + Pinia + Tailwind CSS**
- Komunikasi dari in-app real-time chat (WebSocket) menjadi **WhatsApp deep link + Instagram redirect**
- Arsitektur dari server-rendered Blade menjadi **Decoupled SPA (Vue 3 + Laravel REST API)**

**Ditambahkan:**
- Kategori khusus dokumen prioritas (KTP, SIM, Paspor, Kartu Pelajar) dengan badge merah
- Filter pencarian per kecamatan di Kabupaten Pati
- Sistem reward & poin reputasi penemu
- Smart Match Alert — notifikasi otomatis kecocokan laporan
- Integrasi peta interaktif Leaflet.js + OpenStreetMap
- Field `secret_details` di tabel `items` untuk verifikasi kepemilikan
- Tabel `notifications` (hilang di PRD versi lama)

**Dihapus:**
- Field `warna` dan `merk` dari form laporan (digabung ke `description`)
- Status `Diproses` (disederhanakan menjadi `claimed`)

---

## [1.0.0] — 2026-07-01 *(PRD Awal — TemuBarang)*

### Rilis Dokumen PRD Awal

**Ditambahkan:**
- PRD dasar dengan fitur: registrasi, laporan hilang/ditemukan, pencarian, filter, dashboard, notifikasi, verifikasi kepemilikan
- Database entitas: Users, Reports, Categories, Photos, Notifications, Claims, Statuses
- Tech stack: Laravel 12 + MySQL + HTML/CSS/JS + Bootstrap

---

## Struktur File Dokumen

| File | Versi Terakhir | Keterangan |
| :--- | :--- | :--- |
| `docs/PRD.md` | 2.1.0 | Dokumen kebutuhan produk final |
| `docs/SURVEY_ANALYSIS.md` | 1.0.0 | Analisis data survei 50 responden |
| `docs/DATABASE_SCHEMA.md` | 1.0.0 | Skema tabel lengkap + ERD |
| `docs/API_ENDPOINTS.md` | 1.0.0 | Dokumentasi semua endpoint API |
| `docs/DESIGN_SYSTEM.md` | 1.0.0 | Design system, warna, komponen |
| `diagrams/userflow.md` | 1.0.0 | User flow (Mermaid) |
| `diagrams/sitemap.md` | 1.0.0 | Sitemap (Mermaid) |
| `diagrams/class-diagram.md` | 1.0.0 | UML Class Diagram |
| `diagrams/sequence-diagram.md` | 1.0.0 | Sequence Diagram klaim |
| `diagrams/activity-diagram.md` | 1.0.0 | Activity Diagram pelaporan |

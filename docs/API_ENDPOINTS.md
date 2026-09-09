# API Endpoints — Find & Found

**Base URL:** `https://api.domain.com/api/v1`
**Format:** JSON
**Autentikasi:** Bearer Token (Laravel Sanctum)
**Timestamp:** ISO-8601

---

## Konvensi Response

### Success
```json
{
  "message": "...",
  "data": { ... }
}
```

### Paginated
```json
{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 72
  }
}
```

### Error
```json
{
  "message": "Pesan error.",
  "errors": { "field": ["Pesan validasi"] }
}
```

### HTTP Status Codes
| Code | Arti |
| :--- | :--- |
| 200 | OK |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthenticated |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Unprocessable Entity (validasi gagal) |
| 500 | Internal Server Error |

---

## AUTH

### POST `/auth/register`
Mendaftarkan pengguna baru.

**Request Body:**
```json
{
  "name": "Muhammad Alqaus",
  "email": "alqaus@email.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone_number": "6281234567890",
  "instagram_handle": "alqaus_id",
  "domicile_city": "Pati"
}
```

**Response `201`:**
```json
{
  "message": "Registrasi berhasil.",
  "token": "1|abc...",
  "user": {
    "id": 1,
    "name": "Muhammad Alqaus",
    "email": "alqaus@email.com",
    "role": "user",
    "reputation_points": 0
  }
}
```

---

### POST `/auth/login`
Login dan mendapatkan Bearer Token.

**Request Body:**
```json
{
  "email": "alqaus@email.com",
  "password": "password123"
}
```

**Response `200`:**
```json
{
  "message": "Login berhasil.",
  "token": "1|abc...",
  "user": {
    "id": 1,
    "name": "Muhammad Alqaus",
    "role": "user"
  }
}
```

---

### POST `/auth/logout`
`Auth required`

Logout dan hapus token aktif.

**Response `200`:**
```json
{ "message": "Logout berhasil." }
```

---

## PROFILE

### GET `/profile`
`Auth required`

Mengambil data profil pengguna yang sedang login.

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "name": "Muhammad Alqaus",
    "email": "alqaus@email.com",
    "phone_number": "6281234567890",
    "instagram_handle": "alqaus_id",
    "domicile_city": "Pati",
    "avatar_url": "/storage/avatars/1.jpg",
    "reputation_points": 50,
    "role": "user"
  }
}
```

---

### PUT `/profile`
`Auth required`

Mengubah data profil pengguna.

**Request Body (multipart/form-data):**
```json
{
  "name": "Muhammad Alqaus Sigit",
  "phone_number": "6281234567890",
  "instagram_handle": "alqaus_sigit",
  "domicile_city": "Juwana",
  "avatar": "[file]"
}
```

**Response `200`:**
```json
{
  "message": "Profil berhasil diperbarui.",
  "data": { ... }
}
```

---

## ITEMS

### GET `/items`
Mengambil daftar barang (publik, tanpa auth). Mendukung filter & pagination.

**Query Parameters:**
| Parameter | Tipe | Contoh | Keterangan |
| :--- | :--- | :--- | :--- |
| `type` | string | `lost` / `found` | Filter tipe laporan |
| `category_id` | integer | `2` | Filter kategori |
| `status` | string | `open` | Filter status |
| `district` | string | `Juwana` | Filter kecamatan |
| `search` | string | `kunci motor` | Pencarian kata kunci |
| `date_from` | date | `2026-08-01` | Filter tanggal mulai |
| `date_to` | date | `2026-08-31` | Filter tanggal akhir |
| `priority` | boolean | `true` | Hanya dokumen prioritas |
| `page` | integer | `1` | Pagination |

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "type": "found",
      "title": "Dompet Hitam",
      "category": { "id": 2, "name": "Dompet", "is_priority_document": false },
      "location_name": "Alun-alun Pati",
      "district": "Pati Kota",
      "incident_date": "2026-08-10T14:00:00Z",
      "primary_photo_url": "/storage/items/1.jpg",
      "status": "open",
      "created_at": "2026-08-10T15:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42
  }
}
```

---

### GET `/items/:id`
Mengambil detail lengkap satu laporan barang (publik).

**Response `200`:**
```json
{
  "data": {
    "id": 1,
    "type": "found",
    "title": "Dompet Hitam",
    "description": "Ditemukan di dekat parkiran...",
    "category": { "id": 2, "name": "Dompet", "is_priority_document": false },
    "incident_date": "2026-08-10T14:00:00Z",
    "location_name": "Alun-alun Pati",
    "district": "Pati Kota",
    "latitude": -6.7283,
    "longitude": 111.0384,
    "primary_photo_url": "/storage/items/1.jpg",
    "photos": [
      { "id": 1, "photo_url": "/storage/items/1a.jpg" }
    ],
    "reward_offered": null,
    "status": "open",
    "user": {
      "id": 2,
      "name": "Roid Falih",
      "reputation_points": 100
    },
    "created_at": "2026-08-10T15:00:00Z"
  }
}
```

> **Catatan:** `secret_details`, `phone_number`, dan `instagram_handle` user TIDAK dikembalikan di sini.

---

### POST `/items`
`Auth required`

Membuat laporan barang baru (hilang atau ditemukan).

**Request Body (multipart/form-data):**
```
type             : "lost" | "found"
title            : "Kunci Motor Honda Beat Hitam"
category_id      : 7
description      : "Kunci dengan gantungan..."
secret_details   : "Ada stiker doraemon di gantungan" (opsional)
incident_date    : "2026-08-10 14:00"
location_name    : "Parkiran SMKN 1 Pati"
district         : "Pati Kota"
latitude         : -6.7283 (opsional)
longitude        : 111.0384 (opsional)
reward_offered   : "Traktir makan siang" (opsional)
photo            : [file utama]
photos[]         : [file tambahan, opsional, max 4]
```

**Response `201`:**
```json
{
  "message": "Laporan berhasil dipublikasikan.",
  "data": { "id": 5, "title": "Kunci Motor Honda Beat Hitam", ... }
}
```

---

### PUT `/items/:id`
`Auth required` | Hanya pemilik laporan

Mengubah data laporan (hanya jika status masih `open`).

**Request Body:** Sama seperti POST `/items` (field yang ingin diubah saja).

**Response `200`:**
```json
{ "message": "Laporan berhasil diperbarui.", "data": { ... } }
```

---

### PUT `/items/:id/status`
`Auth required` | Pemilik laporan atau Admin

Mengubah status laporan.

**Request Body:**
```json
{ "status": "cancelled" }
```

**Response `200`:**
```json
{ "message": "Status laporan diperbarui menjadi cancelled." }
```

---

### DELETE `/items/:id`
`Auth required` | Hanya pemilik laporan atau Admin

Menghapus laporan (hanya jika status `open` atau `cancelled`).

**Response `200`:**
```json
{ "message": "Laporan berhasil dihapus." }
```

---

### GET `/items/my`
`Auth required`

Mengambil semua laporan milik user yang sedang login.

**Query Parameters:** `status`, `type`, `page`

**Response `200`:** Sama seperti `GET /items` tapi hanya milik user sendiri.

---

## CLAIMS

### POST `/items/:id/claims`
`Auth required`

Mengajukan klaim kepemilikan atas suatu laporan barang.

**Request Body (multipart/form-data):**
```
proof_description : "Dompet saya berwarna hitam dengan isi KTP, SIM, dan uang 150rb"
proof_photo       : [file foto pembanding, opsional]
```

**Response `201`:**
```json
{
  "message": "Klaim berhasil diajukan. Menunggu konfirmasi penemu.",
  "data": { "id": 3, "status": "pending", ... }
}
```

---

### GET `/claims/incoming`
`Auth required`

Mengambil semua klaim masuk atas laporan milik user (sebagai Finder).

**Response `200`:**
```json
{
  "data": [
    {
      "id": 3,
      "item": { "id": 1, "title": "Dompet Hitam" },
      "claimant": { "id": 5, "name": "Nabila Aufa" },
      "proof_description": "Dompet saya berwarna hitam...",
      "proof_photo_url": "/storage/claims/3.jpg",
      "status": "pending",
      "created_at": "2026-08-11T10:00:00Z"
    }
  ]
}
```

---

### GET `/claims/my`
`Auth required`

Mengambil semua klaim yang pernah diajukan oleh user (sebagai Claimant).

**Response `200`:** Daftar klaim beserta info item dan status.

---

### PATCH `/claims/:claimId/status`
`Auth required` | Hanya pemilik laporan (Finder) atau Admin

Menyetujui atau menolak klaim kepemilikan.

**Request Body:**
```json
{
  "status": "approved",
  "response_notes": "Ciri-ciri cocok, silakan hubungi saya."
}
```

**Response `200` (jika approved):**
```json
{
  "message": "Klaim disetujui.",
  "contact": {
    "phone_number": "6281234567890",
    "instagram_handle": "alqaus_id",
    "whatsapp_url": "https://wa.me/6281234567890?text=Halo%2C+saya+pemilik+barang...",
    "instagram_url": "https://instagram.com/alqaus_id"
  }
}
```

---

### GET `/claims/:claimId/contact`
`Auth required` | Hanya claimant yang klaimnya `approved`

Mengambil data kontak penemu setelah klaim disetujui.

**Response `200`:**
```json
{
  "data": {
    "phone_number": "6281234567890",
    "instagram_handle": "alqaus_id",
    "whatsapp_url": "https://wa.me/6281234567890",
    "instagram_url": "https://instagram.com/alqaus_id"
  }
}
```

---

### POST `/items/:id/reward`
`Auth required` | Hanya pemilik item setelah status `resolved`

Memberi rating dan badge apresiasi ke penemu.

**Request Body:**
```json
{
  "rating": 5,
  "badge": "honest_finder",
  "message": "Terima kasih sudah jujur mengembalikan dompet saya!"
}
```

**Response `200`:**
```json
{
  "message": "Apresiasi berhasil diberikan. Penemu mendapat +50 poin reputasi."
}
```

---

## CATEGORIES

### GET `/categories`
Mengambil semua kategori (publik).

**Response `200`:**
```json
{
  "data": [
    { "id": 1, "name": "KTP / Identitas", "slug": "ktp-identitas", "icon": "id-card", "is_priority_document": true },
    { "id": 5, "name": "HP / Smartphone", "slug": "hp-smartphone", "icon": "smartphone", "is_priority_document": false }
  ]
}
```

---

## NOTIFICATIONS

### GET `/notifications`
`Auth required`

Mengambil semua notifikasi milik user.

**Query Parameters:** `read` (true/false), `page`

**Response `200`:**
```json
{
  "data": [
    {
      "id": 1,
      "type": "claim_approved",
      "data": {
        "item_id": 5,
        "item_title": "Dompet Hitam",
        "message": "Klaim Anda untuk 'Dompet Hitam' telah disetujui."
      },
      "read_at": null,
      "created_at": "2026-08-11T12:00:00Z"
    }
  ],
  "unread_count": 3
}
```

---

### PATCH `/notifications/:id/read`
`Auth required`

Menandai satu notifikasi sebagai sudah dibaca.

**Response `200`:**
```json
{ "message": "Notifikasi ditandai telah dibaca." }
```

---

### PATCH `/notifications/read-all`
`Auth required`

Menandai semua notifikasi sebagai sudah dibaca.

**Response `200`:**
```json
{ "message": "Semua notifikasi ditandai telah dibaca." }
```

---

## ADMIN

> Semua endpoint admin membutuhkan `Auth required` + `role: admin`.

---

### GET `/admin/stats`
Statistik global untuk dashboard admin.

**Response `200`:**
```json
{
  "data": {
    "total_items": 210,
    "total_lost": 130,
    "total_found": 80,
    "total_resolved": 45,
    "total_claims": 87,
    "total_users": 312,
    "pending_moderation": 5
  }
}
```

---

### GET `/admin/items`
Daftar semua laporan untuk moderasi.

**Query Parameters:** `status`, `type`, `category_id`, `search`, `page`

**Response `200`:** Daftar item dengan info user lengkap.

---

### PATCH `/admin/items/:id`
Moderasi laporan (approve, hide, atau update status).

**Request Body:**
```json
{
  "status": "cancelled",
  "moderation_note": "Laporan duplikat / spam."
}
```

**Response `200`:**
```json
{ "message": "Laporan berhasil dimoderasi." }
```

---

### DELETE `/admin/items/:id`
Hapus permanen laporan.

**Response `200`:**
```json
{ "message": "Laporan berhasil dihapus permanen." }
```

---

### GET `/admin/claims`
Daftar semua klaim untuk pengawasan.

**Query Parameters:** `status`, `page`

**Response `200`:** Daftar klaim dengan info item dan claimant.

---

### PATCH `/admin/claims/:id`
Admin bisa override status klaim.

**Request Body:**
```json
{ "status": "rejected", "response_notes": "Bukti tidak memadai." }
```

---

### GET `/admin/categories`
Daftar semua kategori.

### POST `/admin/categories`
Buat kategori baru.

**Request Body:**
```json
{ "name": "Sepeda", "slug": "sepeda", "icon": "bike", "is_priority_document": false }
```

### PUT `/admin/categories/:id`
Edit kategori.

### DELETE `/admin/categories/:id`
Hapus kategori (jika tidak ada item yang menggunakannya).

---

### GET `/admin/users`
Daftar semua pengguna.

**Query Parameters:** `role`, `search`, `page`

**Response `200`:** Daftar user dengan info lengkap.

---

### PATCH `/admin/users/:id`
Update role atau blokir pengguna.

**Request Body:**
```json
{ "role": "admin" }
```

---

### DELETE `/admin/users/:id`
Hapus akun pengguna.

---

## Ringkasan Semua Endpoint

| Method | Endpoint | Auth | Keterangan |
| :---: | :--- | :---: | :--- |
| POST | `/auth/register` | — | Registrasi |
| POST | `/auth/login` | — | Login |
| POST | `/auth/logout` | Ya | Logout |
| GET | `/profile` | Ya | Lihat profil |
| PUT | `/profile` | Ya | Edit profil |
| GET | `/items` | — | Daftar barang + filter |
| GET | `/items/:id` | — | Detail barang |
| POST | `/items` | Ya | Buat laporan |
| PUT | `/items/:id` | Ya | Edit laporan |
| PUT | `/items/:id/status` | Ya | Ubah status |
| DELETE | `/items/:id` | Ya | Hapus laporan |
| GET | `/items/my` | Ya | Laporan saya |
| POST | `/items/:id/claims` | Ya | Ajukan klaim |
| GET | `/claims/incoming` | Ya | Klaim masuk (Finder) |
| GET | `/claims/my` | Ya | Klaim saya (Claimant) |
| PATCH | `/claims/:id/status` | Ya | Approve/reject klaim |
| GET | `/claims/:id/contact` | Ya | Kontak setelah approved |
| POST | `/items/:id/reward` | Ya | Beri reward ke Finder |
| GET | `/categories` | — | Daftar kategori |
| GET | `/notifications` | Ya | Daftar notifikasi |
| PATCH | `/notifications/:id/read` | Ya | Tandai dibaca |
| PATCH | `/notifications/read-all` | Ya | Tandai semua dibaca |
| GET | `/admin/stats` | Admin | Statistik global |
| GET | `/admin/items` | Admin | Moderasi laporan |
| PATCH | `/admin/items/:id` | Admin | Moderasi laporan |
| DELETE | `/admin/items/:id` | Admin | Hapus laporan |
| GET | `/admin/claims` | Admin | Pengawasan klaim |
| PATCH | `/admin/claims/:id` | Admin | Override klaim |
| GET | `/admin/categories` | Admin | Daftar kategori |
| POST | `/admin/categories` | Admin | Buat kategori |
| PUT | `/admin/categories/:id` | Admin | Edit kategori |
| DELETE | `/admin/categories/:id` | Admin | Hapus kategori |
| GET | `/admin/users` | Admin | Daftar pengguna |
| PATCH | `/admin/users/:id` | Admin | Edit role/blokir |
| DELETE | `/admin/users/:id` | Admin | Hapus pengguna |

# Backend — Find & Found API

Laravel 12 REST API dengan Laravel Sanctum.

**Base URL:** `http://localhost:8000/api/v1`

---

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

**Database:** Buat database `findfound` di MySQL terlebih dahulu.

---

## Akun Seed (Siap Pakai untuk Testing)

| Role | Email | Password |
| :--- | :--- | :--- |
| Admin | `admin@findfound.test` | `admin123` |
| User | `user@findfound.test` | `user1234` |

---

## Testing Endpoint

### Tool yang Direkomendasikan

| Tool | Keterangan |
| :--- | :--- |
| **Postman** | GUI, bisa import collection, paling mudah |
| **Insomnia** | Alternatif Postman, ringan |
| **cURL** | CLI, tanpa install apapun |
| **Thunder Client** | Extension VS Code |

---

### Cara Pakai Bearer Token di Postman

1. Login via `POST /api/v1/auth/login`
2. Copy nilai `token` dari response
3. Di setiap request yang butuh auth: tab **Authorization** → **Bearer Token** → paste token

---

### Urutan Testing yang Disarankan

#### 1. Auth
```
POST /api/v1/auth/register
POST /api/v1/auth/login        ← simpan token
POST /api/v1/auth/logout       [Auth]
```

#### 2. Kategori & Profil
```
GET  /api/v1/categories
GET  /api/v1/profile           [Auth]
PUT  /api/v1/profile           [Auth]
```

#### 3. Laporan Barang
```
GET  /api/v1/items
GET  /api/v1/items?type=lost&district=Pati
GET  /api/v1/items?search=kunci&category_id=7
GET  /api/v1/items/{id}
POST /api/v1/items             [Auth] — multipart/form-data
PUT  /api/v1/items/{id}        [Auth]
PUT  /api/v1/items/{id}/status [Auth]
GET  /api/v1/items/user/my     [Auth]
```

#### 4. Klaim
```
POST  /api/v1/items/{id}/claims         [Auth] — ajukan klaim
GET   /api/v1/claims/incoming           [Auth] — sebagai finder
GET   /api/v1/claims/my                 [Auth] — sebagai claimant
PATCH /api/v1/claims/{id}/status        [Auth] — approve/reject
GET   /api/v1/claims/{id}/contact       [Auth] — setelah approved
```

#### 5. Notifikasi
```
GET   /api/v1/notifications             [Auth]
PATCH /api/v1/notifications/read-all    [Auth]
PATCH /api/v1/notifications/{id}/read   [Auth]
```

#### 6. Reward
```
POST /api/v1/items/{id}/reward          [Auth] — setelah status resolved
```

#### 7. Admin (gunakan akun admin)
```
GET    /api/v1/admin/stats
GET    /api/v1/admin/items
PATCH  /api/v1/admin/items/{id}
DELETE /api/v1/admin/items/{id}
GET    /api/v1/admin/claims
PATCH  /api/v1/admin/claims/{id}
GET    /api/v1/admin/categories
POST   /api/v1/admin/categories
PUT    /api/v1/admin/categories/{id}
DELETE /api/v1/admin/categories/{id}
GET    /api/v1/admin/users
PATCH  /api/v1/admin/users/{id}
DELETE /api/v1/admin/users/{id}
```

---

### Contoh Request cURL

**Register:**
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alqaus Sigit",
    "email": "alqaus@test.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone_number": "6281234567890"
  }'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email": "user@findfound.test", "password": "user1234"}'
```

**Buat laporan (dengan token):**
```bash
curl -X POST http://localhost:8000/api/v1/items \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {TOKEN}" \
  -F "type=found" \
  -F "title=Dompet Hitam" \
  -F "category_id=6" \
  -F "description=Ditemukan di parkiran alun-alun" \
  -F "incident_date=2026-09-07 10:00" \
  -F "location_name=Alun-alun Pati" \
  -F "district=Pati Kota"
```

**Daftar barang dengan filter:**
```bash
curl "http://localhost:8000/api/v1/items?type=lost&district=Juwana&page=1" \
  -H "Accept: application/json"
```

---

### Format Response Error

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Header Wajib di Setiap Request
```
Accept: application/json
Content-Type: application/json     (untuk JSON body)
Content-Type: multipart/form-data  (untuk upload file)
Authorization: Bearer {token}      (untuk endpoint yang butuh auth)
```

---

## Struktur Folder Backend

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ItemController.php
│   │   │   ├── ClaimController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── NotificationController.php
│   │   │   └── Admin/
│   │   │       ├── AdminStatsController.php
│   │   │       ├── AdminItemController.php
│   │   │       ├── AdminClaimController.php
│   │   │       ├── AdminCategoryController.php
│   │   │       └── AdminUserController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/
│   │       ├── RegisterRequest.php
│   │       ├── LoginRequest.php
│   │       ├── UpdateProfileRequest.php
│   │       ├── StoreItemRequest.php
│   │       ├── UpdateItemStatusRequest.php
│   │       ├── StoreClaimRequest.php
│   │       └── UpdateClaimStatusRequest.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Item.php
│       ├── ItemPhoto.php
│       ├── Claim.php
│       └── AppNotification.php
├── database/
│   ├── migrations/       (6 tabel)
│   └── seeders/
│       ├── CategorySeeder.php   (10 kategori)
│       └── AdminSeeder.php      (admin + test user)
└── routes/
    └── api.php           (35 endpoint)
```

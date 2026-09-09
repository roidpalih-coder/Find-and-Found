# Diagram UML Sistem Find & Found

Berdasarkan dokumen spesifikasi dan arsitektur sistem pada repository ini, berikut kumpulan diagram UML standar (Mermaid format).

---

## 1. Use Case Diagram

Diagram use case memetakan interaksi antara 3 aktor utama (*Public/Guest*, *Authenticated User*, dan *Admin*) dengan modul sistem Find & Found.

```mermaid
graph LR
    actor Guest as "Guest / Publik"
    actor User as "User Terdaftar (Owner / Finder)"
    actor Admin as "Administrator"

    subgraph System ["Sistem Find & Found"]
        UC1(["Registrasi & Login"])
        UC2(["Eksplorasi & Filter Barang"])
        UC3(["Lihat Detail Barang & Peta"])
        UC4(["Lihat Dokumen Prioritas"])
        
        UC5(["Lapor Barang Hilang (Lost)"])
        UC6(["Lapor Barang Ditemukan (Found)"])
        UC7(["Ajukan Klaim Kepemilikan"])
        UC8(["Verifikasi / Review Klaim"])
        UC9(["Akses Kontak WhatsApp / IG"])
        UC10(["Kelola Profil & Reputasi"])
        
        UC11(["Moderasi Laporan Barang"])
        UC12(["Kelola Sengketa Klaim"])
        UC13(["Kelola Master Kategori"])
        UC14(["Manajemen User & Blacklist"])
        UC15(["Lihat Statistik Dashboard Admin"])
    end

    %% Relasi Guest
    Guest --> UC1
    Guest --> UC2
    Guest --> UC3
    Guest --> UC4

    %% Relasi User Terdaftar (mewarisi Guest)
    User --> UC2
    User --> UC3
    User --> UC4
    User --> UC5
    User --> UC6
    User --> UC7
    User --> UC8
    User --> UC9
    User --> UC10

    %% Relasi Admin
    Admin --> UC11
    Admin --> UC12
    Admin --> UC13
    Admin --> UC14
    Admin --> UC15

    %% Include / Extend
    UC7 -.->|includes| UC3
    UC9 -.->|extends (jika klaim disetujui)| UC8
```

---

## 2. Class Diagram

Diagram kelas memetakan struktur entitas data, model Eloquent ORM, atribut, tipe data, method, dan relasi antar kelas.

```mermaid
classDiagram
    direction TB

    class User {
        +BigInteger id
        +String name
        +String email
        +String password
        +String phone_number
        +String instagram_handle
        +String domicile_city
        +String avatar_url
        +Integer reputation_points
        +Role role
        +Timestamp created_at
        +Timestamp updated_at
        +register()
        +login()
        +updateProfile()
        +addReputation(points)
    }

    class Category {
        +Integer id
        +String name
        +String slug
        +String icon
        +Boolean is_priority_document
        +Timestamp created_at
        +Timestamp updated_at
        +getItems()
    }

    class Item {
        +BigInteger id
        +BigInteger user_id
        +Integer category_id
        +ItemType type
        +String title
        +String description
        +String secret_details
        +DateTime incident_date
        +String location_name
        +String district
        +Decimal latitude
        +Decimal longitude
        +String primary_photo_url
        +String reward_offered
        +ItemStatus status
        +Timestamp created_at
        +Timestamp updated_at
        +createReport()
        +updateStatus(status)
        +getClaims()
    }

    class ItemPhoto {
        +BigInteger id
        +BigInteger item_id
        +String photo_url
        +Timestamp created_at
    }

    class Claim {
        +BigInteger id
        +BigInteger item_id
        +BigInteger claimant_id
        +String proof_description
        +String proof_photo_url
        +ClaimStatus status
        +String response_notes
        +Timestamp created_at
        +Timestamp updated_at
        +submitClaim()
        +approve(notes)
        +reject(notes)
        +getUnlockedContact()
    }

    <<enumeration>> Role
    Role : USER
    Role : ADMIN

    <<enumeration>> ItemType
    ItemType : LOST
    ItemType : FOUND

    <<enumeration>> ItemStatus
    ItemStatus : OPEN
    ItemStatus : CLAIMED
    ItemStatus : RESOLVED
    ItemStatus : CANCELLED

    <<enumeration>> ClaimStatus
    ClaimStatus : PENDING
    ClaimStatus : APPROVED
    ClaimStatus : REJECTED

    User "1" --> "0..*" Item : "membuat laporan"
    User "1" --> "0..*" Claim : "mengajukan klaim"
    Category "1" --> "0..*" Item : "mengelompokkan"
    Item "1" *-- "0..*" ItemPhoto : "memiliki foto galeri"
    Item "1" --> "0..*" Claim : "menerima permohonan"
```

---

## 3. Sequence Diagram: Alur Pengajuan dan Verifikasi Klaim

Menampilkan interaksi berurutan antara Pemilik (*Claimant*), Frontend (Vue 3), Backend API (Laravel 12), Database, dan Penemu (*Finder*).

```mermaid
sequenceDiagram
    autonumber
    actor Claimant as Pemilik (Claimant)
    participant Vue as Frontend (Vue 3 SPA)
    participant API as Backend (Laravel 12 API)
    participant DB as Database (MySQL)
    actor Finder as Penemu (Finder)

    Claimant->>Vue: Buka halaman Item Detail (/items/:id)
    Claimant->>Vue: Isi form klaim (bukti kepemilikan, foto)
    Vue->>API: POST /api/v1/items/:id/claims (Bearer Token + Form Data)
    API->>DB: INSERT INTO claims (status: pending)
    DB-->>API: Claim Created (ID)
    API-->>Vue: 201 Created (Klaim berhasil diajukan)
    Vue-->>Claimant: Tampilkan notifikasi "Klaim Menunggu Verifikasi"

    Note over Finder, Vue: Penemu memeriksa klaim masuk
    Finder->>Vue: Buka Incoming Claims (/incoming-claims)
    Vue->>API: GET /api/v1/claims/incoming
    API->>DB: SELECT * FROM claims WHERE item.user_id = finder.id
    DB-->>API: Return claims list + claimant info
    API-->>Vue: 200 OK (Daftar klaim masuk)
    Vue-->>Finder: Tampilkan deskripsi & foto bukti

    alt Penemu Menyetujui Klaim
        Finder->>Vue: Klik Setujui (Status: approved)
        Vue->>API: PATCH /api/v1/claims/:claimId/status (status: approved)
        API->>DB: UPDATE claims SET status='approved'
        API->>DB: UPDATE items SET status='claimed'
        DB-->>API: Success
        API-->>Vue: 200 OK (Klaim disetujui)
        Vue-->>Finder: Tampilkan tombol WhatsApp / IG pemilik

        Note over Claimant, API: Pemilik mengakses kontak penemu
        Claimant->>Vue: Buka klaim disetujui (/my-claims)
        Vue->>API: GET /api/v1/claims/:claimId/contact
        API->>DB: Validasi status klaim == approved
        DB-->>API: Return verified kontak (wa.me link)
        API-->>Vue: 200 OK (Nomor WA & link IG penemu)
        Vue-->>Claimant: Tampilkan tombol "Hubungi via WhatsApp"
        Claimant->>Claimant: Redirect wa.me untuk janjian serah terima
    else Penemu Menolak Klaim
        Finder->>Vue: Klik Tolak + masukkan alasan
        Vue->>API: PATCH /api/v1/claims/:claimId/status (status: rejected)
        API->>DB: UPDATE claims SET status='rejected'
        API-->>Vue: 200 OK (Klaim ditolak)
        Vue-->>Finder: Klaim ditolak
    end
```

---

## 4. Activity Diagram: Pelaporan Barang Ditemukan (Found Item)

Menjelaskan alur aktivitas dan percabangan keputusan saat pengguna melaporkan barang temuan.

```mermaid
stateDiagram-v2
    [*] --> BukaForm: Buka menu /report/found
    BukaForm --> CekLogin: Periksa status autentikasi
    
    state CekLogin <<choice>>
    CekLogin --> TampilkanForm: Sudah login
    CekLogin --> HalamanLogin: Belum login
    HalamanLogin --> TampilkanForm: Login berhasil

    TampilkanForm --> IsiData: Isi data (nama barang, kategori, tanggal, deskripsi)
    IsiData --> UploadFoto: Upload foto utama & foto galeri
    UploadFoto --> PilihLokasi: Tentukan titik peta Leaflet & kecamatan
    PilihLokasi --> ValidasiInput: Klik tombol "Publikasikan Laporan"

    state ValidasiInput <<choice>>
    ValidasiInput --> SimpanBackend: Data valid
    ValidasiInput --> TampilkanError: Ada data kosong / tidak valid
    TampilkanError --> IsiData: Perbaiki input

    SimpanBackend --> SimpanFoto: Unggah file gambar ke storage
    SimpanFoto --> SimpanDB: Insert data ke tabel items
    SimpanDB --> CekPrioritas: Periksa is_priority_document

    state CekPrioritas <<choice>>
    CekPrioritas --> BeriBadgePrioritas: Dokumen penting (KTP/SIM/Paspor)
    CekPrioritas --> Standar: Barang umum
    
    BeriBadgePrioritas --> Selesai: Tampilkan di halaman Priority Documents & Explore
    Standar --> Selesai: Tampilkan di halaman Explore
    Selesai --> [*]
```

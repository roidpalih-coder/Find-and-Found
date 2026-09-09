# Sequence Diagram — Alur Pengajuan & Verifikasi Klaim

Menampilkan interaksi berurutan antara Pemilik (Claimant), Frontend (Vue 3), Backend API (Laravel 12), Database (MySQL), dan Penemu (Finder).

---

```mermaid
sequenceDiagram
    autonumber
    actor Claimant as Pemilik (Claimant)
    participant Vue as Frontend (Vue 3 SPA)
    participant API as Backend (Laravel 12 API)
    participant DB as Database (MySQL)
    actor Finder as Penemu (Finder)

    %% Claimant mengajukan klaim
    Claimant->>Vue: Buka halaman Item Detail (/items/:id)
    Vue->>API: GET /api/v1/items/:id
    API->>DB: SELECT * FROM items WHERE id = ?
    DB-->>API: Return item data (tanpa secret_details & kontak)
    API-->>Vue: 200 OK — detail item
    Vue-->>Claimant: Tampilkan detail barang + tombol Ajukan Klaim

    Claimant->>Vue: Isi form klaim (bukti kepemilikan, foto opsional)
    Vue->>API: POST /api/v1/items/:id/claims (Bearer Token + Form Data)
    API->>DB: INSERT INTO claims (status: pending)
    DB-->>API: Claim Created (ID)
    API->>DB: INSERT INTO notifications (type: claim_submitted, user_id: finder)
    DB-->>API: Notification Created
    API-->>Vue: 201 Created
    Vue-->>Claimant: Notifikasi "Klaim Menunggu Verifikasi"

    Note over Finder,Vue: Penemu memeriksa klaim masuk

    Finder->>Vue: Buka Incoming Claims (/incoming-claims)
    Vue->>API: GET /api/v1/claims/incoming (Bearer Token)
    API->>DB: SELECT claims WHERE items.user_id = finder.id AND status = pending
    DB-->>API: Return claims list + claimant info
    API-->>Vue: 200 OK
    Vue-->>Finder: Tampilkan deskripsi & foto bukti

    alt Penemu Menyetujui Klaim
        Finder->>Vue: Klik Setujui + isi response_notes
        Vue->>API: PATCH /api/v1/claims/:claimId/status {status: approved}
        API->>DB: UPDATE claims SET status = approved
        API->>DB: UPDATE items SET status = claimed
        API->>DB: INSERT INTO notifications (type: claim_approved, user_id: claimant)
        DB-->>API: Success
        API-->>Vue: 200 OK + contact info (phone_number, wa_url, instagram_url)
        Vue-->>Finder: Tampilkan kontak pemilik (WA / IG)

        Note over Claimant,API: Pemilik mengakses kontak penemu

        Claimant->>Vue: Buka My Claims (/my-claims)
        Vue->>API: GET /api/v1/claims/:claimId/contact (Bearer Token)
        API->>DB: Validasi status klaim == approved AND claimant_id == auth user
        DB-->>API: Return kontak terverifikasi
        API-->>Vue: 200 OK (wa.me link + instagram link)
        Vue-->>Claimant: Tampilkan tombol "Hubungi via WhatsApp"
        Claimant->>Claimant: Redirect wa.me untuk janjian serah terima

    else Penemu Menolak Klaim
        Finder->>Vue: Klik Tolak + masukkan alasan penolakan
        Vue->>API: PATCH /api/v1/claims/:claimId/status {status: rejected}
        API->>DB: UPDATE claims SET status = rejected
        API->>DB: INSERT INTO notifications (type: claim_rejected, user_id: claimant)
        DB-->>API: Success
        API-->>Vue: 200 OK
        Vue-->>Finder: Klaim berhasil ditolak
        Vue-->>Claimant: Notifikasi "Klaim Ditolak — Ajukan Ulang"
    end

    Note over Claimant,Finder: Setelah serah terima berhasil

    Claimant->>Vue: Konfirmasi barang diterima
    Vue->>API: PUT /api/v1/items/:id/status {status: resolved}
    API->>DB: UPDATE items SET status = resolved
    DB-->>API: Success
    API-->>Vue: 200 OK
    Vue-->>Claimant: Tampilkan prompt apresiasi untuk Finder

    Claimant->>Vue: Beri rating & badge ke Finder
    Vue->>API: POST /api/v1/items/:id/reward {rating, badge}
    API->>DB: UPDATE users SET reputation_points = reputation_points + 50 WHERE id = finder.id
    DB-->>API: Success
    API-->>Vue: 200 OK
    Vue-->>Claimant: Apresiasi berhasil dikirim
```

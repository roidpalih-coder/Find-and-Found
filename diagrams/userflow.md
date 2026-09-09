# User Flow — Find & Found

Diagram alur pengguna dari pendaftaran hingga serah terima barang.

---

## Alur Utama

```mermaid
flowchart TD
    Start([Mulai]) --> AuthCheck{Sudah Punya Akun?}
    AuthCheck -- Belum --> Register[Daftar Akun Baru & Isi No. WA / IG]
    AuthCheck -- Sudah --> Login[Login ke Akun]
    Register --> Login

    Login --> Choice{Pilih Aktivitas}

    %% Alur Kehilangan
    Choice -- Kehilangan Barang --> FormLost[Isi Form Lapor Kehilangan\nUpload Foto & Pin Lokasi]
    FormLost --> PublishLost[Postingan LOST Aktif di Feed]
    PublishLost --> MatchCheck{Ada Notifikasi Smart Match?}
    MatchCheck -- Ya --> DetailFound[Buka Detail Barang Ditemukan]
    MatchCheck -- Tidak --> SearchFilter[Cari & Filter\nKategori / Lokasi / Tanggal]
    SearchFilter --> DetailFound

    %% Alur Penemuan
    Choice -- Menemukan Barang --> FormFound[Isi Form Lapor Ditemukan\nFoto Bukti + Pin Peta]
    FormFound --> PublishFound[Postingan FOUND Aktif di Feed]

    %% Proses Klaim
    DetailFound --> ClaimBtn[Klik Ajukan Klaim Kepemilikan]
    ClaimBtn --> FormClaim[Kirim Bukti: Deskripsi Rahasia + Foto Pembanding]
    FormClaim --> FinderReview{Finder / Admin\nMeninjau Bukti}

    FinderReview -- Ditolak --> RejectNotice[Notifikasi: Klaim Ditolak\nBisa Ajukan Ulang dengan Bukti Baru]
    RejectNotice --> DetailFound

    FinderReview -- Disetujui --> ApproveNotice[Klaim Approved!\nKontak WA / IG Terbuka]
    ApproveNotice --> ContactHandoff[Hubungi via WhatsApp atau Instagram]
    ContactHandoff --> Meetup[Janji Temu di Lokasi Aman]
    Meetup --> HandoverSuccess[Konfirmasi Barang Diterima]
    HandoverSuccess --> RewardBadge[Beri Rating & Badge ke Finder]
    RewardBadge --> StatusResolved[Status Barang: RESOLVED]
    StatusResolved --> End([Selesai])
```

---

## Alur Smart Match Alert

```mermaid
flowchart TD
    StartAlert([Trigger: Postingan Baru Masuk]) --> SystemCheck[Sistem Cocokkan Kategori & Kata Kunci]
    SystemCheck --> MatchFound{Ada Kecocokan\nScore lebih dari 70%?}
    MatchFound -- Tidak --> EndAlert([Selesai, Tidak Ada Alert])
    MatchFound -- Ya --> GenerateNotif[Buat Notifikasi Match]
    GenerateNotif --> SendToUser[Kirim Notifikasi Web ke Pemilik Lost Item]
    SendToUser --> UserClick[User Klik Notifikasi]
    UserClick --> RedirectDetail[Redirect ke Detail Barang Ditemukan]
    RedirectDetail --> ClaimAction[Lanjut ke Proses Klaim]
```

---

## Alur Moderasi Admin

```mermaid
flowchart TD
    StartAdmin([Admin Dashboard]) --> ViewQueue[Lihat Antrian Laporan Baru / Dilaporkan]
    ViewQueue --> ReviewItem[Tinjau Detail Laporan]
    ReviewItem --> AdminDecision{Keputusan Moderasi}

    AdminDecision -- Approve --> StatusPublished[Ubah Status: Published]
    StatusPublished --> EndAdmin([Selesai])

    AdminDecision -- Hide / Reject --> AddReason[Pilih Alasan: Spam / Duplikat / Palsu]
    AddReason --> StatusHidden[Ubah Status: Hidden]
    StatusHidden --> NotifyCreator[Kirim Notifikasi ke Pembuat Laporan]
    NotifyCreator --> EndAdmin

    AdminDecision -- Delete --> HardDelete[Hapus Permanen dari Database]
    HardDelete --> EndAdmin
```

---

## Alur Reward & Reputasi

```mermaid
flowchart TD
    StartReward([Trigger: Status Barang = RESOLVED]) --> AskRating[Tampilkan Prompt Apresiasi ke Pemilik]
    AskRating --> OwnerDecision{Pemilik Ingin Beri Reward?}

    OwnerDecision -- Tidak / Skip --> DefaultPoint[Tambahkan +10 Poin Dasar ke Finder]
    DefaultPoint --> EndReward([Selesai])

    OwnerDecision -- Ya --> SelectBadge[Pilih Badge & Rating Bintang]
    SelectBadge --> AddBonusPoint[Tambahkan +50 Bonus Poin ke Profil Finder]
    AddBonusPoint --> EndReward
```

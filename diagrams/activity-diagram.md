# Activity Diagram — Pelaporan Barang Ditemukan

Alur aktivitas dan percabangan keputusan saat pengguna melaporkan barang temuan.

---

## Pelaporan Barang Ditemukan (Found Item)

```mermaid
stateDiagram-v2
    [*] --> BukaForm: Buka menu /report/found

    BukaForm --> CekLogin: Periksa status autentikasi

    state CekLogin <<choice>>
    CekLogin --> TampilkanForm: Sudah login
    CekLogin --> HalamanLogin: Belum login
    HalamanLogin --> TampilkanForm: Login berhasil

    TampilkanForm --> IsiStep1: Step 1 — Pilih tipe & kategori barang
    IsiStep1 --> IsiStep2: Step 2 — Isi nama, deskripsi, ciri rahasia
    IsiStep2 --> IsiStep3: Step 3 — Upload foto utama & foto galeri
    IsiStep3 --> IsiStep4: Step 4 — Tentukan lokasi & kecamatan di peta Leaflet
    IsiStep4 --> IsiStep5: Step 5 — Isi tanggal & jam penemuan
    IsiStep5 --> ValidasiInput: Klik tombol Publikasikan Laporan

    state ValidasiInput <<choice>>
    ValidasiInput --> SimpanBackend: Data valid
    ValidasiInput --> TampilkanError: Ada data kosong / tidak valid
    TampilkanError --> IsiStep1: Perbaiki input di step yang salah

    SimpanBackend --> UploadFoto: Unggah file gambar ke storage
    UploadFoto --> SimpanDB: INSERT data ke tabel items & item_photos

    SimpanDB --> CekPrioritas: Periksa is_priority_document pada kategori

    state CekPrioritas <<choice>>
    CekPrioritas --> BeriBadgePrioritas: Dokumen penting (KTP/SIM/Paspor/Kartu Pelajar)
    CekPrioritas --> Standar: Barang umum

    BeriBadgePrioritas --> TriggerSmartMatch: Jalankan Smart Match Alert
    Standar --> TriggerSmartMatch

    TriggerSmartMatch --> CekKecocokan: Cocokkan with laporan LOST yang ada

    state CekKecocokan <<choice>>
    CekKecocokan --> KirimNotif: Ada kecocokan (score lebih dari 70%)
    CekKecocokan --> Selesai: Tidak ada kecocokan

    KirimNotif --> Selesai: Notifikasi dikirim ke pemilik LOST item

    Selesai --> [*]
```

---

## Pelaporan Barang Hilang (Lost Item)

```mermaid
stateDiagram-v2
    [*] --> BukaFormLost: Buka menu /report/lost

    BukaFormLost --> CekLoginLost: Periksa status autentikasi

    state CekLoginLost <<choice>>
    CekLoginLost --> TampilkanFormLost: Sudah login
    CekLoginLost --> HalamanLoginLost: Belum login
    HalamanLoginLost --> TampilkanFormLost: Login berhasil

    TampilkanFormLost --> IsiDataLost: Isi data laporan kehilangan\n(nama, kategori, deskripsi, ciri rahasia, lokasi, tanggal)
    IsiDataLost --> UploadFotoLost: Upload foto barang yang hilang
    UploadFotoLost --> ValidasiLost: Klik Publikasikan

    state ValidasiLost <<choice>>
    ValidasiLost --> SimpanLost: Data valid
    ValidasiLost --> ErrorLost: Ada data tidak valid
    ErrorLost --> IsiDataLost: Perbaiki input

    SimpanLost --> PublishLost: Laporan LOST aktif di feed
    PublishLost --> TungguMatch: Menunggu notifikasi Smart Match
    TungguMatch --> [*]
```

# Analisis Survei — Find & Found

**Jumlah Responden:** 50 orang
**Periode Survei:** 7–10 Agustus 2026
**Wilayah Dominan:** Kabupaten Pati dan sekitarnya (Juwana, Sukolilo, Cluwak, Trangkil, Batangan, Margoyoso, dll.)
**Demografi:** Mayoritas pelajar usia 15–20 tahun, sebagian mahasiswa dan karyawan

---

## 1. Validasi Masalah

- **~90% responden** pernah kehilangan barang — masalah nyata dan relevan bagi target pasar.
- **Barang paling sering hilang:** Kunci motor/rumah, Dompet, HP, Kartu Identitas/Dokumen (KTP, SIM, Kartu Pelajar), Uang tunai.
- **Kendala terbesar saat mencari (urut terbanyak):**
  1. Tidak ada informasi barang ditemukan
  2. Tidak tahu harus melapor ke mana
  3. Proses konfirmasi kepemilikan sulit
  4. Tidak punya waktu untuk mencari

## 2. Perilaku Saat Menemukan Barang Orang Lain

- Mayoritas: menyerahkan ke satpam/pengelola
- Sebagian: mengunggah ke media sosial (Instagram, Facebook, WA)
- Sebagian kecil: mengabaikan atau mengambil untuk diri sendiri

## 3. Sebaran Domisili Responden

- **Mayoritas:** Kabupaten Pati (Kota Pati, Juwana, Sukolilo, Cluwak, Trangkil, Batangan, Margoyoso, Sidokerto, dll.)
- **Sebagian kecil:** Kabupaten tetangga — Jepara, Rembang
- **Segelintir:** Luar Jawa Tengah (Purbalingga, Jakarta) — tidak signifikan

**Implikasi:** Target pengguna awal dan wilayah uji coba paling relevan adalah Pati dan sekitarnya. Ini juga alasan kuat fitur **filter lokasi per kecamatan** diprioritaskan.

## 4. Validasi Kebutuhan Aplikasi

- **Skor kebutuhan aplikasi:** Mayoritas 4–5 dari 5
- **Kesediaan menggunakan:** Mayoritas Ya, sebagian Mungkin, hanya sedikit Tidak
- **Kesimpulan:** Sinyal kuat untuk melanjutkan pengembangan

## 5. Preferensi Channel Komunikasi

| Channel | Preferensi |
| :--- | :--- |
| WhatsApp | #1 — dipilih >80% responden |
| Chat di dalam aplikasi | #2 |
| Melalui Admin | #3 |
| Email | Sangat jarang |

**Rekomendasi:** Integrasikan WhatsApp sebagai kanal utama (deep link `wa.me`); chat in-app sebagai pelengkap; Instagram sebagai alternatif.

## 6. Apresiasi untuk Penemu

- **>65% responden** bersedia menerima hadiah/apresiasi saat berhasil mengembalikan barang
- **Implikasi:** Fitur reward/badge/poin reputasi dapat meningkatkan partisipasi penemu secara signifikan

## 7. Fitur yang Paling Banyak Diminta (Prioritas Utama)

| Fitur | Tingkat Kepentingan |
| :--- | :--- |
| Upload foto barang | Hampir semua responden, skor rata-rata 4–5 |
| Pencarian & filter lokasi/kategori | >45 dari 50 responden |
| Verifikasi kepemilikan | Banyak diminta |
| Riwayat laporan | Banyak diminta |
| Notifikasi jika ada barang mirip | Banyak diminta |
| Chat antara penemu dan pemilik | Diminta, digantikan deep link WA/IG |

## 8. Fitur Tambahan dari Jawaban Terbuka (Dipertimbangkan)

| Saran Responden | Status |
| :--- | :--- |
| Fitur share lokasi / lokasi real-time | Dipertimbangkan (Leaflet geo-tagging) |
| Prioritas khusus dokumen penting (KTP/SIM/Paspor) | **Diimplementasikan** (FR-04) |
| Video call / voice call untuk verifikasi | Out of scope v1 |
| Akses kamera langsung dalam aplikasi | Dipertimbangkan (upload foto) |
| Info waktu & lokasi barang ditemukan ditampilkan jelas | **Diimplementasikan** (field `incident_date`, `location_name`) |
| WhatsApp Cloud API | Out of scope v1 (pakai deep link) |
| Fitur COD / janjian serah terima | Digantikan koordinasi via WA |
| Notifikasi saat ada barang hilang baru | **Diimplementasikan** (FR-11 Smart Match) |

## 9. Rekomendasi Final dari Data Survei

1. **Fitur inti wajib:** Form lapor hilang/ditemukan dengan foto, filter lokasi & kategori, verifikasi kepemilikan, riwayat laporan, redirect WA/IG.
2. **Integrasi lokasi:** Peta atau tag lokasi (Leaflet + koordinat), bukan sekadar teks.
3. **Notifikasi otomatis:** Ketika ada barang baru yang cocok dengan laporan kehilangan (Smart Match).
4. **Komunikasi:** WhatsApp deep link sebagai utama, Instagram sebagai alternatif.
5. **Reward ringan:** Badge + poin reputasi untuk penemu yang mengembalikan barang.
6. **Kategori prioritas:** KTP/SIM/Paspor tampil lebih menonjol dari barang biasa.
7. **Fokus wilayah awal:** Pati dan sekitarnya — sesuai sebaran domisili responden.

---

*Data mentah tersedia di [../data/](../data/)*

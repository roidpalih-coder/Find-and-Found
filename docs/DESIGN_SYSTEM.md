# Design System — Find & Found

**Brand Name:** Find & Found
**Tagline:** *"Temukan yang Hilang, Kembalikan dengan Tenang."*
**Personality:** Trustworthy, Helpful, Modern, Clean, Community-Centric
**Tone:** Minimalis, kontras jelas, ramah mobile (*thumb-friendly*), memberikan rasa aman

---

## 1. Color Tokens

```css
:root {
  /* Brand Primary — Deep Trust Blue */
  --color-primary-50:  #eff6ff;
  --color-primary-100: #dbeafe;
  --color-primary-500: #2563eb;
  --color-primary-600: #1d4ed8;
  --color-primary-700: #1e40af;

  /* Success / Found Status — Emerald Green */
  --color-success-500: #10b981;
  --color-success-600: #059669;

  /* Warning / Lost Status — Amber Gold */
  --color-warning-500: #f59e0b;
  --color-warning-600: #d97706;

  /* Danger / Priority Document — Crimson Red */
  --color-danger-500: #ef4444;
  --color-danger-600: #dc2626;

  /* External Channel Brand Colors */
  --color-whatsapp:  #25d366;
  --color-instagram: #e4405f;

  /* Neutral Surface & Typography */
  --color-surface-bg:   #f8fafc;
  --color-surface-card: #ffffff;
  --color-text-main:    #0f172a;
  --color-text-muted:   #64748b;
  --color-border:       #e2e8f0;
}
```

### Penggunaan Warna per Konteks

| Elemen | Warna |
| :--- | :--- |
| Badge LOST | `--color-warning-500` (Amber) |
| Badge FOUND | `--color-primary-500` (Blue) |
| Badge RESOLVED | `--color-success-500` (Green) |
| Badge PRIORITY DOCUMENT | `--color-danger-500` (Red) |
| Tombol WhatsApp | `--color-whatsapp` (#25D366) |
| Tombol Instagram | `--color-instagram` (#E4405F) |
| Background halaman | `--color-surface-bg` |
| Card | `--color-surface-card` |

---

## 2. Typography

**Primary Font:** `'Plus Jakarta Sans', 'Inter', sans-serif`

| Level | Size | Weight | Line-height | Penggunaan |
| :--- | :--- | :--- | :--- | :--- |
| H1 | 36px (2.25rem) | 700 (Bold) | 1.2 | Hero / Page Title |
| H2 | 28px (1.75rem) | 600 (SemiBold) | 1.3 | Section Header |
| H3 | 20px (1.25rem) | 600 (SemiBold) | 1.4 | Card Title / Sub-section |
| Body | 16px (1.00rem) | 400 (Regular) | 1.5 | Teks konten utama |
| Small | 14px (0.875rem) | 500 (Medium) | 1.4 | Badge / Caption / Label |

---

## 3. Spacing (8px Grid System)

Semua spacing menggunakan kelipatan 8px:

| Token | Value | Tailwind |
| :--- | :--- | :--- |
| xs | 4px | `p-1` |
| sm | 8px | `p-2` |
| md | 16px | `p-4` |
| lg | 24px | `p-6` |
| xl | 32px | `p-8` |
| 2xl | 48px | `p-12` |
| 3xl | 64px | `p-16` |

**Container Max-Width:**
- Desktop: `1200px`
- Tablet: `768px`
- Mobile: `100%` dengan padding horizontal `16px`

---

## 4. Border Radius

| Token | Value | Penggunaan |
| :--- | :--- | :--- |
| `rounded-sm` | 8px | Tombol / Badge |
| `rounded-md` | 12px | Card / Input Field |
| `rounded-lg` | 16px | Modal / Bottom Sheet |
| `rounded-full` | 9999px | Avatar / Pill Tag |

---

## 5. Elevation (Shadow Scale)

| Level | CSS Shadow | Penggunaan |
| :--- | :--- | :--- |
| Low | `0 1px 3px rgba(0,0,0,.05), 0 1px 2px rgba(0,0,0,.03)` | Card default |
| Medium | `0 4px 6px -1px rgba(0,0,0,.08), 0 2px 4px -1px rgba(0,0,0,.04)` | Card hover / Dropdown |
| High | `0 20px 25px -5px rgba(0,0,0,.12), 0 10px 10px -5px rgba(0,0,0,.04)` | Modal / Floating Sheet |

---

## 6. Komponen Utama

### Item Card
- Thumbnail foto rasio **4:3**
- Badge tipe: `LOST` (Amber) atau `FOUND` (Blue)
- Badge prioritas: `DOKUMEN PENTING` (Red) jika `is_priority_document = true`
- Judul barang — font H3 bold
- Nama kecamatan di Pati — text muted
- Waktu relatif — "2 jam lalu"
- Tombol aksi cepat

### Status Badge

| Status | Background | Text | Border |
| :--- | :--- | :--- | :--- |
| LOST | `#FEF3C7` | `#92400E` | `#F59E0B` |
| FOUND | `#DBEAFE` | `#1E40AF` | `#2563EB` |
| CLAIMED | `#EDE9FE` | `#5B21B6` | `#7C3AED` |
| RESOLVED | `#D1FAE5` | `#065F46` | `#10B981` |
| CANCELLED | `#F1F5F9` | `#475569` | `#94A3B8` |

### Priority Document Badge
```css
background: #FEE2E2;
border: 1px solid #EF4444;
color: #991B1B;
border-radius: 9999px;
font-size: 12px;
font-weight: 600;
padding: 2px 8px;
```
Teks: **"DOKUMEN PENTING"**

### WhatsApp Button
```css
background: #25D366;
color: #ffffff;
border-radius: 8px;
padding: 12px 20px;
font-weight: 600;
```
Dengan icon WhatsApp, membuka: `https://wa.me/{phone}?text=Halo...`

### Instagram Button
```css
background: #E4405F;
color: #ffffff;
border-radius: 8px;
padding: 12px 20px;
font-weight: 600;
```
Dengan icon Instagram, membuka: `https://instagram.com/{handle}`

---

## 7. Struktur Komponen Vue

```
frontend/src/
├── components/
│   ├── common/
│   │   ├── BaseButton.vue          # Tombol (Primary, Outline, Danger)
│   │   ├── BaseInput.vue           # Input dengan label & error
│   │   ├── BaseSelect.vue          # Dropdown kategori & wilayah
│   │   ├── BaseModal.vue           # Modal dialog
│   │   ├── StatusBadge.vue         # Badge warna per status
│   │   └── PriorityBadge.vue       # Badge merah dokumen penting
│   ├── items/
│   │   ├── ItemCard.vue            # Card ringkasan di grid
│   │   ├── ItemGrid.vue            # Grid responsif ItemCard
│   │   ├── ItemFilterBar.vue       # Bar filter pencarian & kecamatan
│   │   ├── ItemImageUploader.vue   # Upload foto + preview
│   │   └── ItemLocationPicker.vue  # Picker peta Leaflet
│   ├── claims/
│   │   ├── ClaimFormModal.vue      # Form modal ajukan klaim
│   │   └── ClaimReviewCard.vue     # Card approve/reject klaim
│   └── handoff/
│       ├── WhatsAppDirectBtn.vue   # Tombol deep link WA
│       └── InstagramDirectBtn.vue  # Tombol redirect IG
│
├── layouts/
│   ├── PublicLayout.vue            # Navbar publik + Footer
│   ├── AppLayout.vue               # Sidebar user + Header notifikasi
│   └── AdminLayout.vue             # Sidebar admin
│
└── views/
    ├── HomeView.vue
    ├── ExploreView.vue
    ├── ItemDetailView.vue
    ├── ReportItemView.vue
    ├── MyReportsView.vue
    ├── MyClaimsView.vue
    ├── IncomingClaimsView.vue
    ├── NotificationsView.vue
    ├── ProfileView.vue
    ├── DashboardView.vue
    └── admin/
        ├── AdminDashboardView.vue
        ├── AdminItemsView.vue
        ├── AdminClaimsView.vue
        ├── AdminCategoriesView.vue
        └── AdminUsersView.vue
```

---

## 8. Pinia State Management

| Store | Data yang Dikelola |
| :--- | :--- |
| `useAuthStore` | Status login, data user, token Sanctum, auto-logout |
| `useItemStore` | Cache daftar barang, filter aktif, pagination, CRUD laporan |
| `useClaimStore` | Status klaim aktif, validasi bukti, info kontak approved |
| `useNotificationStore` | Daftar notifikasi, jumlah unread |

---

## 9. UI Do's and Don'ts

**DO:**
- Tampilkan foto barang dengan rasio 4:3 dan kompresi otomatis (hemat kuota)
- Wajibkan konfirmasi kepemilikan sebelum membuka kontak WA/IG
- Gunakan step indicator pada form panjang di mobile
- Masking data sensitif (nomor HP, NIK di foto KTP) di halaman publik

**DON'T:**
- Jangan tampilkan nomor kontak terbuka di antarmuka publik
- Jangan buat form panjang dalam 1 layar tanpa progress step
- Jangan tampilkan `secret_details` item di halaman publik

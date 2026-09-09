<script setup>
const problems = [
  {
    icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    title: 'Tidak Ada Wadah Pelaporan',
    desc: 'Warga kesulitan melaporkan barang temuan atau kehilangan tanpa platform yang terpusat dan mudah diakses.',
  },
  {
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    title: 'Proses Serah Terima Tidak Aman',
    desc: 'Tanpa verifikasi identitas yang jelas, proses pengembalian barang berisiko tinggi dan rawan penyalahgunaan.',
  },
  {
    icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7',
    title: 'Informasi Tidak Tersebar Luas',
    desc: 'Laporan kehilangan hanya beredar di grup WhatsApp atau media sosial tanpa koordinasi yang baik antar kecamatan.',
  },
]

const steps = [
  { num: '1', title: 'Temukan atau Kehilangan', desc: 'Menemukan barang orang lain atau kehilangan barang berharga Anda.' },
  { num: '2', title: 'Buat Laporan', desc: 'Isi formulir laporan dengan detail lengkap dan foto barang.' },
  { num: '3', title: 'Verifikasi', desc: 'Admin memverifikasi laporan dan pemilik membuktikan kepemilikan.' },
  { num: '4', title: 'Serah Terima', desc: 'Barang diserahkan kembali ke pemilik sah dengan aman dan tercatat.' },
]

const team = [
  { name: 'Muhammad Alqaus Sigit Widodo', role: 'Project Lead & Backend', initials: 'MA' },
  { name: 'Muhammad Roid Falih', role: 'Backend Developer', initials: 'MR' },
  { name: 'Nabila Aufa Bilqis Ardiyani', role: 'UI/UX Designer', initials: 'NA' },
  { name: 'Sekar Dwi Wulandari', role: 'Frontend Developer', initials: 'SD' },
  { name: 'Shaza Nurmalak', role: 'Frontend Developer', initials: 'SN' },
]

const avatarColors = ['bg-primary-100 text-primary-700', 'bg-blue-100 text-blue-700', 'bg-pink-100 text-pink-700', 'bg-purple-100 text-purple-700', 'bg-green-100 text-green-700']
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-primary-50 to-white border-b border-border">
      <div class="max-w-4xl mx-auto px-4 py-20 text-center">
        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-semibold px-3 py-1 rounded-full mb-4">Tentang Kami</span>
        <h1 class="text-4xl md:text-5xl font-bold text-text-main mb-5">Tentang Find &amp; Found</h1>
        <p class="text-lg text-text-muted max-w-2xl mx-auto leading-relaxed">
          Find &amp; Found adalah platform digital yang menghubungkan warga Kabupaten Pati untuk melaporkan dan menemukan kembali barang hilang secara aman, cepat, dan terverifikasi.
        </p>
      </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 py-16">
      <h2 class="text-2xl font-bold text-text-main text-center mb-3">Masalah yang Kami Selesaikan</h2>
      <p class="text-text-muted text-center mb-10">Tantangan nyata yang dihadapi warga setiap hari</p>
      <div class="grid md:grid-cols-3 gap-6">
        <div v-for="(p, i) in problems" :key="i" class="bg-white border border-border rounded-xl p-6 hover:shadow-md transition-shadow">
          <div class="w-12 h-12 bg-danger-50 rounded-xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="p.icon"/>
            </svg>
          </div>
          <h3 class="font-semibold text-text-main mb-2">{{ p.title }}</h3>
          <p class="text-sm text-text-muted leading-relaxed">{{ p.desc }}</p>
        </div>
      </div>
    </section>

    <section class="bg-surface-bg border-y border-border py-16">
      <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-text-main text-center mb-3">Cara Kerja</h2>
        <p class="text-text-muted text-center mb-12">Proses sederhana, hasil yang nyata</p>
        <div class="grid md:grid-cols-4 gap-6 relative">
          <div class="hidden md:block absolute top-10 left-1/4 right-1/4 h-0.5 bg-primary-200 -z-0"></div>
          <div v-for="(step, i) in steps" :key="i" class="relative text-center">
            <div class="w-20 h-20 rounded-full bg-primary-600 text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg shadow-primary-200 relative z-10">
              {{ step.num }}
            </div>
            <div v-if="i < steps.length - 1" class="hidden md:block absolute top-10 -right-3 z-20 text-primary-300">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <h3 class="font-semibold text-text-main mb-2">{{ step.title }}</h3>
            <p class="text-sm text-text-muted leading-relaxed">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 py-16">
      <h2 class="text-2xl font-bold text-text-main text-center mb-3">Tim Pengembang</h2>
      <p class="text-text-muted text-center mb-10">Siswa XII TJKT 1 yang membangun platform ini</p>
      <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        <div v-for="(member, i) in team" :key="i" class="bg-white border border-border rounded-xl p-5 text-center hover:shadow-md transition-shadow">
          <div :class="['w-16 h-16 rounded-full flex items-center justify-center text-xl font-bold mx-auto mb-3', avatarColors[i]]">
            {{ member.initials }}
          </div>
          <p class="font-semibold text-text-main text-sm leading-snug">{{ member.name }}</p>
          <p class="text-xs text-text-muted mt-1">{{ member.role }}</p>
        </div>
      </div>
    </section>

    <section class="bg-primary-50 border-t border-border">
      <div class="max-w-3xl mx-auto px-4 py-12 text-center">
        <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 rounded-full px-4 py-1.5 text-sm font-medium mb-4">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Lokasi
        </div>
        <h2 class="text-xl font-bold text-text-main mb-2">Kabupaten Pati, Jawa Tengah</h2>
        <p class="text-text-muted text-sm">Platform ini melayani seluruh wilayah Kabupaten Pati dan sekitarnya. Dikembangkan sebagai proyek akhir siswa XII TJKT 1.</p>
      </div>
    </section>
  </div>
</template>

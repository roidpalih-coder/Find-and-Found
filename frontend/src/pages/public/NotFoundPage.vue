<script setup>
import { RouterLink } from 'vue-router'
import BaseButton from '@/components/base/BaseButton.vue'
</script>

<template>
  <div class="min-h-[70vh] flex flex-col items-center justify-center px-4 text-center">
    <p class="text-8xl md:text-9xl font-black text-primary-600 leading-none select-none">404</p>
    <h1 class="text-2xl md:text-3xl font-bold text-text-main mt-4 mb-2">Halaman Tidak Ditemukan</h1>
    <p class="text-text-muted max-w-sm mb-8">Halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
    <RouterLink to="/">
      <BaseButton variant="primary" size="lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Kembali ke Beranda
      </BaseButton>
    </RouterLink>
  </div>
</template>

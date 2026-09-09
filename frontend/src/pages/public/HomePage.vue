<script setup>
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useItemStore } from '@/stores/items'
import ItemCard from '@/components/base/ItemCard.vue'
import BaseButton from '@/components/base/BaseButton.vue'

const itemStore = useItemStore()

onMounted(() => {
  itemStore.fetchItems({ per_page: 8 })
})
</script>

<template>
  <div>
    <section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 text-white overflow-hidden">
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-primary-300 rounded-full blur-3xl"></div>
      </div>
      <div class="relative max-w-5xl mx-auto px-4 py-24 text-center">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-1.5 text-sm font-medium mb-6">
          <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
          Platform Kehilangan & Temuan Kabupaten Pati
        </div>
        <h1 class="text-4xl md:text-6xl font-bold leading-tight tracking-tight mb-6">
          Temukan yang Hilang,<br />
          <span class="text-primary-200">Kembalikan dengan Tenang</span>
        </h1>
        <p class="text-lg md:text-xl text-primary-100 max-w-2xl mx-auto mb-10 leading-relaxed">
          Platform digital untuk melaporkan dan menemukan barang hilang di Kabupaten Pati. Terhubung dengan komunitas dan kembalikan barang ke pemiliknya.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
          <RouterLink to="/report/found">
            <BaseButton size="lg" variant="secondary">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Lapor Barang Temuan
            </BaseButton>
          </RouterLink>
          <RouterLink to="/explore">
            <BaseButton size="lg" variant="ghost" class="text-white hover:bg-white/10 border border-white/30">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              Jelajahi Laporan
            </BaseButton>
          </RouterLink>
        </div>
      </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 -mt-8 relative z-10">
      <div class="bg-white rounded-2xl shadow-lg border border-border grid grid-cols-3 divide-x divide-border">
        <div class="py-8 px-6 text-center">
          <p class="text-3xl font-bold text-primary-600">1.200+</p>
          <p class="text-sm text-text-muted mt-1 font-medium">Total Laporan</p>
        </div>
        <div class="py-8 px-6 text-center">
          <p class="text-3xl font-bold text-success-600">850+</p>
          <p class="text-sm text-text-muted mt-1 font-medium">Barang Kembali</p>
        </div>
        <div class="py-8 px-6 text-center">
          <p class="text-3xl font-bold text-primary-600">15</p>
          <p class="text-sm text-text-muted mt-1 font-medium">Kecamatan Terjangkau</p>
        </div>
      </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-16">
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-2xl font-bold text-text-main">Laporan Terbaru</h2>
          <p class="text-text-muted text-sm mt-1">Barang hilang dan temuan yang baru dilaporkan</p>
        </div>
        <RouterLink to="/explore" class="text-primary-600 text-sm font-semibold hover:underline">Lihat Semua →</RouterLink>
      </div>

      <div v-if="itemStore.loading" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="n in 4" :key="n" class="animate-pulse bg-gray-200 rounded-xl aspect-[4/3]"></div>
      </div>

      <div v-else-if="itemStore.items.length === 0" class="text-center py-16">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        <p class="text-text-muted font-medium">Belum ada laporan</p>
        <p class="text-text-muted text-sm mt-1">Jadilah yang pertama melaporkan!</p>
      </div>

      <div v-else class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <ItemCard v-for="item in itemStore.items" :key="item.id" :item="item" />
      </div>
    </section>

    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
      <div class="max-w-4xl mx-auto px-4 py-16 text-center">
        <h2 class="text-3xl font-bold mb-3">Mulai Sekarang</h2>
        <p class="text-primary-100 text-lg mb-8">Bantu sesama warga Pati menemukan barang mereka yang hilang</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
          <RouterLink to="/report/found">
            <BaseButton size="lg" variant="secondary">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Lapor Temuan
            </BaseButton>
          </RouterLink>
          <RouterLink to="/report/lost">
            <BaseButton size="lg" variant="ghost" class="text-white hover:bg-white/10 border border-white/30">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              Lapor Kehilangan
            </BaseButton>
          </RouterLink>
        </div>
      </div>
    </section>
  </div>
</template>

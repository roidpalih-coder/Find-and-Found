<script setup>
import { computed, onMounted } from 'vue'
import { useItemStore } from '@/stores/items'
import ItemCard from '@/components/base/ItemCard.vue'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BasePagination from '@/components/base/BasePagination.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import { ref } from 'vue'

const itemStore = useItemStore()

const items = computed(() => itemStore.items)
const meta = computed(() => itemStore.meta)
const loading = computed(() => itemStore.loading)

const currentPage = ref(1)

async function fetchItems() {
  await itemStore.fetchItems({ is_priority_document: true, status: 'active', page: currentPage.value })
}

function onPageChange(page) {
  currentPage.value = page
  fetchItems()
}

onMounted(fetchItems)
</script>

<template>
  <div class="min-h-screen bg-surface-bg">
    <div class="bg-danger-500 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center gap-4 mb-4">
          <div class="p-3 bg-white/20 rounded-xl">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-bold">Dokumen Prioritas</h1>
            <p class="text-white/80 mt-1">Dokumen penting yang membutuhkan penanganan segera</p>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-danger-500/10 border border-danger-500/30 rounded-xl p-5 mb-8">
        <h2 class="text-sm font-semibold text-danger-600 mb-2">Dokumen yang Termasuk Prioritas:</h2>
        <div class="flex flex-wrap gap-2">
          <span v-for="doc in ['KTP', 'SIM', 'Paspor', 'Kartu Pelajar', 'Kartu BPJS', 'Ijazah', 'Akta Kelahiran', 'STNK', 'BPKB']" :key="doc"
            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-danger-500/10 text-danger-700 border border-danger-500/20"
          >
            {{ doc }}
          </span>
        </div>
        <p class="text-xs text-danger-600 mt-3">
          Jika Anda menemukan dokumen milik orang lain, segera laporkan agar pemilik segera dihubungi.
        </p>
      </div>

      <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <div v-for="n in 8" :key="n" class="bg-white rounded-xl border border-border overflow-hidden animate-pulse">
          <div class="aspect-[4/3] bg-gray-200" />
          <div class="p-4 space-y-2">
            <div class="h-4 bg-gray-200 rounded w-3/4" />
            <div class="h-3 bg-gray-200 rounded w-1/2" />
          </div>
        </div>
      </div>

      <div v-else-if="items.length === 0" class="flex flex-col items-center justify-center py-20 text-text-muted">
        <svg class="w-16 h-16 opacity-30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="text-lg font-medium">Tidak ada dokumen prioritas saat ini</p>
        <p class="text-sm mt-1">Semua dokumen penting sudah ditemukan pemiliknya</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <ItemCard v-for="item in items" :key="item.id" :item="item" />
      </div>

      <div class="mt-8">
        <BasePagination
          v-if="meta.last_page > 1"
          :current-page="meta.current_page || 1"
          :last-page="meta.last_page || 1"
          @change="onPageChange"
        />
      </div>
    </div>
  </div>
</template>

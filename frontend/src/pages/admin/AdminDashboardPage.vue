<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { adminService } from '@/services/endpoints'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseBadge from '@/components/base/BaseBadge.vue'

const stats = ref(null)
const recentItems = ref([])
const loading = ref(true)

function formatDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

onMounted(async () => {
  try {
    const [statsRes, itemsRes] = await Promise.all([
      adminService.stats(),
      adminService.listItems({ per_page: 5, page: 1 }),
    ])
    stats.value = statsRes.data.data || statsRes.data
    recentItems.value = itemsRes.data.data || []
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
    <h1 class="text-2xl font-bold text-text-main">Dashboard Admin</h1>

    <div v-if="loading" class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div v-for="i in 4" :key="i" class="h-24 bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else-if="stats" class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <BaseCard padding="p-5">
        <p class="text-xs text-text-muted">Total Laporan</p>
        <p class="mt-1 text-3xl font-bold text-text-main">{{ stats.total_items ?? 0 }}</p>
      </BaseCard>
      <BaseCard padding="p-5">
        <p class="text-xs text-text-muted">Total Pengguna</p>
        <p class="mt-1 text-3xl font-bold text-text-main">{{ stats.total_users ?? 0 }}</p>
      </BaseCard>
      <BaseCard padding="p-5">
        <p class="text-xs text-text-muted">Laporan Aktif</p>
        <p class="mt-1 text-3xl font-bold text-primary-600">{{ stats.active_items ?? 0 }}</p>
      </BaseCard>
      <BaseCard padding="p-5">
        <p class="text-xs text-text-muted">Klaim Pending</p>
        <p class="mt-1 text-3xl font-bold text-warning-600">{{ stats.pending_claims ?? 0 }}</p>
      </BaseCard>
    </div>

    <BaseCard>
      <h2 class="text-lg font-semibold text-text-main mb-4">Laporan Terbaru</h2>
      <div v-if="loading" class="space-y-3">
        <div v-for="i in 5" :key="i" class="h-10 bg-surface-bg rounded animate-pulse" />
      </div>
      <table v-else class="w-full text-sm">
        <thead>
          <tr class="text-left border-b border-border">
            <th class="pb-2 font-medium text-text-muted">ID</th>
            <th class="pb-2 font-medium text-text-muted">Judul</th>
            <th class="pb-2 font-medium text-text-muted">Tipe</th>
            <th class="pb-2 font-medium text-text-muted">Status</th>
            <th class="pb-2 font-medium text-text-muted">Tanggal</th>
            <th class="pb-2 font-medium text-text-muted">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in recentItems" :key="item.id" class="border-b border-border last:border-0">
            <td class="py-3 text-text-muted">#{{ item.id }}</td>
            <td class="py-3 font-medium text-text-main max-w-[160px] truncate">{{ item.title }}</td>
            <td class="py-3"><BaseBadge :type="item.type" /></td>
            <td class="py-3"><BaseBadge :type="item.status" /></td>
            <td class="py-3 text-text-muted">{{ formatDate(item.created_at) }}</td>
            <td class="py-3">
              <RouterLink :to="`/items/${item.id}`" class="text-primary-600 hover:underline font-medium">Lihat</RouterLink>
            </td>
          </tr>
        </tbody>
      </table>
    </BaseCard>
  </div>
</template>

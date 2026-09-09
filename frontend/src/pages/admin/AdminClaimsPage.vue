<script setup>
import { ref, onMounted, watch } from 'vue'
import { adminService } from '@/services/endpoints'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BasePagination from '@/components/base/BasePagination.vue'
import BaseCard from '@/components/base/BaseCard.vue'

const claims = ref([])
const meta = ref({})
const loading = ref(false)
const activeTab = ref('all')
const currentPage = ref(1)

const tabs = [
  { key: 'all', label: 'Semua' },
  { key: 'pending', label: 'Menunggu' },
  { key: 'approved', label: 'Disetujui' },
  { key: 'rejected', label: 'Ditolak' },
]

function formatDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

async function load() {
  loading.value = true
  try {
    const params = { page: currentPage.value, per_page: 15 }
    if (activeTab.value !== 'all') params.status = activeTab.value
    const { data } = await adminService.listClaims(params)
    claims.value = data.data || []
    meta.value = data.meta || {}
  } finally {
    loading.value = false
  }
}

watch([activeTab, currentPage], load)
onMounted(load)
</script>

<template>
  <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
    <h1 class="text-2xl font-bold text-text-main">Kelola Klaim</h1>

    <div class="flex gap-1 p-1 bg-surface-bg rounded-xl w-fit">
      <button
        v-for="tab in tabs"
        :key="tab.key"
        :class="[
          'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
          activeTab === tab.key
            ? 'bg-white text-text-main shadow-sm'
            : 'text-text-muted hover:text-text-main'
        ]"
        @click="activeTab = tab.key; currentPage = 1"
      >
        {{ tab.label }}
      </button>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 8" :key="i" class="h-14 bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else-if="claims.length === 0" class="text-center py-20 text-text-muted">
      Tidak ada klaim ditemukan.
    </div>

    <BaseCard v-else padding="p-0">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="text-left border-b border-border bg-surface-bg">
              <th class="py-3 px-4 font-medium text-text-muted">ID</th>
              <th class="py-3 px-4 font-medium text-text-muted">Barang</th>
              <th class="py-3 px-4 font-medium text-text-muted">Pengaju</th>
              <th class="py-3 px-4 font-medium text-text-muted">Status</th>
              <th class="py-3 px-4 font-medium text-text-muted">Tanggal</th>
              <th class="py-3 px-4 font-medium text-text-muted">Catatan</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="claim in claims" :key="claim.id" class="border-b border-border last:border-0 hover:bg-surface-bg/50 transition-colors">
              <td class="py-3 px-4 text-text-muted">#{{ claim.id }}</td>
              <td class="py-3 px-4 font-medium text-text-main max-w-[200px] truncate">
                {{ claim.item?.title || '-' }}
              </td>
              <td class="py-3 px-4 text-text-muted">
                {{ claim.claimant?.name || '-' }}
              </td>
              <td class="py-3 px-4">
                <BaseBadge :type="claim.status" />
              </td>
              <td class="py-3 px-4 text-text-muted">
                {{ formatDate(claim.created_at) }}
              </td>
              <td class="py-3 px-4 text-text-muted max-w-[250px] truncate">
                {{ claim.notes || claim.description || '-' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>

    <BasePagination
      v-if="meta?.last_page > 1"
      :current-page="currentPage"
      :last-page="meta.last_page"
      @change="p => { currentPage = p }"
    />
  </div>
</template>

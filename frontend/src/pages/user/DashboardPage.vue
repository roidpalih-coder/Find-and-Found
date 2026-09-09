<script setup>
import { ref, computed, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useItemStore } from '@/stores/items'
import { useClaimStore } from '@/stores/claims'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BaseButton from '@/components/base/BaseButton.vue'

const authStore = useAuthStore()
const itemStore = useItemStore()
const claimStore = useClaimStore()

const loading = ref(true)

const activeReports = computed(() =>
  itemStore.myReports.filter(i => i.status === 'active').length
)
const claimsSubmitted = computed(() =>
  claimStore.myClaims.length
)
const claimsIncomingPending = computed(() =>
  claimStore.incoming.filter(c => c.status === 'pending').length
)

function formatDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

onMounted(async () => {
  await Promise.all([
    itemStore.fetchMyReports({ per_page: 3 }),
    claimStore.fetchMyClaims({ per_page: 3 }),
    claimStore.fetchIncoming({ per_page: 3 }),
  ])
  loading.value = false
})
</script>

<template>
  <div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
    <h1 class="text-2xl font-bold text-text-main">
      Selamat datang, {{ authStore.user?.name }}!
    </h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <BaseCard padding="p-5">
        <p class="text-sm text-text-muted">Laporan Aktif</p>
        <p class="mt-1 text-3xl font-bold text-primary-600">{{ activeReports }}</p>
      </BaseCard>
      <BaseCard padding="p-5">
        <p class="text-sm text-text-muted">Klaim Diajukan</p>
        <p class="mt-1 text-3xl font-bold text-primary-600">{{ claimsSubmitted }}</p>
      </BaseCard>
      <BaseCard padding="p-5">
        <p class="text-sm text-text-muted">Klaim Masuk Pending</p>
        <p class="mt-1 text-3xl font-bold text-warning-600">{{ claimsIncomingPending }}</p>
      </BaseCard>
    </div>

    <div class="flex gap-3">
      <RouterLink to="/report/found">
        <BaseButton variant="primary">Lapor Barang Temuan</BaseButton>
      </RouterLink>
      <RouterLink to="/report/lost">
        <BaseButton variant="secondary">Lapor Barang Hilang</BaseButton>
      </RouterLink>
    </div>

    <BaseCard>
      <h2 class="text-lg font-semibold text-text-main mb-4">Laporan Saya Terbaru</h2>
      <div v-if="loading" class="space-y-3">
        <div v-for="i in 3" :key="i" class="h-10 bg-surface-bg rounded animate-pulse" />
      </div>
      <div v-else-if="itemStore.myReports.length === 0" class="text-center py-8 text-text-muted">
        Belum ada laporan.
      </div>
      <table v-else class="w-full text-sm">
        <thead>
          <tr class="text-left border-b border-border">
            <th class="pb-2 font-medium text-text-muted">Judul</th>
            <th class="pb-2 font-medium text-text-muted">Tipe</th>
            <th class="pb-2 font-medium text-text-muted">Status</th>
            <th class="pb-2 font-medium text-text-muted">Tanggal</th>
            <th class="pb-2 font-medium text-text-muted">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in itemStore.myReports" :key="item.id" class="border-b border-border last:border-0">
            <td class="py-3 font-medium text-text-main max-w-[200px] truncate">{{ item.title }}</td>
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

    <BaseCard v-if="claimStore.incoming.length > 0">
      <h2 class="text-lg font-semibold text-text-main mb-4">Klaim Masuk Terbaru</h2>
      <div class="space-y-3">
        <div
          v-for="claim in claimStore.incoming"
          :key="claim.id"
          class="flex items-center justify-between p-3 rounded-lg border border-border bg-surface-bg"
        >
          <div>
            <p class="font-medium text-text-main text-sm">{{ claim.item?.title }}</p>
            <p class="text-xs text-text-muted mt-0.5">{{ claim.claimant?.name }} · {{ formatDate(claim.created_at) }}</p>
          </div>
          <BaseBadge :type="claim.status" />
        </div>
      </div>
    </BaseCard>
  </div>
</template>

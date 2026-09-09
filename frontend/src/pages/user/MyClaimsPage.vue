<script setup>
import { ref, onMounted, watch } from 'vue'
import { useClaimStore } from '@/stores/claims'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseCard from '@/components/base/BaseCard.vue'

const claimStore = useClaimStore()
const activeTab = ref('all')

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

function whatsappLink(phone) {
  const clean = phone?.replace(/\D/g, '')
  return `https://wa.me/${clean}`
}

function instagramLink(handle) {
  return `https://instagram.com/${handle?.replace('@', '')}`
}

async function load() {
  const params = {}
  if (activeTab.value !== 'all') params.status = activeTab.value
  await claimStore.fetchMyClaims(params)
}

watch(activeTab, load)
onMounted(load)
</script>

<template>
  <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">
    <h1 class="text-2xl font-bold text-text-main">Klaim Saya</h1>

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
        @click="activeTab = tab.key"
      >
        {{ tab.label }}
      </button>
    </div>

    <div v-if="claimStore.loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-24 bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else-if="claimStore.myClaims.length === 0" class="text-center py-20 text-text-muted">
      <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
      </svg>
      <p>Belum ada klaim.</p>
    </div>

    <div v-else class="space-y-3">
      <BaseCard v-for="claim in claimStore.myClaims" :key="claim.id" padding="p-4">
        <div class="flex items-start gap-4">
          <img
            v-if="claim.item?.primary_photo_url"
            :src="claim.item.primary_photo_url"
            :alt="claim.item.title"
            class="w-16 h-16 object-cover rounded-lg flex-shrink-0"
          />
          <div v-else class="w-16 h-16 bg-surface-bg rounded-lg flex-shrink-0" />
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <p class="font-semibold text-text-main text-sm line-clamp-2">{{ claim.item?.title }}</p>
              <BaseBadge :type="claim.status" class="flex-shrink-0" />
            </div>
            <p class="text-xs text-text-muted mt-1">{{ formatDate(claim.created_at) }}</p>
            <p v-if="claim.notes" class="text-sm text-text-muted mt-2 line-clamp-2">{{ claim.notes }}</p>
            <div v-if="claim.status === 'approved'" class="flex gap-2 mt-3">
              <a
                v-if="claim.item?.owner?.phone_number"
                :href="whatsappLink(claim.item.owner.phone_number)"
                target="_blank"
                rel="noopener noreferrer"
              >
                <BaseButton variant="whatsapp" size="sm">WhatsApp</BaseButton>
              </a>
              <a
                v-if="claim.item?.owner?.instagram_handle"
                :href="instagramLink(claim.item.owner.instagram_handle)"
                target="_blank"
                rel="noopener noreferrer"
              >
                <BaseButton variant="instagram" size="sm">Instagram</BaseButton>
              </a>
            </div>
          </div>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useClaimStore } from '@/stores/claims'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseModal from '@/components/base/BaseModal.vue'
import BaseCard from '@/components/base/BaseCard.vue'
import BaseAvatar from '@/components/base/BaseAvatar.vue'
import BasePagination from '@/components/base/BasePagination.vue'

const claimStore = useClaimStore()

const activeTab = ref('all')
const currentPage = ref(1)
const showModal = ref(false)
const modalMode = ref('approve')
const selectedClaim = ref(null)
const responseNotes = ref('')
const actionLoading = ref(false)

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
  const params = { page: currentPage.value, per_page: 10 }
  if (activeTab.value !== 'all') params.status = activeTab.value
  await claimStore.fetchIncoming(params)
}

function openApprove(claim) {
  selectedClaim.value = claim
  modalMode.value = 'approve'
  responseNotes.value = ''
  showModal.value = true
}

function openReject(claim) {
  selectedClaim.value = claim
  modalMode.value = 'reject'
  responseNotes.value = ''
  showModal.value = true
}

async function confirmAction() {
  if (!selectedClaim.value) return
  actionLoading.value = true
  try {
    if (modalMode.value === 'approve') {
      await claimStore.approveClaim(selectedClaim.value.id, { response_notes: responseNotes.value })
    } else {
      await claimStore.rejectClaim(selectedClaim.value.id, { response_notes: responseNotes.value })
    }
    showModal.value = false
    await load()
  } finally {
    actionLoading.value = false
  }
}

watch([activeTab, currentPage], load)
onMounted(load)
</script>

<template>
  <div class="max-w-3xl mx-auto px-4 py-8 space-y-6">
    <h1 class="text-2xl font-bold text-text-main">Klaim Masuk</h1>

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

    <div v-if="claimStore.loading" class="space-y-3">
      <div v-for="i in 4" :key="i" class="h-28 bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else-if="claimStore.incoming.length === 0" class="text-center py-20 text-text-muted">
      <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
      </svg>
      <p>Belum ada klaim masuk.</p>
    </div>

    <div v-else class="space-y-3">
      <BaseCard v-for="claim in claimStore.incoming" :key="claim.id" padding="p-4">
        <div class="flex items-start gap-4">
          <img
            v-if="claim.proof_photo_url"
            :src="claim.proof_photo_url"
            :alt="'Bukti klaim'"
            class="w-16 h-16 object-cover rounded-lg flex-shrink-0"
          />
          <div class="flex-1 min-w-0 space-y-2">
            <div class="flex items-center gap-3">
              <BaseAvatar :src="claim.claimant?.avatar_url" :name="claim.claimant?.name" size="sm" />
              <div>
                <p class="font-semibold text-text-main text-sm">{{ claim.claimant?.name }}</p>
                <p class="text-xs text-text-muted">{{ formatDate(claim.created_at) }}</p>
              </div>
              <BaseBadge :type="claim.status" class="ml-auto" />
            </div>
            <p v-if="claim.description" class="text-sm text-text-muted line-clamp-3">{{ claim.description }}</p>
            <div v-if="claim.status === 'pending'" class="flex gap-2 pt-1">
              <BaseButton variant="primary" size="sm" @click="openApprove(claim)">Setujui</BaseButton>
              <BaseButton variant="danger" size="sm" @click="openReject(claim)">Tolak</BaseButton>
            </div>
          </div>
        </div>
      </BaseCard>
    </div>

    <BasePagination
      v-if="claimStore.meta?.last_page > 1"
      :current-page="currentPage"
      :last-page="claimStore.meta.last_page"
      @change="p => { currentPage = p }"
    />

    <BaseModal
      :show="showModal"
      :title="modalMode === 'approve' ? 'Setujui Klaim' : 'Tolak Klaim'"
      @close="showModal = false"
    >
      <div class="space-y-3">
        <p class="text-sm text-text-muted">
          {{ modalMode === 'approve' ? 'Anda akan menyetujui klaim ini.' : 'Anda akan menolak klaim ini.' }}
          Tambahkan catatan respons (opsional):
        </p>
        <textarea
          v-model="responseNotes"
          rows="4"
          placeholder="Catatan respons..."
          class="w-full rounded-lg border border-border px-3 py-2.5 text-sm text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none"
        />
      </div>
      <template #footer>
        <div class="flex justify-end gap-3">
          <BaseButton variant="secondary" @click="showModal = false">Batal</BaseButton>
          <BaseButton
            :variant="modalMode === 'approve' ? 'primary' : 'danger'"
            :loading="actionLoading"
            @click="confirmAction"
          >
            {{ modalMode === 'approve' ? 'Setujui' : 'Tolak' }}
          </BaseButton>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

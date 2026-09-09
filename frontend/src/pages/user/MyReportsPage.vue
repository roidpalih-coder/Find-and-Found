<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { RouterLink } from 'vue-router'
import { useItemStore } from '@/stores/items'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseModal from '@/components/base/BaseModal.vue'
import BasePagination from '@/components/base/BasePagination.vue'
import ItemCard from '@/components/base/ItemCard.vue'

const itemStore = useItemStore()

const activeTab = ref('all')
const currentPage = ref(1)
const showDropdown = ref(false)
const showCloseModal = ref(false)
const showDeleteModal = ref(false)
const selectedItem = ref(null)
const actionLoading = ref(false)

const tabs = [
  { key: 'all', label: 'Semua' },
  { key: 'active', label: 'Aktif' },
  { key: 'resolved', label: 'Selesai' },
  { key: 'closed', label: 'Ditutup' },
]

async function load() {
  const params = { page: currentPage.value, per_page: 12 }
  if (activeTab.value !== 'all') params.status = activeTab.value
  await itemStore.fetchMyReports(params)
}

function setTab(key) {
  activeTab.value = key
  currentPage.value = 1
}

function openCloseModal(item) {
  selectedItem.value = item
  showCloseModal.value = true
}

function openDeleteModal(item) {
  selectedItem.value = item
  showDeleteModal.value = true
}

async function confirmClose() {
  if (!selectedItem.value) return
  actionLoading.value = true
  try {
    await itemStore.closeItem(selectedItem.value.id)
    showCloseModal.value = false
    await load()
  } finally {
    actionLoading.value = false
  }
}

async function confirmDelete() {
  if (!selectedItem.value) return
  actionLoading.value = true
  try {
    await itemStore.deleteItem(selectedItem.value.id)
    showDeleteModal.value = false
    await load()
  } finally {
    actionLoading.value = false
  }
}

watch([activeTab, currentPage], load)
onMounted(load)
</script>

<template>
  <div class="max-w-5xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-center justify-between gap-4">
      <h1 class="text-2xl font-bold text-text-main">Laporan Saya</h1>
      <div class="relative">
        <BaseButton variant="primary" @click="showDropdown = !showDropdown">
          + Buat Laporan Baru
        </BaseButton>
        <div
          v-if="showDropdown"
          class="absolute right-0 mt-2 w-48 bg-white rounded-xl border border-border shadow-lg z-10"
          @mouseleave="showDropdown = false"
        >
          <RouterLink
            to="/report/found"
            class="block px-4 py-3 text-sm text-text-main hover:bg-surface-bg rounded-t-xl transition-colors"
            @click="showDropdown = false"
          >
            Barang Temuan
          </RouterLink>
          <RouterLink
            to="/report/lost"
            class="block px-4 py-3 text-sm text-text-main hover:bg-surface-bg rounded-b-xl transition-colors"
            @click="showDropdown = false"
          >
            Barang Hilang
          </RouterLink>
        </div>
      </div>
    </div>

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
        @click="setTab(tab.key)"
      >
        {{ tab.label }}
      </button>
    </div>

    <div v-if="itemStore.loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      <div v-for="i in 6" :key="i" class="aspect-[4/3] bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else-if="itemStore.myReports.length === 0" class="text-center py-20 text-text-muted">
      <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <p>Belum ada laporan.</p>
    </div>

    <div v-else class="space-y-3">
      <div
        v-for="item in itemStore.myReports"
        :key="item.id"
        class="flex items-center gap-4 p-4 bg-white rounded-xl border border-border shadow-sm"
      >
        <img
          v-if="item.primary_photo_url"
          :src="item.primary_photo_url"
          :alt="item.title"
          class="w-16 h-16 object-cover rounded-lg flex-shrink-0"
        />
        <div v-else class="w-16 h-16 bg-surface-bg rounded-lg flex-shrink-0 flex items-center justify-center">
          <svg class="w-6 h-6 text-text-muted opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01" />
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="font-semibold text-text-main text-sm truncate">{{ item.title }}</p>
          <div class="flex items-center gap-2 mt-1">
            <BaseBadge :type="item.type" />
            <BaseBadge :type="item.status" />
          </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
          <RouterLink :to="`/my-reports/${item.id}/edit`">
            <BaseButton variant="secondary" size="sm">Edit</BaseButton>
          </RouterLink>
          <BaseButton
            v-if="item.status === 'active'"
            variant="ghost"
            size="sm"
            @click="openCloseModal(item)"
          >
            Tutup
          </BaseButton>
          <BaseButton variant="danger" size="sm" @click="openDeleteModal(item)">Hapus</BaseButton>
        </div>
      </div>
    </div>

    <BasePagination
      v-if="itemStore.meta?.last_page > 1"
      :current-page="currentPage"
      :last-page="itemStore.meta.last_page"
      @change="p => { currentPage = p }"
    />

    <BaseModal :show="showCloseModal" title="Tutup Laporan" @close="showCloseModal = false">
      <p class="text-text-muted text-sm">Apakah Anda yakin ingin menutup laporan <strong>{{ selectedItem?.title }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
      <template #footer>
        <div class="flex justify-end gap-3">
          <BaseButton variant="secondary" @click="showCloseModal = false">Batal</BaseButton>
          <BaseButton variant="danger" :loading="actionLoading" @click="confirmClose">Tutup Laporan</BaseButton>
        </div>
      </template>
    </BaseModal>

    <BaseModal :show="showDeleteModal" title="Hapus Laporan" @close="showDeleteModal = false">
      <p class="text-text-muted text-sm">Apakah Anda yakin ingin menghapus laporan <strong>{{ selectedItem?.title }}</strong>? Data tidak dapat dipulihkan.</p>
      <template #footer>
        <div class="flex justify-end gap-3">
          <BaseButton variant="secondary" @click="showDeleteModal = false">Batal</BaseButton>
          <BaseButton variant="danger" :loading="actionLoading" @click="confirmDelete">Hapus</BaseButton>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

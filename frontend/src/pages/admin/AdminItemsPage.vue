<script setup>
import { ref, onMounted, watch } from 'vue'
import { adminService } from '@/services/endpoints'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseModal from '@/components/base/BaseModal.vue'
import BasePagination from '@/components/base/BasePagination.vue'

const items = ref([])
const meta = ref({})
const loading = ref(false)
const search = ref('')
const filterStatus = ref('')
const currentPage = ref(1)
const showDeleteModal = ref(false)
const selectedItem = ref(null)
const deleteLoading = ref(false)

const statusOptions = [
  { value: '', label: 'Semua Status' },
  { value: 'active', label: 'Aktif' },
  { value: 'resolved', label: 'Selesai' },
  { value: 'closed', label: 'Ditutup' },
]

function formatDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

async function load() {
  loading.value = true
  try {
    const params = { page: currentPage.value, per_page: 15 }
    if (search.value) params.search = search.value
    if (filterStatus.value) params.status = filterStatus.value
    const { data } = await adminService.listItems(params)
    items.value = data.data || []
    meta.value = data.meta || {}
  } finally {
    loading.value = false
  }
}

function openDelete(item) {
  selectedItem.value = item
  showDeleteModal.value = true
}

async function confirmDelete() {
  if (!selectedItem.value) return
  deleteLoading.value = true
  try {
    await adminService.deleteItem(selectedItem.value.id)
    showDeleteModal.value = false
    await load()
  } finally {
    deleteLoading.value = false
  }
}

let searchTimer = null
watch(search, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { currentPage.value = 1; load() }, 400)
})
watch([filterStatus, currentPage], load)
onMounted(load)
</script>

<template>
  <div class="max-w-6xl mx-auto px-4 py-8 space-y-6">
    <h1 class="text-2xl font-bold text-text-main">Kelola Laporan</h1>

    <div class="flex gap-3 flex-wrap">
      <input
        v-model="search"
        type="text"
        placeholder="Cari laporan..."
        class="flex-1 min-w-[200px] rounded-lg border border-border px-3 py-2.5 text-sm text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
      />
      <select
        v-model="filterStatus"
        class="rounded-lg border border-border px-3 py-2.5 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-primary-500"
      >
        <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
      </select>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 8" :key="i" class="h-14 bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else-if="items.length === 0" class="text-center py-20 text-text-muted">
      Tidak ada laporan ditemukan.
    </div>

    <div v-else class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left border-b border-border">
            <th class="pb-3 pr-3 font-medium text-text-muted">Foto</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Judul</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Tipe</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Status</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Pengguna</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Tanggal</th>
            <th class="pb-3 font-medium text-text-muted">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="border-b border-border last:border-0">
            <td class="py-3 pr-3">
              <img
                v-if="item.primary_photo_url"
                :src="item.primary_photo_url"
                :alt="item.title"
                class="w-10 h-10 object-cover rounded-lg"
              />
              <div v-else class="w-10 h-10 bg-surface-bg rounded-lg" />
            </td>
            <td class="py-3 pr-3 font-medium text-text-main max-w-[160px] truncate">{{ item.title }}</td>
            <td class="py-3 pr-3"><BaseBadge :type="item.type" /></td>
            <td class="py-3 pr-3"><BaseBadge :type="item.status" /></td>
            <td class="py-3 pr-3 text-text-muted">{{ item.owner?.name }}</td>
            <td class="py-3 pr-3 text-text-muted">{{ formatDate(item.created_at) }}</td>
            <td class="py-3">
              <div class="flex items-center gap-2">
                <a :href="`/items/${item.id}`" target="_blank" class="text-primary-600 hover:underline text-xs font-medium">Lihat</a>
                <BaseButton variant="danger" size="sm" @click="openDelete(item)">Hapus</BaseButton>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <BasePagination
      v-if="meta?.last_page > 1"
      :current-page="currentPage"
      :last-page="meta.last_page"
      @change="p => { currentPage = p }"
    />

    <BaseModal :show="showDeleteModal" title="Hapus Laporan" @close="showDeleteModal = false">
      <p class="text-text-muted text-sm">Hapus laporan <strong>{{ selectedItem?.title }}</strong>? Data tidak dapat dipulihkan.</p>
      <template #footer>
        <div class="flex justify-end gap-3">
          <BaseButton variant="secondary" @click="showDeleteModal = false">Batal</BaseButton>
          <BaseButton variant="danger" :loading="deleteLoading" @click="confirmDelete">Hapus</BaseButton>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

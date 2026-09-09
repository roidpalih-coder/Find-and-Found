<script setup>
import { ref, reactive, computed, watch, onMounted, nextTick } from 'vue'
import { useItemStore } from '@/stores/items'
import { categoryService } from '@/services/endpoints'
import ItemCard from '@/components/base/ItemCard.vue'
import MapPicker from '@/components/base/MapPicker.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BasePagination from '@/components/base/BasePagination.vue'

const itemStore = useItemStore()

const categories = ref([])
const viewMode = ref('grid')
const currentPage = ref(1)

const filters = reactive({
  search: '',
  type: '',
  category_id: '',
  status: '',
})

const items = computed(() => itemStore.items)
const meta = computed(() => itemStore.meta)
const loading = computed(() => itemStore.loading)

async function loadCategories() {
  const { data } = await categoryService.list()
  categories.value = data.data || data
}

async function fetchItems() {
  const params = { page: currentPage.value }
  if (filters.search) params.search = filters.search
  if (filters.type) params.type = filters.type
  if (filters.category_id) params.category_id = filters.category_id
  if (filters.status) params.status = filters.status
  await itemStore.fetchItems(params)
}

function onPageChange(page) {
  currentPage.value = page
  fetchItems()
}

let searchTimeout = null
watch(() => filters.search, () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    currentPage.value = 1
    fetchItems()
  }, 400)
})

watch([() => filters.type, () => filters.category_id, () => filters.status], () => {
  currentPage.value = 1
  fetchItems()
})

let mapInst = null
const mapEl = ref(null)

watch(viewMode, async (val) => {
  if (val === 'map') {
    await nextTick()
    if (mapInst) return
    initMap()
  }
})

async function initMap() {
  const L = (await import('leaflet')).default || (await import('leaflet'))
  await import('leaflet/dist/leaflet.css')

  delete L.Icon.Default.prototype._getIconUrl
  L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl:       'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl:     'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
  })

  mapInst = L.map(mapEl.value).setView([-6.7615, 111.0135], 11)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  }).addTo(mapInst)

  addMarkers(L)
}

function addMarkers(L) {
  if (!mapInst) return
  items.value.forEach((item) => {
    if (!item.latitude || !item.longitude) return
    const marker = L.marker([item.latitude, item.longitude]).addTo(mapInst)
    marker.bindPopup(`
      <div class="text-sm">
        <p class="font-semibold">${item.title}</p>
        <a href="/items/${item.id}" class="text-blue-600 underline">Lihat Detail</a>
      </div>
    `)
  })
}

watch(items, async () => {
  if (viewMode.value === 'map' && mapInst) {
    const L = (await import('leaflet')).default || (await import('leaflet'))
    mapInst.eachLayer((layer) => {
      if (layer instanceof L.Marker) mapInst.removeLayer(layer)
    })
    addMarkers(L)
  }
})

onMounted(() => {
  loadCategories()
  fetchItems()
})
</script>

<template>
  <div class="min-h-screen bg-surface-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-text-main">Jelajahi Laporan</h1>
        <p v-if="meta.total" class="text-sm text-text-muted mt-1">
          {{ meta.total }} laporan ditemukan
        </p>
      </div>

      <div class="sticky top-0 z-20 bg-surface-bg pb-4 pt-1">
        <div class="bg-white rounded-xl border border-border shadow-sm p-4">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Cari barang..."
                class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-border text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 min-h-[44px]"
              />
            </div>

            <select
              v-model="filters.type"
              class="rounded-lg border border-border px-3 py-2.5 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-primary-500 min-h-[44px] bg-white"
            >
              <option value="">Semua Tipe</option>
              <option value="lost">Hilang</option>
              <option value="found">Temuan</option>
            </select>

            <select
              v-model="filters.category_id"
              class="rounded-lg border border-border px-3 py-2.5 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-primary-500 min-h-[44px] bg-white"
            >
              <option value="">Semua Kategori</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>

            <select
              v-model="filters.status"
              class="rounded-lg border border-border px-3 py-2.5 text-sm text-text-main focus:outline-none focus:ring-2 focus:ring-primary-500 min-h-[44px] bg-white"
            >
              <option value="">Semua Status</option>
              <option value="active">Aktif</option>
              <option value="resolved">Selesai</option>
              <option value="closed">Ditutup</option>
            </select>

            <div class="flex rounded-lg border border-border overflow-hidden">
              <button
                @click="viewMode = 'grid'"
                :class="['px-4 py-2.5 text-sm font-medium transition-colors min-h-[44px]', viewMode === 'grid' ? 'bg-primary-500 text-white' : 'bg-white text-text-muted hover:bg-surface-bg']"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
              </button>
              <button
                @click="viewMode = 'map'"
                :class="['px-4 py-2.5 text-sm font-medium transition-colors min-h-[44px]', viewMode === 'map' ? 'bg-primary-500 text-white' : 'bg-white text-text-muted hover:bg-surface-bg']"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="viewMode === 'grid'">
        <div v-if="loading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
          <div v-for="n in 8" :key="n" class="bg-white rounded-xl border border-border overflow-hidden animate-pulse">
            <div class="aspect-[4/3] bg-gray-200" />
            <div class="p-4 space-y-2">
              <div class="h-4 bg-gray-200 rounded w-3/4" />
              <div class="h-3 bg-gray-200 rounded w-1/2" />
              <div class="h-3 bg-gray-200 rounded w-2/3" />
            </div>
          </div>
        </div>

        <div v-else-if="items.length === 0" class="flex flex-col items-center justify-center py-20 text-text-muted">
          <svg class="w-16 h-16 opacity-30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-lg font-medium">Tidak ada laporan ditemukan</p>
          <p class="text-sm mt-1">Coba ubah filter pencarian Anda</p>
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

      <div v-else class="rounded-xl overflow-hidden border border-border shadow-sm" style="height: 600px;">
        <div ref="mapEl" class="w-full h-full" />
      </div>
    </div>
  </div>
</template>

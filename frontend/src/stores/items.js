import { defineStore } from 'pinia'
import { ref } from 'vue'
import { itemService } from '@/services/endpoints'

export const useItemStore = defineStore('items', () => {
  const items      = ref([])
  const item       = ref(null)
  const myReports  = ref([])
  const meta       = ref({})
  const loading    = ref(false)
  const error      = ref(null)

  async function fetchItems(params = {}) {
    loading.value = true
    error.value   = null
    try {
      const { data } = await itemService.list(params)
      items.value = data.data
      meta.value  = data.meta || {}
    } catch (e) {
      error.value = e.response?.data?.message || 'Gagal memuat laporan.'
    } finally {
      loading.value = false
    }
  }

  async function fetchItem(id) {
    loading.value = true
    error.value   = null
    try {
      const { data } = await itemService.show(id)
      item.value = data.data
    } catch (e) {
      error.value = e.response?.data?.message || 'Laporan tidak ditemukan.'
    } finally {
      loading.value = false
    }
  }

  async function fetchMyReports(params = {}) {
    loading.value = true
    try {
      const { data } = await itemService.myReports(params)
      myReports.value = data.data
      meta.value      = data.meta || {}
    } catch (e) {
      error.value = e.response?.data?.message || 'Gagal memuat laporan.'
    } finally {
      loading.value = false
    }
  }

  async function createItem(payload) {
    const { data } = await itemService.create(payload)
    return data.data
  }

  async function updateItem(id, payload) {
    const { data } = await itemService.update(id, payload)
    return data.data
  }

  async function deleteItem(id) {
    await itemService.remove(id)
    myReports.value = myReports.value.filter((i) => i.id !== id)
  }

  async function closeItem(id) {
    await itemService.close(id)
    if (item.value?.id === id) item.value.status = 'closed'
  }

  return { items, item, myReports, meta, loading, error, fetchItems, fetchItem, fetchMyReports, createItem, updateItem, deleteItem, closeItem }
})

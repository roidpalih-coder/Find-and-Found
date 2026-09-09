import { defineStore } from 'pinia'
import { ref } from 'vue'
import { claimService } from '@/services/endpoints'

export const useClaimStore = defineStore('claims', () => {
  const myClaims   = ref([])
  const incoming   = ref([])
  const meta       = ref({})
  const loading    = ref(false)
  const error      = ref(null)

  async function fetchMyClaims(params = {}) {
    loading.value = true
    error.value   = null
    try {
      const { data } = await claimService.myClaims(params)
      myClaims.value = data.data
      meta.value     = data.meta || {}
    } catch (e) {
      error.value = e.response?.data?.message || 'Gagal memuat klaim.'
    } finally {
      loading.value = false
    }
  }

  async function fetchIncoming(params = {}) {
    loading.value = true
    error.value   = null
    try {
      const { data } = await claimService.incoming(params)
      incoming.value = data.data
      meta.value     = data.meta || {}
    } catch (e) {
      error.value = e.response?.data?.message || 'Gagal memuat klaim masuk.'
    } finally {
      loading.value = false
    }
  }

  async function submitClaim(itemId, payload) {
    const { data } = await claimService.submit(itemId, payload)
    return data.data
  }

  async function approveClaim(id, notes = '') {
    const { data } = await claimService.approve(id, { response_notes: notes })
    const idx = incoming.value.findIndex((c) => c.id === id)
    if (idx !== -1) incoming.value[idx] = data.data
    return data.data
  }

  async function rejectClaim(id, notes = '') {
    const { data } = await claimService.reject(id, { response_notes: notes })
    const idx = incoming.value.findIndex((c) => c.id === id)
    if (idx !== -1) incoming.value[idx] = data.data
    return data.data
  }

  return { myClaims, incoming, meta, loading, error, fetchMyClaims, fetchIncoming, submitClaim, approveClaim, rejectClaim }
})

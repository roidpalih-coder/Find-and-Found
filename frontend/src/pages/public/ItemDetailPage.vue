<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useItemStore } from '@/stores/items'
import { useClaimStore } from '@/stores/claims'
import MapPicker from '@/components/base/MapPicker.vue'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseModal from '@/components/base/BaseModal.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'
import BaseAvatar from '@/components/base/BaseAvatar.vue'

const route = useRoute()
const authStore = useAuthStore()
const itemStore = useItemStore()
const claimStore = useClaimStore()

const item = computed(() => itemStore.item)
const loading = computed(() => itemStore.loading)
const error = computed(() => itemStore.error)

const activePhoto = ref(null)
const showClaimModal = ref(false)
const claimSubmitting = ref(false)
const claimSuccess = ref(false)
const claimError = ref('')

const claimForm = ref({
  proof_description: '',
  proof_photo: null,
})

const proofPhotoPreview = ref(null)

function setActivePhoto(url) {
  activePhoto.value = url
}

function onProofPhotoChange(e) {
  const file = e.target.files[0]
  if (!file) return
  claimForm.value.proof_photo = file
  proofPhotoPreview.value = URL.createObjectURL(file)
}

const canClaim = computed(() => {
  if (!authStore.isAuthenticated) return false
  if (!item.value) return false
  if (item.value.status !== 'active') return false
  if (item.value.user_id === authStore.user?.id) return false
  return true
})

const approvedClaim = computed(() => {
  if (!authStore.isAuthenticated || !item.value) return null
  const myClaims = claimStore.myClaims
  return myClaims.find(
    (c) => c.item_id === item.value.id && c.status === 'approved'
  ) || null
})

async function submitClaim() {
  if (!claimForm.value.proof_description.trim()) {
    claimError.value = 'Deskripsi bukti wajib diisi.'
    return
  }
  claimError.value = ''
  claimSubmitting.value = true
  try {
    const fd = new FormData()
    fd.append('proof_description', claimForm.value.proof_description)
    if (claimForm.value.proof_photo) fd.append('proof_photo', claimForm.value.proof_photo)
    await claimStore.submitClaim(item.value.id, fd)
    claimSuccess.value = true
    showClaimModal.value = false
    claimForm.value = { proof_description: '', proof_photo: null }
    proofPhotoPreview.value = null
  } catch (e) {
    claimError.value = e.response?.data?.message || 'Gagal mengajukan klaim.'
  } finally {
    claimSubmitting.value = false
  }
}

function formatDate(d) {
  if (!d) return '-'
  return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
}

onMounted(async () => {
  await itemStore.fetchItem(route.params.id)
  if (item.value?.primary_photo_url) activePhoto.value = item.value.primary_photo_url
  if (authStore.isAuthenticated) await claimStore.fetchMyClaims()
})
</script>

<template>
  <div class="min-h-screen bg-surface-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

      <nav class="flex items-center gap-2 text-sm text-text-muted mb-6 flex-wrap">
        <RouterLink to="/" class="hover:text-primary-600 transition-colors">Beranda</RouterLink>
        <span>/</span>
        <RouterLink to="/explore" class="hover:text-primary-600 transition-colors">Jelajahi</RouterLink>
        <span>/</span>
        <span class="text-text-main font-medium truncate max-w-[200px]">{{ item?.title || '...' }}</span>
      </nav>

      <div v-if="loading" class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-pulse">
        <div class="space-y-4">
          <div class="aspect-[4/3] bg-gray-200 rounded-xl" />
          <div class="flex gap-2">
            <div v-for="n in 3" :key="n" class="w-20 h-20 bg-gray-200 rounded-lg" />
          </div>
        </div>
        <div class="space-y-4">
          <div class="h-6 bg-gray-200 rounded w-1/3" />
          <div class="h-8 bg-gray-200 rounded w-3/4" />
          <div class="h-4 bg-gray-200 rounded w-1/2" />
          <div class="h-20 bg-gray-200 rounded" />
        </div>
      </div>

      <div v-else-if="error" class="flex flex-col items-center justify-center py-20 text-text-muted">
        <svg class="w-16 h-16 opacity-30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-lg font-medium">{{ error }}</p>
        <RouterLink to="/explore" class="mt-4 text-primary-600 hover:underline text-sm">Kembali ke Jelajahi</RouterLink>
      </div>

      <div v-else-if="item" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-4">
          <div class="aspect-[4/3] rounded-xl overflow-hidden bg-surface-bg border border-border">
            <img
              v-if="activePhoto"
              :src="activePhoto"
              :alt="item.title"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-text-muted">
              <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>

          <div v-if="item.photos && item.photos.length > 0" class="flex gap-2 flex-wrap">
            <button
              v-if="item.primary_photo_url"
              @click="setActivePhoto(item.primary_photo_url)"
              :class="['w-20 h-20 rounded-lg overflow-hidden border-2 transition-colors', activePhoto === item.primary_photo_url ? 'border-primary-500' : 'border-border']"
            >
              <img :src="item.primary_photo_url" :alt="item.title" class="w-full h-full object-cover" />
            </button>
            <button
              v-for="photo in item.photos"
              :key="photo.id"
              @click="setActivePhoto(photo.url)"
              :class="['w-20 h-20 rounded-lg overflow-hidden border-2 transition-colors', activePhoto === photo.url ? 'border-primary-500' : 'border-border']"
            >
              <img :src="photo.url" :alt="item.title" class="w-full h-full object-cover" />
            </button>
          </div>

          <div v-if="item.latitude && item.longitude">
            <h3 class="text-sm font-semibold text-text-main mb-2">Lokasi Kejadian</h3>
            <MapPicker
              :lat="item.latitude"
              :lng="item.longitude"
              :readonly="true"
              :zoom="14"
            />
          </div>
        </div>

        <div class="space-y-5">
          <div class="flex flex-wrap gap-2">
            <BaseBadge :type="item.type" />
            <BaseBadge v-if="item.is_priority_document" type="priority" />
            <BaseBadge :type="item.status" />
          </div>

          <div>
            <h1 class="text-2xl font-bold text-text-main">{{ item.title }}</h1>
            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-text-muted">
              <span v-if="item.category">
                <span class="font-medium">Kategori:</span> {{ item.category.name }}
              </span>
              <span v-if="item.incident_date">
                <span class="font-medium">Tanggal:</span> {{ formatDate(item.incident_date) }}
              </span>
            </div>
          </div>

          <div v-if="item.location_name || item.district" class="flex items-start gap-2 text-sm text-text-muted">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>{{ [item.location_name, item.district].filter(Boolean).join(', ') }}</span>
          </div>

          <div>
            <h3 class="text-sm font-semibold text-text-main mb-1">Deskripsi</h3>
            <p class="text-sm text-text-muted leading-relaxed whitespace-pre-line">{{ item.description }}</p>
          </div>

          <div v-if="item.user" class="flex items-center gap-3 p-4 bg-surface-bg rounded-xl border border-border">
            <BaseAvatar :src="item.user.avatar_url" :name="item.user.name" size="md" />
            <div>
              <p class="text-sm font-semibold text-text-main">{{ item.user.name }}</p>
              <p class="text-xs text-text-muted">Reputasi: {{ item.user.reputation_points || 0 }} poin</p>
            </div>
          </div>

          <BaseAlert
            v-if="claimSuccess"
            type="success"
            message="Klaim berhasil diajukan, tunggu verifikasi dari penemu."
          />

          <div v-if="approvedClaim" class="p-4 bg-success-500/10 border border-success-500/30 rounded-xl space-y-3">
            <p class="text-sm font-semibold text-success-600">Klaim Anda disetujui! Hubungi pelapor:</p>
            <div class="flex flex-wrap gap-3">
              <a
                v-if="item.user?.phone_number"
                :href="`https://wa.me/${item.user.phone_number}?text=Halo saya pengaju klaim barang ${item.title}`"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors min-h-[44px]"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp
              </a>
              <a
                v-if="item.user?.instagram_handle"
                :href="`https://instagram.com/${item.user.instagram_handle}`"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity min-h-[44px]"
              >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                Instagram
              </a>
            </div>
          </div>

          <BaseButton v-if="canClaim && !claimSuccess" @click="showClaimModal = true" size="lg" class="w-full">
            Ajukan Klaim
          </BaseButton>

          <p v-if="!authStore.isAuthenticated" class="text-sm text-text-muted text-center">
            <RouterLink to="/login" class="text-primary-600 hover:underline font-medium">Masuk</RouterLink> untuk mengajukan klaim
          </p>
        </div>
      </div>
    </div>

    <BaseModal :show="showClaimModal" title="Ajukan Klaim" @close="showClaimModal = false">
      <div class="space-y-4">
        <BaseAlert v-if="claimError" type="error" :message="claimError" />

        <div class="flex flex-col gap-1">
          <label class="text-sm font-medium text-text-main">
            Deskripsi Bukti <span class="text-danger-500">*</span>
          </label>
          <textarea
            v-model="claimForm.proof_description"
            placeholder="Jelaskan bukti kepemilikan Anda secara detail..."
            rows="4"
            class="w-full rounded-lg border border-border px-3 py-2.5 text-sm text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
          />
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-sm font-medium text-text-main">Foto Bukti (opsional)</label>
          <input
            type="file"
            accept="image/*"
            @change="onProofPhotoChange"
            class="text-sm text-text-muted file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer"
          />
          <img v-if="proofPhotoPreview" :src="proofPhotoPreview" alt="Preview" class="mt-2 rounded-lg h-32 object-cover w-full" />
        </div>

        <div class="flex gap-3 pt-2">
          <BaseButton variant="secondary" class="flex-1" @click="showClaimModal = false">Batal</BaseButton>
          <BaseButton class="flex-1" :loading="claimSubmitting" @click="submitClaim">Kirim Klaim</BaseButton>
        </div>
      </div>
    </BaseModal>
  </div>
</template>

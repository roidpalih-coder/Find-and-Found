<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useReportStore } from '@/stores/report'
import { useItemStore } from '@/stores/items'
import { categoryService, itemService } from '@/services/endpoints'
import MapPicker from '@/components/base/MapPicker.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'

const props = defineProps({
  type: { type: String, required: true },
})

const router = useRouter()
const reportStore = useReportStore()

const categories = ref([])
const submitting = ref(false)
const submitError = ref('')
const stepError = ref('')

const primaryPhotoPreview = ref(null)
const extraPhotoPreviews = ref([])

const step = computed(() => reportStore.step)
const form = computed(() => reportStore.form)

const stepTitles = ['Kategori', 'Detail Barang', 'Foto', 'Lokasi', 'Waktu & Konfirmasi']

async function loadCategories() {
  const { data } = await categoryService.list()
  categories.value = data.data || data
}

function selectCategory(id) {
  reportStore.form.category_id = id
}

function onPrimaryPhotoChange(e) {
  const file = e.target.files[0]
  if (!file) return
  reportStore.form.primary_photo = file
  primaryPhotoPreview.value = URL.createObjectURL(file)
}

function onExtraPhotosChange(e) {
  const files = Array.from(e.target.files).slice(0, 5)
  reportStore.form.photos = files
  extraPhotoPreviews.value = files.map((f) => URL.createObjectURL(f))
}

function onCoordsUpdate({ lat, lng }) {
  reportStore.form.latitude = lat
  reportStore.form.longitude = lng
}

function validateStep() {
  stepError.value = ''
  if (step.value === 1) {
    if (!reportStore.form.category_id) { stepError.value = 'Pilih kategori barang.'; return false }
  }
  if (step.value === 2) {
    if (!reportStore.form.title.trim()) { stepError.value = 'Judul barang wajib diisi.'; return false }
    if (!reportStore.form.description.trim()) { stepError.value = 'Deskripsi wajib diisi.'; return false }
  }
  if (step.value === 3) {
    if (!reportStore.form.primary_photo) { stepError.value = 'Foto utama wajib diunggah.'; return false }
  }
  if (step.value === 4) {
    if (!reportStore.form.latitude || !reportStore.form.longitude) { stepError.value = 'Pilih lokasi pada peta.'; return false }
    if (!reportStore.form.location_name.trim()) { stepError.value = 'Nama lokasi wajib diisi.'; return false }
  }
  if (step.value === 5) {
    if (!reportStore.form.incident_date) { stepError.value = 'Tanggal kejadian wajib diisi.'; return false }
  }
  return true
}

function nextStep() {
  if (!validateStep()) return
  reportStore.nextStep()
  stepError.value = ''
}

function prevStep() {
  stepError.value = ''
  reportStore.prevStep()
}

const selectedCategory = computed(() => categories.value.find((c) => c.id === reportStore.form.category_id))

async function submitReport() {
  if (!validateStep()) return
  submitError.value = ''
  submitting.value = true
  try {
    const fd = new FormData()
    fd.append('type', props.type)
    fd.append('category_id', reportStore.form.category_id)
    fd.append('title', reportStore.form.title)
    fd.append('description', reportStore.form.description)
    if (reportStore.form.secret_details) fd.append('secret_details', reportStore.form.secret_details)
    fd.append('primary_photo', reportStore.form.primary_photo)
    reportStore.form.photos.forEach((f) => fd.append('photos[]', f))
    fd.append('latitude', reportStore.form.latitude)
    fd.append('longitude', reportStore.form.longitude)
    fd.append('location_name', reportStore.form.location_name)
    fd.append('district', reportStore.form.district)
    fd.append('incident_date', reportStore.form.incident_date)
    if (reportStore.form.incident_time) fd.append('incident_time', reportStore.form.incident_time)
    if (props.type === 'lost' && reportStore.form.reward_offered) fd.append('reward_offered', reportStore.form.reward_offered)

    await itemService.create(fd)
    reportStore.reset()
    router.push('/my-reports')
  } catch (e) {
    submitError.value = e.response?.data?.message || 'Gagal mengirim laporan.'
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  reportStore.form.type = props.type
  loadCategories()
})
</script>

<template>
  <div class="min-h-screen bg-surface-bg">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-8">
      <h1 class="text-2xl font-bold text-text-main mb-6">
        {{ type === 'found' ? 'Laporkan Barang Temuan' : 'Laporkan Barang Hilang' }}
      </h1>

      <div class="flex items-center gap-2 mb-8">
        <template v-for="(title, idx) in stepTitles" :key="idx">
          <div class="flex items-center gap-2">
            <div
              :class="[
                'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-colors',
                step === idx + 1
                  ? 'bg-primary-500 text-white'
                  : step > idx + 1
                  ? 'bg-primary-100 text-primary-700'
                  : 'bg-gray-100 text-text-muted'
              ]"
            >
              <svg v-if="step > idx + 1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
              <span v-else>{{ idx + 1 }}</span>
            </div>
            <span class="hidden sm:block text-xs font-medium" :class="step === idx + 1 ? 'text-primary-600' : 'text-text-muted'">{{ title }}</span>
          </div>
          <div v-if="idx < stepTitles.length - 1" class="flex-1 h-px bg-border" />
        </template>
      </div>

      <div class="bg-white rounded-xl border border-border shadow-sm p-6">
        <BaseAlert v-if="stepError" type="error" :message="stepError" class="mb-4" />

        <div v-if="step === 1">
          <h2 class="text-base font-semibold text-text-main mb-4">Pilih Kategori Barang</h2>
          <div v-if="categories.length === 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3 animate-pulse">
            <div v-for="n in 8" :key="n" class="aspect-square bg-gray-100 rounded-xl" />
          </div>
          <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
            <button
              v-for="cat in categories"
              :key="cat.id"
              @click="selectCategory(cat.id)"
              :class="[
                'flex flex-col items-center gap-2 p-3 rounded-xl border-2 transition-all text-center',
                form.category_id === cat.id
                  ? 'border-primary-500 bg-primary-50 text-primary-700'
                  : 'border-border bg-surface-bg text-text-muted hover:border-primary-300 hover:bg-primary-50/50'
              ]"
            >
              <span v-if="cat.icon" class="text-2xl">{{ cat.icon }}</span>
              <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
              <span class="text-xs font-medium leading-tight">{{ cat.name }}</span>
            </button>
          </div>
        </div>

        <div v-if="step === 2" class="space-y-4">
          <h2 class="text-base font-semibold text-text-main mb-2">Detail Barang</h2>
          <BaseInput
            label="Judul Barang"
            v-model="form.title"
            placeholder="Contoh: Dompet hitam kulit"
            required
          />
          <div class="flex flex-col gap-1">
            <label class="text-sm font-medium text-text-main">Deskripsi <span class="text-danger-500">*</span></label>
            <textarea
              v-model="form.description"
              placeholder="Deskripsikan barang secara detail..."
              rows="4"
              class="w-full rounded-lg border border-border px-3 py-2.5 text-sm text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
            />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-sm font-medium text-text-main flex items-center gap-2">
              Ciri Rahasia
              <span class="group relative">
                <svg class="w-4 h-4 text-text-muted cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="invisible group-hover:visible absolute left-6 top-0 w-56 bg-gray-800 text-white text-xs rounded-lg p-2 z-10">
                  Tidak ditampilkan publik, digunakan untuk verifikasi klaim
                </span>
              </span>
            </label>
            <textarea
              v-model="form.secret_details"
              placeholder="Ciri khas yang hanya diketahui pemilik (tidak tampil ke publik)..."
              rows="3"
              class="w-full rounded-lg border border-border px-3 py-2.5 text-sm text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"
            />
          </div>
        </div>

        <div v-if="step === 3" class="space-y-5">
          <h2 class="text-base font-semibold text-text-main mb-2">Foto Barang</h2>
          <div>
            <label class="text-sm font-medium text-text-main block mb-2">
              Foto Utama <span class="text-danger-500">*</span>
            </label>
            <input
              type="file"
              accept="image/*"
              @change="onPrimaryPhotoChange"
              class="text-sm text-text-muted file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer w-full"
            />
            <div v-if="primaryPhotoPreview" class="mt-3 rounded-xl overflow-hidden aspect-[4/3] border border-border">
              <img :src="primaryPhotoPreview" alt="Preview" class="w-full h-full object-cover" />
            </div>
          </div>
          <div>
            <label class="text-sm font-medium text-text-main block mb-2">Foto Tambahan (maks. 5, opsional)</label>
            <input
              type="file"
              accept="image/*"
              multiple
              @change="onExtraPhotosChange"
              class="text-sm text-text-muted file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 cursor-pointer w-full"
            />
            <div v-if="extraPhotoPreviews.length > 0" class="mt-3 flex gap-2 flex-wrap">
              <img
                v-for="(url, i) in extraPhotoPreviews"
                :key="i"
                :src="url"
                alt="Preview"
                class="w-20 h-20 object-cover rounded-lg border border-border"
              />
            </div>
          </div>
        </div>

        <div v-if="step === 4" class="space-y-4">
          <h2 class="text-base font-semibold text-text-main mb-2">Lokasi Kejadian</h2>
          <p class="text-xs text-text-muted">Klik atau geser marker pada peta untuk menentukan lokasi.</p>
          <MapPicker :lat="-6.7615" :lng="111.0135" :zoom="12" @update:coords="onCoordsUpdate" />
          <div v-if="form.latitude" class="text-xs text-text-muted">
            Koordinat: {{ form.latitude?.toFixed(6) }}, {{ form.longitude?.toFixed(6) }}
          </div>
          <BaseInput label="Nama Lokasi / Landmark" v-model="form.location_name" placeholder="Contoh: Pasar Pati, Alun-alun Pati" required />
          <BaseInput label="Kecamatan" v-model="form.district" placeholder="Contoh: Pati Kota" />
        </div>

        <div v-if="step === 5" class="space-y-5">
          <h2 class="text-base font-semibold text-text-main mb-2">Waktu & Konfirmasi</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <BaseInput label="Tanggal Kejadian" type="date" v-model="form.incident_date" required />
            <BaseInput label="Waktu Kejadian" type="time" v-model="form.incident_time" />
          </div>
          <BaseInput
            v-if="type === 'lost'"
            label="Hadiah (opsional)"
            v-model="form.reward_offered"
            placeholder="Contoh: Rp 100.000"
          />

          <div class="bg-surface-bg rounded-xl border border-border p-4 space-y-2 text-sm">
            <h3 class="font-semibold text-text-main mb-3">Ringkasan Laporan</h3>
            <div class="grid grid-cols-2 gap-1">
              <span class="text-text-muted">Tipe:</span>
              <span class="font-medium text-text-main">{{ type === 'found' ? 'Barang Temuan' : 'Barang Hilang' }}</span>
              <span class="text-text-muted">Kategori:</span>
              <span class="font-medium text-text-main">{{ selectedCategory?.name || '-' }}</span>
              <span class="text-text-muted">Judul:</span>
              <span class="font-medium text-text-main">{{ form.title || '-' }}</span>
              <span class="text-text-muted">Lokasi:</span>
              <span class="font-medium text-text-main">{{ [form.location_name, form.district].filter(Boolean).join(', ') || '-' }}</span>
              <span class="text-text-muted">Tanggal:</span>
              <span class="font-medium text-text-main">{{ form.incident_date || '-' }}</span>
              <template v-if="type === 'lost' && form.reward_offered">
                <span class="text-text-muted">Hadiah:</span>
                <span class="font-medium text-text-main">{{ form.reward_offered }}</span>
              </template>
            </div>
          </div>

          <BaseAlert v-if="submitError" type="error" :message="submitError" />
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <BaseButton v-if="step > 1" variant="secondary" class="flex-1" @click="prevStep">Kembali</BaseButton>
        <BaseButton v-if="step < 5" class="flex-1" @click="nextStep">Lanjut</BaseButton>
        <BaseButton v-if="step === 5" class="flex-1" :loading="submitting" @click="submitReport">
          {{ type === 'found' ? 'Laporkan Temuan' : 'Laporkan Kehilangan' }}
        </BaseButton>
      </div>
    </div>
  </div>
</template>

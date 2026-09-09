<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'
import BaseAvatar from '@/components/base/BaseAvatar.vue'

const authStore = useAuthStore()

const form = ref({
  name: '',
  phone_number: '',
  instagram_handle: '',
  domicile_city: '',
})
const avatarFile = ref(null)
const avatarPreview = ref('')
const loading = ref(false)
const alertType = ref('')
const alertMsg = ref('')
const fileInput = ref(null)

function initForm() {
  const u = authStore.user
  if (!u) return
  form.value.name = u.name || ''
  form.value.phone_number = u.phone_number || ''
  form.value.instagram_handle = u.instagram_handle || ''
  form.value.domicile_city = u.domicile_city || ''
  avatarPreview.value = u.avatar_url || ''
}

function onFileChange(e) {
  const file = e.target.files[0]
  if (!file) return
  avatarFile.value = file
  avatarPreview.value = URL.createObjectURL(file)
}

async function save() {
  loading.value = true
  alertMsg.value = ''
  try {
    const payload = new FormData()
    Object.entries(form.value).forEach(([k, v]) => payload.append(k, v))
    if (avatarFile.value) payload.append('avatar', avatarFile.value)
    await authStore.updateProfile(payload)
    alertType.value = 'success'
    alertMsg.value = 'Profil berhasil diperbarui.'
  } catch (e) {
    alertType.value = 'error'
    alertMsg.value = e.response?.data?.message || 'Gagal memperbarui profil.'
  } finally {
    loading.value = false
  }
}

onMounted(initForm)
</script>

<template>
  <div class="max-w-xl mx-auto px-4 py-8 space-y-6">
    <h1 class="text-2xl font-bold text-text-main">Profil Saya</h1>

    <div class="flex flex-col items-center gap-3">
      <BaseAvatar :src="avatarPreview" :name="authStore.user?.name" size="xl" />
      <button
        class="text-sm text-primary-600 hover:underline font-medium"
        @click="fileInput?.click()"
      >
        Ganti Foto
      </button>
      <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange" />
    </div>

    <BaseAlert v-if="alertMsg" :type="alertType" :message="alertMsg" />

    <form class="space-y-4" @submit.prevent="save">
      <BaseInput v-model="form.name" label="Nama" placeholder="Nama lengkap" required />
      <BaseInput :model-value="authStore.user?.email" label="Email" type="email" :disabled="true" />
      <BaseInput v-model="form.phone_number" label="Nomor Telepon" type="tel" placeholder="+62..." />
      <BaseInput v-model="form.instagram_handle" label="Instagram" placeholder="@username" />
      <BaseInput v-model="form.domicile_city" label="Kota Domisili" placeholder="Jakarta" />

      <BaseButton type="submit" variant="primary" :loading="loading" class="w-full">
        Simpan Perubahan
      </BaseButton>
    </form>

    <div class="p-4 bg-surface-bg rounded-xl border border-border">
      <p class="text-sm font-medium text-text-muted">Poin Reputasi</p>
      <p class="text-3xl font-bold text-primary-600 mt-1">{{ authStore.user?.reputation_points ?? 0 }}</p>
      <p class="text-xs text-text-muted mt-2">Poin reputasi bertambah saat laporan Anda terselesaikan dan mendapat ulasan positif dari komunitas.</p>
    </div>
  </div>
</template>

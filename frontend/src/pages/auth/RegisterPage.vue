<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  name: '',
  email: '',
  phone_number: '',
  instagram_handle: '',
  domicile_city: 'Pati',
  password: '',
  password_confirmation: '',
})
const loading = ref(false)
const error = ref('')

async function handleRegister() {
  error.value = ''
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Konfirmasi kata sandi tidak cocok.'
    return
  }
  loading.value = true
  try {
    await authStore.register(form.value)
    router.push('/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message || 'Pendaftaran gagal. Periksa kembali data Anda.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">
      <div class="bg-white rounded-2xl border border-border shadow-sm p-8">
        <div class="text-center mb-8">
          <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
          </div>
          <h1 class="text-2xl font-bold text-text-main">Buat Akun Baru</h1>
          <p class="text-text-muted text-sm mt-1">Bergabung dengan komunitas Find &amp; Found</p>
        </div>

        <BaseAlert v-if="error" type="error" :message="error" class="mb-5" />

        <form @submit.prevent="handleRegister" class="space-y-4">
          <BaseInput
            v-model="form.name"
            label="Nama Lengkap"
            type="text"
            placeholder="Nama lengkap Anda"
            :required="true"
            :disabled="loading"
          />
          <BaseInput
            v-model="form.email"
            label="Email"
            type="email"
            placeholder="contoh@email.com"
            :required="true"
            :disabled="loading"
          />
          <BaseInput
            v-model="form.phone_number"
            label="Nomor WhatsApp"
            type="tel"
            placeholder="628123456789"
            :required="true"
            :disabled="loading"
          />
          <BaseInput
            v-model="form.instagram_handle"
            label="Username Instagram"
            type="text"
            placeholder="username tanpa @"
            :disabled="loading"
          />
          <BaseInput
            v-model="form.domicile_city"
            label="Kota Domisili"
            type="text"
            placeholder="Pati"
            :disabled="loading"
          />
          <BaseInput
            v-model="form.password"
            label="Kata Sandi"
            type="password"
            placeholder="Minimal 8 karakter"
            :required="true"
            :disabled="loading"
          />
          <BaseInput
            v-model="form.password_confirmation"
            label="Konfirmasi Kata Sandi"
            type="password"
            placeholder="Ulangi kata sandi"
            :required="true"
            :disabled="loading"
          />
          <BaseButton type="submit" variant="primary" size="lg" :loading="loading" class="w-full mt-2">
            Daftar Sekarang
          </BaseButton>
        </form>

        <p class="text-center text-sm text-text-muted mt-6">
          Sudah punya akun?
          <RouterLink to="/login" class="text-primary-600 font-semibold hover:underline">Masuk</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

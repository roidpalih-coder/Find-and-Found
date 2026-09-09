<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({ email: '', password: '' })
const loading = ref(false)
const error = ref('')

async function handleLogin() {
  error.value = ''
  loading.value = true
  try {
    await authStore.login(form.value)
    const redirect = route.query.redirect || '/dashboard'
    router.push(redirect)
  } catch (e) {
    error.value = e.response?.data?.message || 'Email atau kata sandi salah.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <div class="bg-white rounded-2xl border border-border shadow-sm p-8">
        <div class="text-center mb-8">
          <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <h1 class="text-2xl font-bold text-text-main">Masuk ke Akun Anda</h1>
          <p class="text-text-muted text-sm mt-1">Selamat datang kembali di Find &amp; Found</p>
        </div>

        <BaseAlert v-if="error" type="error" :message="error" class="mb-5" />

        <form @submit.prevent="handleLogin" class="space-y-4">
          <BaseInput
            v-model="form.email"
            label="Email"
            type="email"
            placeholder="contoh@email.com"
            :required="true"
            :disabled="loading"
          />
          <BaseInput
            v-model="form.password"
            label="Kata Sandi"
            type="password"
            placeholder="Masukkan kata sandi"
            :required="true"
            :disabled="loading"
          />
          <BaseButton type="submit" variant="primary" size="lg" :loading="loading" class="w-full mt-2">
            Masuk
          </BaseButton>
        </form>

        <p class="text-center text-sm text-text-muted mt-6">
          Belum punya akun?
          <RouterLink to="/register" class="text-primary-600 font-semibold hover:underline">Daftar</RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

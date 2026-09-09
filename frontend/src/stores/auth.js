import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authService } from '@/services/endpoints'

export const useAuthStore = defineStore('auth', () => {
  const user  = ref(null)
  const token = ref(localStorage.getItem('ff_token') || null)

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin         = computed(() => user.value?.role === 'admin')

  async function login(credentials) {
    const { data } = await authService.login(credentials)
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('ff_token', data.token)
  }

  async function register(payload) {
    const { data } = await authService.register(payload)
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('ff_token', data.token)
  }

  async function logout() {
    try { await authService.logout() } catch {}
    token.value = null
    user.value  = null
    localStorage.removeItem('ff_token')
  }

  async function fetchProfile() {
    const { data } = await authService.me()
    user.value = data.data
  }

  async function updateProfile(payload) {
    const { data } = await authService.update(payload)
    user.value = data.data
  }

  return { user, token, isAuthenticated, isAdmin, login, register, logout, fetchProfile, updateProfile }
})

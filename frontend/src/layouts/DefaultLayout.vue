<script setup>
import { ref, computed } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useNotificationStore } from '@/stores/notifications'
import { onMounted, onUnmounted } from 'vue'

const auth         = useAuthStore()
const notifStore   = useNotificationStore()
const router       = useRouter()
const mobileOpen   = ref(false)
const userDropdown = ref(false)

const navLinks = [
  { name: 'Beranda',           to: '/' },
  { name: 'Jelajahi',          to: '/explore' },
  { name: 'Dokumen Prioritas', to: '/priority-documents' },
  { name: 'Tentang',           to: '/about' },
]

onMounted(() => { if (auth.isAuthenticated) notifStore.startPolling() })
onUnmounted(() => notifStore.stopPolling())

async function handleLogout() {
  await auth.logout()
  userDropdown.value = false
  router.push('/')
}
</script>

<template>
  <div class="min-h-screen flex flex-col bg-surface-bg">
    <header class="bg-white border-b border-border sticky top-0 z-40">
      <nav class="ff-container flex items-center justify-between h-16">
        <RouterLink to="/" class="flex items-center gap-2 font-extrabold text-xl text-primary-600">
          <span class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">F</span>
          Find &amp; Found
        </RouterLink>

        <div class="hidden md:flex items-center gap-6">
          <RouterLink
            v-for="link in navLinks"
            :key="link.to"
            :to="link.to"
            class="text-sm font-medium text-text-muted hover:text-primary-600 transition-colors"
            active-class="text-primary-600"
          >
            {{ link.name }}
          </RouterLink>
        </div>

        <div class="flex items-center gap-3">
          <template v-if="auth.isAuthenticated">
            <RouterLink to="/notifications" class="relative p-2 text-text-muted hover:text-primary-600 transition-colors" aria-label="Notifikasi">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
              <span v-if="notifStore.unreadCount > 0" class="absolute top-1 right-1 w-4 h-4 bg-danger-500 rounded-full text-white text-[10px] flex items-center justify-center font-bold">
                {{ notifStore.unreadCount > 9 ? '9+' : notifStore.unreadCount }}
              </span>
            </RouterLink>

            <div class="relative">
              <button @click="userDropdown = !userDropdown" class="flex items-center gap-2 p-1 rounded-full hover:bg-surface-bg transition-colors min-w-[44px] min-h-[44px] justify-center" aria-label="Menu akun">
                <img v-if="auth.user?.avatar_url" :src="auth.user.avatar_url" :alt="auth.user.name" class="w-8 h-8 rounded-full object-cover" />
                <div v-else class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-bold">
                  {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
                </div>
              </button>
              <div v-if="userDropdown" class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-border py-1 z-50">
                <div class="px-4 py-2 border-b border-border">
                  <p class="text-sm font-semibold text-text-main truncate">{{ auth.user?.name }}</p>
                  <p class="text-xs text-text-muted truncate">{{ auth.user?.email }}</p>
                </div>
                <RouterLink to="/dashboard"      class="block px-4 py-2 text-sm text-text-main hover:bg-surface-bg" @click="userDropdown=false">Dashboard</RouterLink>
                <RouterLink to="/my-reports"     class="block px-4 py-2 text-sm text-text-main hover:bg-surface-bg" @click="userDropdown=false">Laporan Saya</RouterLink>
                <RouterLink to="/incoming-claims" class="block px-4 py-2 text-sm text-text-main hover:bg-surface-bg" @click="userDropdown=false">Klaim Masuk</RouterLink>
                <RouterLink to="/profile"        class="block px-4 py-2 text-sm text-text-main hover:bg-surface-bg" @click="userDropdown=false">Profil</RouterLink>
                <RouterLink v-if="auth.isAdmin" to="/admin" class="block px-4 py-2 text-sm text-primary-600 font-medium hover:bg-surface-bg" @click="userDropdown=false">Admin Panel</RouterLink>
                <button @click="handleLogout" class="w-full text-left px-4 py-2 text-sm text-danger-500 hover:bg-surface-bg">Keluar</button>
              </div>
            </div>
          </template>
          <template v-else>
            <RouterLink to="/login"    class="text-sm font-medium text-text-muted hover:text-primary-600 transition-colors hidden sm:block">Masuk</RouterLink>
            <RouterLink to="/register" class="text-sm font-semibold bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600 transition-colors">Daftar</RouterLink>
          </template>

          <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-text-muted min-w-[44px] min-h-[44px] flex items-center justify-center" aria-label="Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </nav>

      <div v-if="mobileOpen" class="md:hidden bg-white border-t border-border px-4 py-3 space-y-1">
        <RouterLink v-for="link in navLinks" :key="link.to" :to="link.to" class="block py-2 text-sm font-medium text-text-main hover:text-primary-600" @click="mobileOpen=false">
          {{ link.name }}
        </RouterLink>
        <template v-if="!auth.isAuthenticated">
          <RouterLink to="/login"    class="block py-2 text-sm font-medium text-text-main" @click="mobileOpen=false">Masuk</RouterLink>
          <RouterLink to="/register" class="block py-2 text-sm font-medium text-primary-600" @click="mobileOpen=false">Daftar</RouterLink>
        </template>
      </div>
    </header>

    <main class="flex-1">
      <RouterView />
    </main>

    <footer class="bg-white border-t border-border mt-16">
      <div class="ff-container py-10 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <p class="font-extrabold text-lg text-primary-600">Find &amp; Found</p>
          <p class="mt-2 text-sm text-text-muted">Temukan yang Hilang, Kembalikan dengan Tenang.</p>
          <p class="mt-1 text-xs text-text-muted">Kabupaten Pati dan sekitarnya.</p>
        </div>
        <div>
          <p class="font-semibold text-sm text-text-main mb-3">Navigasi</p>
          <div class="space-y-2">
            <RouterLink v-for="link in navLinks" :key="link.to" :to="link.to" class="block text-sm text-text-muted hover:text-primary-600">{{ link.name }}</RouterLink>
          </div>
        </div>
        <div>
          <p class="font-semibold text-sm text-text-main mb-3">Laporkan Barang</p>
          <div class="space-y-2">
            <RouterLink to="/report/found" class="block text-sm text-text-muted hover:text-primary-600">Saya Menemukan Barang</RouterLink>
            <RouterLink to="/report/lost"  class="block text-sm text-text-muted hover:text-primary-600">Saya Kehilangan Barang</RouterLink>
          </div>
        </div>
      </div>
      <div class="border-t border-border py-4 text-center text-xs text-text-muted">
        &copy; 2026 Find &amp; Found — XII TJKT 1
      </div>
    </footer>
  </div>
</template>

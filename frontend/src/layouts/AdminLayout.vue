<script setup>
import { ref } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth       = useAuthStore()
const router     = useRouter()
const sidebarOpen = ref(true)

const navItems = [
  { label: 'Dashboard',  to: '/admin',            icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
  { label: 'Laporan',    to: '/admin/items',       icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
  { label: 'Klaim',      to: '/admin/claims',      icon: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z' },
  { label: 'Pengguna',   to: '/admin/users',       icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
  { label: 'Kategori',   to: '/admin/categories',  icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' },
]

async function handleLogout() {
  await auth.logout()
  router.push('/')
}
</script>

<template>
  <div class="min-h-screen flex bg-surface-bg">
    <aside :class="['bg-white border-r border-border flex flex-col transition-all duration-300', sidebarOpen ? 'w-60' : 'w-16']">
      <div class="h-16 flex items-center px-4 border-b border-border gap-3">
        <span class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center text-white text-sm font-bold flex-shrink-0">F</span>
        <span v-if="sidebarOpen" class="font-extrabold text-primary-600 truncate">Admin Panel</span>
      </div>

      <nav class="flex-1 py-4 px-2 space-y-1">
        <RouterLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-text-muted hover:bg-surface-bg hover:text-primary-600 transition-colors min-h-[44px]"
          active-class="bg-primary-50 text-primary-600"
        >
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
          </svg>
          <span v-if="sidebarOpen">{{ item.label }}</span>
        </RouterLink>
      </nav>

      <div class="p-2 border-t border-border space-y-1">
        <RouterLink to="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-text-muted hover:bg-surface-bg transition-colors min-h-[44px]">
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
          <span v-if="sidebarOpen">Lihat Website</span>
        </RouterLink>
        <button @click="handleLogout" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-danger-500 hover:bg-danger-500/5 transition-colors min-h-[44px]">
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
          <span v-if="sidebarOpen">Keluar</span>
        </button>
      </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
      <header class="h-16 bg-white border-b border-border flex items-center px-6 gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-text-muted hover:text-primary-600 transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center" aria-label="Toggle sidebar">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <div class="flex-1" />
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-bold">
            {{ auth.user?.name?.charAt(0)?.toUpperCase() }}
          </div>
          <span class="text-sm font-medium text-text-main hidden sm:block">{{ auth.user?.name }}</span>
        </div>
      </header>

      <main class="flex-1 p-6 overflow-auto">
        <RouterView />
      </main>
    </div>
  </div>
</template>

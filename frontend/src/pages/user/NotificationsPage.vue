<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useNotificationStore } from '@/stores/notifications'

const notifStore = useNotificationStore()
const router = useRouter()

function timeAgo(dateStr) {
  if (!dateStr) return ''
  const diff = Date.now() - new Date(dateStr).getTime()
  const mins = Math.floor(diff / 60000)
  if (mins < 1) return 'Baru saja'
  if (mins < 60) return `${mins} menit lalu`
  const hours = Math.floor(mins / 60)
  if (hours < 24) return `${hours} jam lalu`
  const days = Math.floor(hours / 24)
  return `${days} hari lalu`
}

async function handleClick(notif) {
  await notifStore.markRead(notif.id)
  if (notif.data?.item_id) {
    router.push(`/items/${notif.data.item_id}`)
  } else if (notif.data?.claim_id) {
    router.push(`/claims/${notif.data.claim_id}`)
  }
}

onMounted(() => notifStore.fetchNotifications())
</script>

<template>
  <div class="max-w-2xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-text-main">Notifikasi</h1>
      <button
        v-if="notifStore.notifications.length > 0"
        class="text-sm text-primary-600 hover:underline font-medium"
        @click="notifStore.markAllRead()"
      >
        Tandai Semua Dibaca
      </button>
    </div>

    <div v-if="notifStore.notifications.length === 0" class="text-center py-20 text-text-muted">
      <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      <p>Tidak ada notifikasi.</p>
    </div>

    <div v-else class="space-y-2">
      <button
        v-for="notif in notifStore.notifications"
        :key="notif.id"
        class="w-full text-left flex items-start gap-4 p-4 bg-white rounded-xl border border-border shadow-sm hover:shadow-md transition-all"
        :class="{ 'bg-primary-50/50 border-primary-100': !notif.is_read }"
        @click="handleClick(notif)"
      >
        <div class="flex-shrink-0 mt-0.5">
          <div class="w-9 h-9 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </div>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-text-main">{{ notif.title }}</p>
          <p class="text-sm text-text-muted mt-0.5 line-clamp-2">{{ notif.body }}</p>
          <p class="text-xs text-text-muted mt-1">{{ timeAgo(notif.created_at) }}</p>
        </div>
        <div v-if="!notif.is_read" class="w-2 h-2 rounded-full bg-primary-500 flex-shrink-0 mt-2" />
      </button>
    </div>
  </div>
</template>

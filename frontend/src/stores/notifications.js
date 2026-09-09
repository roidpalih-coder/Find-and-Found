import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { notificationService } from '@/services/endpoints'

export const useNotificationStore = defineStore('notifications', () => {
  const notifications = ref([])
  const loading       = ref(false)
  let   pollingTimer  = null

  const unreadCount = computed(() => notifications.value.filter((n) => !n.is_read).length)

  async function fetchNotifications() {
    try {
      const { data } = await notificationService.list()
      notifications.value = data.data
    } catch {}
  }

  async function markRead(id) {
    await notificationService.read(id)
    const n = notifications.value.find((n) => n.id === id)
    if (n) n.is_read = true
  }

  async function markAllRead() {
    await notificationService.readAll()
    notifications.value.forEach((n) => { n.is_read = true })
  }

  function startPolling() {
    fetchNotifications()
    pollingTimer = setInterval(fetchNotifications, 30000)
  }

  function stopPolling() {
    if (pollingTimer) { clearInterval(pollingTimer); pollingTimer = null }
  }

  return { notifications, loading, unreadCount, fetchNotifications, markRead, markAllRead, startPolling, stopPolling }
})

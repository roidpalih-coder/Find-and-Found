<script setup>
import { ref, onMounted, watch } from 'vue'
import { adminService } from '@/services/endpoints'
import BaseAvatar from '@/components/base/BaseAvatar.vue'
import BaseBadge from '@/components/base/BaseBadge.vue'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseModal from '@/components/base/BaseModal.vue'
import BasePagination from '@/components/base/BasePagination.vue'

const users = ref([])
const meta = ref({})
const loading = ref(false)
const search = ref('')
const currentPage = ref(1)
const showRoleModal = ref(false)
const selectedUser = ref(null)
const roleLoading = ref(false)

function formatDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

async function load() {
  loading.value = true
  try {
    const params = { page: currentPage.value, per_page: 15 }
    if (search.value) params.search = search.value
    const { data } = await adminService.listUsers(params)
    users.value = data.data || []
    meta.value = data.meta || {}
  } finally {
    loading.value = false
  }
}

function openToggleRole(user) {
  selectedUser.value = user
  showRoleModal.value = true
}

async function confirmToggleRole() {
  if (!selectedUser.value) return
  roleLoading.value = true
  const newRole = selectedUser.value.role === 'admin' ? 'user' : 'admin'
  try {
    await adminService.updateUser(selectedUser.value.id, { role: newRole })
    showRoleModal.value = false
    await load()
  } finally {
    roleLoading.value = false
  }
}

let searchTimer = null
watch(search, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => { currentPage.value = 1; load() }, 400)
})
watch(currentPage, load)
onMounted(load)
</script>

<template>
  <div class="max-w-5xl mx-auto px-4 py-8 space-y-6">
    <h1 class="text-2xl font-bold text-text-main">Kelola Pengguna</h1>

    <input
      v-model="search"
      type="text"
      placeholder="Cari pengguna berdasarkan nama atau email..."
      class="w-full max-w-sm rounded-lg border border-border px-3 py-2.5 text-sm text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
    />

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 8" :key="i" class="h-14 bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else-if="users.length === 0" class="text-center py-20 text-text-muted">
      Tidak ada pengguna ditemukan.
    </div>

    <div v-else class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left border-b border-border">
            <th class="pb-3 pr-3 font-medium text-text-muted">Pengguna</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Email</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Peran</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Reputasi</th>
            <th class="pb-3 pr-3 font-medium text-text-muted">Terdaftar</th>
            <th class="pb-3 font-medium text-text-muted">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="border-b border-border last:border-0">
            <td class="py-3 pr-3">
              <div class="flex items-center gap-3">
                <BaseAvatar :src="user.avatar_url" :name="user.name" size="sm" />
                <span class="font-medium text-text-main">{{ user.name }}</span>
              </div>
            </td>
            <td class="py-3 pr-3 text-text-muted">{{ user.email }}</td>
            <td class="py-3 pr-3">
              <BaseBadge
                :type="user.role === 'admin' ? 'priority' : 'pending'"
                :label="user.role === 'admin' ? 'ADMIN' : 'USER'"
              />
            </td>
            <td class="py-3 pr-3 font-medium text-text-main">{{ user.reputation_points ?? 0 }}</td>
            <td class="py-3 pr-3 text-text-muted">{{ formatDate(user.created_at) }}</td>
            <td class="py-3">
              <BaseButton
                variant="secondary"
                size="sm"
                @click="openToggleRole(user)"
              >
                {{ user.role === 'admin' ? 'Jadikan User' : 'Jadikan Admin' }}
              </BaseButton>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <BasePagination
      v-if="meta?.last_page > 1"
      :current-page="currentPage"
      :last-page="meta.last_page"
      @change="p => { currentPage = p }"
    />

    <BaseModal :show="showRoleModal" title="Ubah Peran Pengguna" @close="showRoleModal = false">
      <p class="text-text-muted text-sm">
        Ubah peran <strong>{{ selectedUser?.name }}</strong> menjadi
        <strong>{{ selectedUser?.role === 'admin' ? 'User biasa' : 'Admin' }}</strong>?
      </p>
      <template #footer>
        <div class="flex justify-end gap-3">
          <BaseButton variant="secondary" @click="showRoleModal = false">Batal</BaseButton>
          <BaseButton variant="primary" :loading="roleLoading" @click="confirmToggleRole">Konfirmasi</BaseButton>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

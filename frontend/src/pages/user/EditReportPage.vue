<script setup>
import { onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useItemStore } from '@/stores/items'
import ReportForm from '@/components/common/ReportForm.vue'
import BaseAlert from '@/components/base/BaseAlert.vue'

const route = useRoute()
const router = useRouter()
const itemStore = useItemStore()

onMounted(async () => {
  await itemStore.fetchItem(route.params.id)
})

watch(() => itemStore.item, (newItem) => {
  if (newItem && newItem.status !== 'active') {
    router.push('/my-reports')
  }
})
</script>

<template>
  <div class="ff-container py-10 max-w-3xl">
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-text-main">Edit Laporan</h1>
      <p class="text-sm text-text-muted mt-1">Perbarui detail laporan barang Anda.</p>
    </div>

    <div v-if="itemStore.loading" class="animate-pulse space-y-4">
      <div class="h-10 bg-surface-bg rounded w-3/4"></div>
      <div class="h-32 bg-surface-bg rounded"></div>
      <div class="h-10 bg-surface-bg rounded w-1/4"></div>
    </div>

    <div v-else-if="itemStore.error">
      <BaseAlert type="error" :message="itemStore.error" />
    </div>

    <ReportForm
      v-else-if="itemStore.item"
      :type="itemStore.item.type"
      :is-edit="true"
      :initial-data="itemStore.item"
    />
  </div>
</template>

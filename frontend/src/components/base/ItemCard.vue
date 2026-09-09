<script setup>
import { RouterLink } from 'vue-router'
import BaseBadge from './BaseBadge.vue'

defineProps({
  item: { type: Object, required: true },
})

function formatDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>

<template>
  <RouterLink :to="`/items/${item.id}`" class="group block bg-white rounded-xl border border-border shadow-sm hover:shadow-md hover:border-primary-200 transition-all duration-200 overflow-hidden">
    <div class="relative aspect-[4/3] bg-surface-bg overflow-hidden">
      <img
        v-if="item.primary_photo_url"
        :src="item.primary_photo_url"
        :alt="item.title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        loading="lazy"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-text-muted">
        <svg class="w-12 h-12 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
      </div>
      <div class="absolute top-2 left-2 flex gap-1.5">
        <BaseBadge :type="item.type" />
        <BaseBadge v-if="item.is_priority_document" type="priority" label="PRIORITAS" />
      </div>
      <div v-if="item.status === 'resolved'" class="absolute inset-0 bg-success-500/20 flex items-center justify-center">
        <BaseBadge type="resolved" />
      </div>
    </div>

    <div class="p-4">
      <p class="font-semibold text-text-main text-sm line-clamp-2 group-hover:text-primary-600 transition-colors">{{ item.title }}</p>
      <div class="mt-2 flex items-center gap-1 text-xs text-text-muted">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        <span class="truncate">{{ item.district || item.location_name || '-' }}</span>
      </div>
      <div class="mt-1 flex items-center gap-1 text-xs text-text-muted">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
        <span>{{ formatDate(item.incident_date) }}</span>
      </div>
    </div>
  </RouterLink>
</template>

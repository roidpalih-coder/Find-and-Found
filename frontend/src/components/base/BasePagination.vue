<script setup>
const props = defineProps({
  currentPage: { type: Number, required: true },
  lastPage:    { type: Number, required: true },
})
const emit = defineEmits(['change'])

function pages() {
  const p = []
  for (let i = 1; i <= props.lastPage; i++) p.push(i)
  return p
}
</script>

<template>
  <div v-if="lastPage > 1" class="flex items-center justify-center gap-1 flex-wrap">
    <button
      @click="emit('change', currentPage - 1)"
      :disabled="currentPage === 1"
      class="px-3 py-2 rounded-lg text-sm font-medium text-text-muted hover:bg-surface-bg disabled:opacity-40 disabled:cursor-not-allowed transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center"
      aria-label="Halaman sebelumnya"
    >&#8592;</button>

    <button
      v-for="p in pages()"
      :key="p"
      @click="emit('change', p)"
      :class="[
        'px-3 py-2 rounded-lg text-sm font-medium transition-colors min-w-[44px] min-h-[44px]',
        p === currentPage ? 'bg-primary-500 text-white' : 'text-text-muted hover:bg-surface-bg'
      ]"
      :aria-current="p === currentPage ? 'page' : undefined"
    >{{ p }}</button>

    <button
      @click="emit('change', currentPage + 1)"
      :disabled="currentPage === lastPage"
      class="px-3 py-2 rounded-lg text-sm font-medium text-text-muted hover:bg-surface-bg disabled:opacity-40 disabled:cursor-not-allowed transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center"
      aria-label="Halaman berikutnya"
    >&#8594;</button>
  </div>
</template>

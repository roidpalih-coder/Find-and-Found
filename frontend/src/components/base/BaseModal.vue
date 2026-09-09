<script setup>
defineProps({
  show:  { type: Boolean, required: true },
  title: { type: String,  default: '' },
  size:  { type: String,  default: 'md' },
})
defineEmits(['close'])

const sizeClass = { sm: 'max-w-sm', md: 'max-w-lg', lg: 'max-w-2xl' }
</script>

<template>
  <Teleport to="body">
    <Transition name="modal">
      <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="$emit('close')">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('close')" />
        <div :class="['relative bg-white rounded-2xl shadow-xl w-full', sizeClass[size]]">
          <div v-if="title" class="flex items-center justify-between px-6 py-4 border-b border-border">
            <h2 class="text-lg font-semibold text-text-main">{{ title }}</h2>
            <button @click="$emit('close')" class="p-2 text-text-muted hover:text-text-main transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center rounded-lg hover:bg-surface-bg" aria-label="Tutup">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
          </div>
          <div class="px-6 py-5"><slot /></div>
          <div v-if="$slots.footer" class="px-6 pb-5"><slot name="footer" /></div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity .2s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>

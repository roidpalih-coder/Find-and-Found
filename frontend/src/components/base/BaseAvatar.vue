<script setup>
const props = defineProps({
  src:  { type: String,  default: '' },
  name: { type: String,  default: '' },
  size: { type: String,  default: 'md' },
})

const sizeClass = { xs: 'w-6 h-6 text-xs', sm: 'w-8 h-8 text-sm', md: 'w-10 h-10 text-base', lg: 'w-14 h-14 text-lg', xl: 'w-20 h-20 text-2xl' }
const initials  = () => props.name?.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase() || '?'
</script>

<template>
  <div :class="['rounded-full flex-shrink-0 overflow-hidden', sizeClass[size]]">
    <img v-if="src" :src="src" :alt="name" class="w-full h-full object-cover" />
    <div v-else class="w-full h-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold">
      {{ initials() }}
    </div>
  </div>
</template>

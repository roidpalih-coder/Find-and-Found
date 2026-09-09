<script setup>
defineProps({
  padding: { type: String, default: 'p-6' },
  shadow:  { type: Boolean, default: true },
})
</script>

<template>
  <div :class="['bg-white rounded-xl border border-border', padding, shadow && 'shadow-sm']">
    <slot />
  </div>
</template>

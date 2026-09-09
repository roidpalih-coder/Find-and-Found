<script setup>
defineProps({
  type:    { type: String, default: 'info' },
  message: { type: String, required: true },
})

const styles = {
  info:    'bg-primary-50 text-primary-700 border border-primary-100',
  success: 'bg-success-500/10 text-success-600 border border-success-500/30',
  warning: 'bg-warning-500/10 text-warning-600 border border-warning-500/30',
  error:   'bg-danger-500/10 text-danger-600 border border-danger-500/30',
}
</script>

<template>
  <div :class="['rounded-lg px-4 py-3 text-sm font-medium', styles[type]]" role="alert">
    {{ message }}
  </div>
</template>

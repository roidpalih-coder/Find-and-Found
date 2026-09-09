<script setup>
defineProps({
  variant: { type: String, default: 'primary' },
  size:    { type: String, default: 'md' },
  loading: { type: Boolean, default: false },
  disabled:{ type: Boolean, default: false },
  type:    { type: String, default: 'button' },
})

const variantClass = {
  primary:   'bg-primary-500 text-white hover:bg-primary-600 focus-visible:ring-primary-500',
  secondary: 'bg-white text-text-main border border-border hover:bg-surface-bg focus-visible:ring-primary-500',
  danger:    'bg-danger-500 text-white hover:bg-danger-600 focus-visible:ring-danger-500',
  ghost:     'text-text-muted hover:bg-surface-bg focus-visible:ring-primary-500',
  whatsapp:  'bg-whatsapp text-white hover:opacity-90 focus-visible:ring-green-400',
  instagram: 'bg-instagram text-white hover:opacity-90 focus-visible:ring-pink-400',
}

const sizeClass = {
  sm: 'px-3 py-1.5 text-xs min-h-[36px]',
  md: 'px-4 py-2 text-sm min-h-[44px]',
  lg: 'px-6 py-3 text-base min-h-[48px]',
}
</script>

<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
      variantClass[variant],
      sizeClass[size],
    ]"
  >
    <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
    <slot />
  </button>
</template>

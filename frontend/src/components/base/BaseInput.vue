<script setup>
defineProps({
  label:       { type: String,  default: '' },
  type:        { type: String,  default: 'text' },
  placeholder: { type: String,  default: '' },
  error:       { type: String,  default: '' },
  modelValue:  { default: '' },
  required:    { type: Boolean, default: false },
})
defineEmits(['update:modelValue'])
const id = `input-${Math.random().toString(36).slice(2)}`
</script>

<template>
  <div class="flex flex-col gap-1">
    <label v-if="label" :for="id" class="text-sm font-medium text-text-main">
      {{ label }} <span v-if="required" class="text-danger-500">*</span>
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :class="[
        'w-full rounded-lg border px-3 py-2.5 text-sm text-text-main placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition min-h-[44px]',
        error ? 'border-danger-500 bg-danger-500/5' : 'border-border bg-white'
      ]"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="error" class="text-xs text-danger-500">{{ error }}</p>
  </div>
</template>

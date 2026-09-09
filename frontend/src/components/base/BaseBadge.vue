<script setup>
defineProps({
  type:  { type: String, default: 'found' },
  label: { type: String, default: '' },
})

const styles = {
  lost:     'bg-warning-500/10 text-warning-600 border border-warning-500/30',
  found:    'bg-primary-50 text-primary-700 border border-primary-100',
  resolved: 'bg-success-500/10 text-success-600 border border-success-500/30',
  priority: 'bg-danger-500/10 text-danger-600 border border-danger-500/30',
  pending:  'bg-gray-100 text-gray-600 border border-gray-200',
  approved: 'bg-success-500/10 text-success-600 border border-success-500/30',
  rejected: 'bg-danger-500/10 text-danger-600 border border-danger-500/30',
}

const labels = {
  lost:     'HILANG',
  found:    'DITEMUKAN',
  resolved: 'SELESAI',
  priority: 'PRIORITAS',
  pending:  'MENUNGGU',
  approved: 'DISETUJUI',
  rejected: 'DITOLAK',
}
</script>

<template>
  <span :class="['inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold tracking-wide', styles[type] || styles.found]">
    {{ label || labels[type] || type.toUpperCase() }}
  </span>
</template>

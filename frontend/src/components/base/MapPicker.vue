<script setup>
import { onMounted, onUnmounted, ref } from 'vue'

const props = defineProps({
  lat:       { type: Number, default: -6.7615 },
  lng:       { type: Number, default: 111.0135 },
  readonly:  { type: Boolean, default: false },
  zoom:      { type: Number, default: 13 },
})
const emit = defineEmits(['update:coords'])

const mapEl   = ref(null)
let   mapInst = null
let   marker  = null

onMounted(async () => {
  const L = await import('leaflet')
  await import('leaflet/dist/leaflet.css')

  delete L.Icon.Default.prototype._getIconUrl
  L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl:       'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl:     'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
  })

  mapInst = L.map(mapEl.value).setView([props.lat, props.lng], props.zoom)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
  }).addTo(mapInst)

  marker = L.marker([props.lat, props.lng], { draggable: !props.readonly }).addTo(mapInst)

  if (!props.readonly) {
    marker.on('dragend', (e) => {
      const { lat, lng } = e.target.getLatLng()
      emit('update:coords', { lat, lng })
    })
    mapInst.on('click', (e) => {
      marker.setLatLng(e.latlng)
      emit('update:coords', { lat: e.latlng.lat, lng: e.latlng.lng })
    })
  }
})

onUnmounted(() => { if (mapInst) mapInst.remove() })
</script>

<template>
  <div ref="mapEl" class="w-full rounded-xl overflow-hidden border border-border" style="min-height: 300px;" />
</template>

import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useReportStore = defineStore('report', () => {
  const step = ref(1)
  const form = ref({
    type:           '',
    category_id:    null,
    title:          '',
    description:    '',
    secret_details: '',
    primary_photo:  null,
    photos:         [],
    latitude:       null,
    longitude:      null,
    location_name:  '',
    district:       '',
    incident_date:  '',
    reward_offered: '',
  })

  function nextStep() { if (step.value < 5) step.value++ }
  function prevStep() { if (step.value > 1) step.value-- }
  function goToStep(n) { step.value = n }

  function reset() {
    step.value = 1
    form.value = {
      type: '', category_id: null, title: '', description: '',
      secret_details: '', primary_photo: null, photos: [],
      latitude: null, longitude: null, location_name: '',
      district: '', incident_date: '', reward_offered: '',
    }
  }

  return { step, form, nextStep, prevStep, goToStep, reset }
})

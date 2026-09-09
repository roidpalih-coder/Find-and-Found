<script setup>
import { ref, watch, onMounted } from 'vue'
import { adminService } from '@/services/endpoints'
import BaseButton from '@/components/base/BaseButton.vue'
import BaseModal from '@/components/base/BaseModal.vue'
import BaseInput from '@/components/base/BaseInput.vue'
import BaseBadge from '@/components/base/BaseBadge.vue'

const categories = ref([])
const loading = ref(false)
const showFormModal = ref(false)
const showDeleteModal = ref(false)
const editingCategory = ref(null)
const selectedCategory = ref(null)
const formLoading = ref(false)
const deleteLoading = ref(false)

const form = ref({
  name: '',
  slug: '',
  icon: '',
  is_priority_document: false,
})

function slugify(text) {
  return text
    .toString()
    .toLowerCase()
    .trim()
    .replace(/\s+/g, '-')
    .replace(/[^\w-]+/g, '')
    .replace(/--+/g, '-')
}

watch(() => form.value.name, (val) => {
  if (!editingCategory.value) {
    form.value.slug = slugify(val)
  }
})

async function load() {
  loading.value = true
  try {
    const { data } = await adminService.listCategories()
    categories.value = data.data || []
  } finally {
    loading.value = false
  }
}

function openAdd() {
  editingCategory.value = null
  form.value = { name: '', slug: '', icon: '', is_priority_document: false }
  showFormModal.value = true
}

function openEdit(cat) {
  editingCategory.value = cat
  form.value = {
    name: cat.name,
    slug: cat.slug,
    icon: cat.icon || '',
    is_priority_document: Boolean(cat.is_priority_document),
  }
  showFormModal.value = true
}

function openDelete(cat) {
  selectedCategory.value = cat
  showDeleteModal.value = true
}

async function saveForm() {
  formLoading.value = true
  try {
    if (editingCategory.value) {
      await adminService.updateCategory(editingCategory.value.id, form.value)
    } else {
      await adminService.createCategory(form.value)
    }
    showFormModal.value = false
    await load()
  } finally {
    formLoading.value = false
  }
}

async function confirmDelete() {
  if (!selectedCategory.value) return
  deleteLoading.value = true
  try {
    await adminService.deleteCategory(selectedCategory.value.id)
    showDeleteModal.value = false
    await load()
  } finally {
    deleteLoading.value = false
  }
}

onMounted(load)
</script>

<template>
  <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-text-main">Kelola Kategori</h1>
      <BaseButton variant="primary" @click="openAdd">+ Tambah Kategori</BaseButton>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="i in 5" :key="i" class="h-14 bg-surface-bg rounded-xl animate-pulse" />
    </div>

    <div v-else class="space-y-2">
      <div
        v-for="cat in categories"
        :key="cat.id"
        class="flex items-center justify-between p-4 bg-white rounded-xl border border-border shadow-sm"
      >
        <div class="flex items-center gap-3">
          <span class="text-2xl">{{ cat.icon || '📁' }}</span>
          <div>
            <div class="flex items-center gap-2">
              <p class="font-semibold text-text-main text-sm">{{ cat.name }}</p>
              <BaseBadge
                v-if="cat.is_priority_document"
                type="priority"
                label="DOKUMEN PENTING"
              />
            </div>
            <p class="text-xs text-text-muted mt-0.5">{{ cat.slug }}</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <BaseButton variant="secondary" size="sm" @click="openEdit(cat)">Edit</BaseButton>
          <BaseButton variant="danger" size="sm" @click="openDelete(cat)">Hapus</BaseButton>
        </div>
      </div>
    </div>

    <BaseModal
      :show="showFormModal"
      :title="editingCategory ? 'Edit Kategori' : 'Tambah Kategori'"
      @close="showFormModal = false"
    >
      <form class="space-y-4" @submit.prevent="saveForm">
        <BaseInput v-model="form.name" label="Nama Kategori" placeholder="cth. Dompet & Tas" required />
        <BaseInput v-model="form.slug" label="Slug" placeholder="cth. dompet-tas" required />
        <BaseInput v-model="form.icon" label="Ikon (Emoji atau Teks)" placeholder="cth. 👜" />

        <div class="flex items-center gap-3 pt-2">
          <input
            id="priority_doc"
            v-model="form.is_priority_document"
            type="checkbox"
            class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-border"
          />
          <label for="priority_doc" class="text-sm font-medium text-text-main select-none">
            Kategori Dokumen Prioritas (KTP, SIM, Paspor, dll.)
          </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-border">
          <BaseButton variant="secondary" @click="showFormModal = false">Batal</BaseButton>
          <BaseButton type="submit" variant="primary" :loading="formLoading">Simpan</BaseButton>
        </div>
      </form>
    </BaseModal>

    <BaseModal :show="showDeleteModal" title="Hapus Kategori" @close="showDeleteModal = false">
      <p class="text-text-muted text-sm">Hapus kategori <strong>{{ selectedCategory?.name }}</strong>?</p>
      <template #footer>
        <div class="flex justify-end gap-3">
          <BaseButton variant="secondary" @click="showDeleteModal = false">Batal</BaseButton>
          <BaseButton variant="danger" :loading="deleteLoading" @click="confirmDelete">Hapus</BaseButton>
        </div>
      </template>
    </BaseModal>
  </div>
</template>

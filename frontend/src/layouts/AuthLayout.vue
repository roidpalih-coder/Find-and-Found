<script setup>
import { RouterView, RouterLink } from 'vue-router'
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-primary-600 to-primary-700 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <RouterLink to="/" class="inline-flex items-center gap-2 font-extrabold text-2xl text-white">
          <span class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white text-lg font-bold">F</span>
          Find &amp; Found
        </RouterLink>
        <p class="mt-2 text-sm text-primary-100">Temukan yang Hilang, Kembalikan dengan Tenang.</p>
      </div>
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <RouterView />
      </div>
    </div>
  </div>
</template>

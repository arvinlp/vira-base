<script setup>
import { defineProps } from 'vue'
import { usePage } from '@inertiajs/vue3'
const props = defineProps({
  toggleSidebar: Function,
})
</script>

<template>
  <header class="flex items-center justify-between px-6 py-3 bg-white shadow">
    <div class="flex items-center gap-4">
      <button class="p-2 rounded hover:bg-gray-100 md:hidden" @click="$emit('toggleSidebar')">
        <i class="pi pi-bars"></i>
      </button>
      <h1 class="font-bold text-lg">Panel</h1>
    </div>

    <div class="flex items-center gap-4">
      <span>{{ $page.props.auth.user.nickname }}</span>
      <form :action="route('logout')" method="POST">
        <button type="submit" class="p-2 rounded hover:bg-gray-100">
          <i class="pi pi-sign-out"></i>
        </button>
      </form>
    </div>
  </header>
</template>

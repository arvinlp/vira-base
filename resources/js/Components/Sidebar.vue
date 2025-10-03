<script setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const menuItems = ref([
  { label: 'Dashboard', icon: 'pi pi-home', link: route('panel.dashboard') },
  { label: 'Staffs', icon: 'pi pi-users', link: route('panel.staffs.index') },
  { label: 'Clients', icon: 'pi pi-user', link: route('panel.clients.index') },
  // { label: 'Profile', icon: 'pi pi-id-card', link: route('panel.profile') },
  // { label: 'Settings', icon: 'pi pi-cog', link: route('settings') },
])
</script>

<template>
  <aside class="w-64 bg-white shadow-md">
    <div class="p-4 font-bold text-lg border-b">Vira Panel</div>
    <ul>
      <li v-for="item in menuItems" :key="item.label">
        <a
          :href="item.link"
          class="flex items-center p-3 hover:bg-gray-100"
          :class="{'bg-gray-200': page.url === item.link }"
        >
          <i :class="item.icon" class="mr-2"></i>
          <span>{{ item.label }}</span>
        </a>
      </li>
    </ul>
  </aside>
</template>

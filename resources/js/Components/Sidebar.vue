<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

// آیتم‌های منو
const menuItems = ref([
  { label: "Dashboard", icon: "pi pi-home", link: route("panel.dashboard") },
  { label: "Staffs", icon: "pi pi-users", link: route("panel.staffs.index") },
  { label: "Clients", icon: "pi pi-user", link: route("panel.clients.index") },
]);

// Props برای موبایل و دارک مود
const props = defineProps({
  mobileOpen: { type: Boolean, default: false },
  isDark: { type: Boolean, default: false },
});

// Emits برای بستن منو
const emit = defineEmits(["close"]);
</script>

<template>
  <aside
    class="hidden md:flex w-64 flex-col shadow-md"
    :class="
      props.isDark ? 'bg-gray-800 text-gray-100' : 'bg-white text-gray-900'
    "
  >
    <div
      class="p-4 font-bold text-lg border-b"
      :class="props.isDark ? 'border-gray-700' : 'border-gray-200'"
    >
      Vira Panel
    </div>
    <ul>
      <li v-for="item in menuItems" :key="item.label">
        <a
          :href="item.link"
          class="flex items-center p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
          :class="
            page.url === item.link
              ? props.isDark
                ? 'bg-gray-700'
                : 'bg-gray-200'
              : ''
          "
        >
          <i :class="item.icon" class="mr-2"></i>
          <span>{{ item.label }}</span>
        </a>
      </li>
    </ul>
  </aside>

  <transition name="slide">
    <div v-if="props.mobileOpen" class="fixed inset-0 z-50 flex md:hidden">
      <div class="bg-black bg-opacity-50 flex-1" @click="emit('close')"></div>

      <div
        class="w-64 flex flex-col shadow-md"
        :class="
          props.isDark ? 'bg-gray-800 text-gray-100' : 'bg-white text-gray-900'
        "
      >
        <div
          class="p-4 font-bold text-lg border-b flex justify-between items-center"
          :class="props.isDark ? 'border-gray-700' : 'border-gray-200'"
        >
          Vira Panel
          <button
            @click="emit('close')"
            class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            <i class="pi pi-times"></i>
          </button>
        </div>
        <ul>
          <li v-for="item in menuItems" :key="item.label">
            <a
              :href="item.link"
              class="flex items-center p-3 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
              :class="
                page.url === item.link
                  ? props.isDark
                    ? 'bg-gray-700'
                    : 'bg-gray-200'
                  : ''
              "
              @click="emit('close')"
            >
              <i :class="item.icon" class="mr-2"></i>
              <span>{{ item.label }}</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from {
  transform: translateX(-100%);
}
.slide-enter-to {
  transform: translateX(0%);
}
.slide-leave-from {
  transform: translateX(0%);
}
.slide-leave-to {
  transform: translateX(-100%);
}
</style>
-->
<template>
  <div class="flex h-screen" :class="isDark ? 'bg-gray-900' : 'bg-gray-100'">
    <!-- Sidebar -->
    <Sidebar
      :isDark="isDark"
      :mobileOpen="mobileOpen"
      @close="mobileOpen = false"
    />

    <div class="flex-1 flex flex-col">
      <!-- Navbar -->
      <Navbar
        :toggleSidebar="toggleSidebar"
        :isDark="isDark"
        @toggleDarkMode="toggleDarkMode"
      />
      <main class="p-6 flex-1 overflow-y-auto">
        <slot />
      </main>
    </div>

    <Toast />
    <Modal />
    <Dialog />
  </div>
</template>

<script setup>
import { ref, onMounted, provide } from "vue";
import Sidebar from "@/Components/Sidebar.vue";
import Navbar from "@/Components/Navbar.vue";
import Toast from "@/Components/Toast.vue";
import Modal from "@/Components/Modal.vue";
import Dialog from "@/Components/Dialog.vue";

const mobileOpen = ref(false);

function toggleSidebar() {
  mobileOpen.value = !mobileOpen.value;
}

const isDark = ref(false);

function toggleDarkMode() {
  isDark.value = !isDark.value;
  localStorage.setItem("darkMode", isDark.value);
  document.documentElement.classList.toggle("my-app-dark", isDark.value);
}

onMounted(() => {
  isDark.value = localStorage.getItem("darkMode") === "true";
  document.documentElement.classList.toggle("my-app-dark", isDark.value);
});
provide("isDark", isDark);
provide("toggleDarkMode", toggleDarkMode);
</script>

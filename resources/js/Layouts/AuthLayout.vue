<template>
  <div
    :class="[
      'flex items-center justify-center min-h-screen relative',
      isDark ? 'bg-gray-900' : 'bg-gray-50',
    ]"
  >
    <div
      :class="[
        'w-full max-w-md p-8 rounded-2xl shadow',
        isDark ? 'bg-gray-800 text-white' : 'bg-white text-gray-900',
      ]"
    >
      <slot />
    </div>

    <!-- Toggle Dark Mode Button -->
    <button
      class="absolute top-8 right-4 p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white shadow hover:opacity-80 transition"
      @click="toggleDarkMode"
    >
      <i :class="isDark ? 'pi pi-sun' : 'pi pi-moon'"></i>
    </button>

    <Toast />
    <Modal />
    <Dialog />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import Toast from "@/Components/Toast.vue";
import Modal from "@/Components/Modal.vue";
import Dialog from "@/Components/Dialog.vue";
import Button from "primevue/button";

const isDark = ref(false);

onMounted(() => {
  isDark.value = localStorage.getItem("darkMode") === "true";
  document.documentElement.classList.toggle("my-app-dark", isDark.value);
});

// اگر حالت تغییر کرد، localStorage و کلاس HTML رو آپدیت کن
const toggleDarkMode = () => {
  isDark.value = !isDark.value;
  localStorage.setItem("darkMode", isDark.value);
  document.documentElement.classList.toggle("my-app-dark", isDark.value);
};
</script>

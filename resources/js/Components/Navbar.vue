<template>
  <header
    class="flex items-center justify-between px-6 py-3 shadow"
    :class="isDark ? 'bg-gray-800' : 'bg-white'"
  >
    <div class="flex items-center gap-2">
      <div class="md:hidden">
        <!-- دکمه باز کردن منو در موبایل -->
        <Button
          icon="pi pi-bars"
          class="p-button-rounded p-button-text"
          @click="$emit('toggleSidebar')"
        />

        <h1
          class="font-bold text-lg"
          :class="isDark ? 'text-gray-100' : 'text-gray-800'"
        >
          Vira Panel
        </h1>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <Button
        :icon="isDark ? 'pi pi-sun' : 'pi pi-moon'"
        class="p-button-rounded p-button-text"
        @click="toggleDarkMode"
      />

      <button
        @click="logout"
        class="p-2 rounded"
        :class="isDark ? 'hover:bg-gray-700' : 'hover:bg-gray-100'"
      >
        <i
          class="pi pi-sign-out"
          :class="isDark ? 'text-gray-100' : 'text-gray-800'"
        ></i>
      </button>
    </div>
  </header>
</template>

<script setup>
import Button from "primevue/button";
import { inject } from "vue";
import { router } from "@inertiajs/vue3";

const isDark = inject("isDark");
const toggleDarkMode = inject("toggleDarkMode");

function logout() {
  const token = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

  fetch(route("logout"), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": token,
      Accept: "application/json",
    },
    credentials: "same-origin",
  }).then(() => {
    router.visit(route("login"));
  });
}
</script>

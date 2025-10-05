<!-- resources/js/Components/StatCard.vue -->
<script setup>
import { Link } from "@inertiajs/vue3";
import { inject } from "vue";

const isDark = inject("isDark");

defineProps({
  title: String,
  icon: String,
  value: [String, Number],
  route: String,
  isDark: Boolean,
});
</script>

<template>
  <Link
    :href="route"
    class="shadow p-4 rounded hover:shadow-md transition block"
    :class="isDark ? 'bg-gray-800 text-white' : 'bg-white text-gray-900'"
  >
    <h3 class="text-sm text-gray-500 flex items-center gap-2">
      <i :class="icon"></i>
      {{ title }}
    </h3>
    <p class="text-2xl font-bold text-center">
      {{ value }}
    </p>
  </Link>
</template>

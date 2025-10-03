<template>
  <Head title="Dashboard" />
  <AppLayout>
    <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <Link
        :href="route('staffs.index')"
        class="bg-white shadow p-4 rounded hover:shadow-md transition block"
      >
        <h3 class="text-sm text-gray-500 flex items-center gap-2">
          <i class="pi pi-users"></i>
          Staffs
        </h3>
        <p class="text-2xl font-bold text-center">
          {{ stats.staffs }}
        </p>
      </Link>

      <Link
        :href="route('clients.index')"
        class="bg-white shadow p-4 rounded hover:shadow-md transition block"
      >
        <h3 class="text-sm text-gray-500 flex items-center gap-2">
          <i class="pi pi-user"></i>
          Clients
        </h3>
        <p class="text-2xl font-bold text-center">
          {{ stats.users }}
        </p>
      </Link>

      <Link
        :href="route('activities.index')"
        class="bg-white shadow p-4 rounded hover:shadow-md transition block"
      >
        <h3 class="text-sm text-gray-500 flex items-center gap-2">
          <i class="pi pi-bars"></i>
          Activities
        </h3>
        <p class="text-2xl font-bold text-center">
          {{ stats.activities }}
        </p>
      </Link>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { Head, usePage, Link } from "@inertiajs/vue3";

const page = usePage();

const stats = ref({
  staffs: page.props.stats.staffs || 0,
  users: page.props.stats.users || 0,
  activities: page.props.stats.activities || 0,
});
</script>

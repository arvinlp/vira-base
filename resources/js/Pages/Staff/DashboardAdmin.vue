<template>
  <Head title="Dashboard" />
  <AppLayout>
    <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
      <StatCard
        title="Staffs"
        icon="pi pi-users"
        :value="stats.staffs"
        :route="route('panel.staffs.index')"
      />
      <StatCard
        title="Clients"
        icon="pi pi-user"
        :value="stats.users"
        :route="route('panel.clients.index')"
      />
      <StatCard
        title="Tenants"
        icon="pi pi-bars"
        :value="stats.tenants"
        :route="route('panel.tenants.index')"
      />
      <StatCard
        title="Activities"
        icon="pi pi-bars"
        :value="stats.activities"
        :route="route('panel.clients.index')"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import StatCard from "@/Components/StatCard.vue";
import { ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";

const page = usePage();

const stats = ref({
  staffs: page.props.stats.staffs || 0,
  users: page.props.stats.users || 0,
  activities: page.props.stats.activities || 0,
});
</script>

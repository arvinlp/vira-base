<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";

const page = usePage();
const tenant = page.props.tenant;
const user = page.props.auth.user;
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            You're logged in!

            <p>Hi, {{ user?.first_name || "Guest" }}</p>
            <p>Company: {{ tenant?.name || "Unknown" }}</p>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

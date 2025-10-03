<template>
  <Head title="Login" />
  <AuthLayout>
    <form @submit.prevent="submit" class="space-y-4">
      <h1 class="text-2xl font-bold mb-6">Login</h1>

      <div>
        <label class="block text-sm mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="input w-full"
          required
        />
      </div>

      <div>
        <label class="block text-sm mb-1">Password</label>
        <input
          v-model="form.password"
          type="password"
          class="input w-full"
          required
        />
      </div>

      <button type="submit" class="btn-primary w-full">Login</button>

      <div class="mt-4 text-sm text-center">
        You don't have an account?
        <Link :href="route('register')" class="text-blue-600">Register</Link>
      </div>
      <template v-if="stats.appDemo">
        <hr />
        <div class="mt-6">
          <p class="text-center text-gray-500 mb-3">Demo Login</p>
          <div class="grid grid-cols-3 gap-3">
            <button
              @click="demoLogin('admin')"
              class="bg-red-500 text-white py-2 rounded hover:bg-red-600"
            >
              Admin
            </button>
            <button
              @click="demoLogin('staff')"
              class="bg-green-500 text-white py-2 rounded hover:bg-green-600"
            >
              Staff
            </button>
            <button
              @click="demoLogin('client')"
              class="bg-blue-500 text-white py-2 rounded hover:bg-blue-600"
            >
              Client
            </button>
          </div>
        </div>
      </template>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { ref } from "vue";
import { Head, useForm, Link, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

const toast = useToast();
const page = usePage();

const form = useForm({
  email: "",
  password: "",
  remember: false,
});
//
function demoLogin(role) {
  let demoUsers = {
    admin: { email: "admin@example.com", password: "password" },
    staff: { email: "staff@example.com", password: "password" },
    client: { email: "client@example.com", password: "password" },
  };

  form.email = demoUsers[role].email;
  form.password = demoUsers[role].password;
  form.post(route("login"));
}
//
function submit() {
  form.post(route("login"), {
    onError: (errors) => {
      if (errors.email) {
        toast.add({
          severity: "error",
          summary: "Login Failed",
          detail: errors.email,
          life: 4000,
        });
      }
      if (errors.password) {
        toast.add({
          severity: "error",
          summary: "Login Failed",
          detail: errors.password,
          life: 4000,
        });
      }
    },
    onSuccess: () => {
      toast.add({
        severity: "success",
        summary: "Welcome",
        detail: "You are logged in successfully",
        life: 3000,
      });
    },
  });
}
//
const stats = ref({
  appDemo: page.props.appDemo || false,
});
</script>

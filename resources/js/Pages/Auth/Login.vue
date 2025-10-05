<template>
  <Head title="Login" />
  <AuthLayout>
    <form @submit.prevent="submit" class="space-y-4">
      <h1 class="text-2xl font-bold mb-6">Login</h1>

      <div>
        <label class="block text-sm mb-1">Email</label>
        <InputText
          v-model="form.email"
          type="email"
          class="input w-full"
          required
        />
      </div>

      <div>
        <label class="block text-sm mb-1">Password</label>
        <InputText
          v-model="form.password"
          type="password"
          class="input w-full"
          required
        />
      </div>

      <Button type="submit" class="p-button-primary w-full" raised>
        <i class="pi pi-sign-in mr-2"></i>
        Login
      </Button>

      <div class="mt-4 text-sm text-center">
        You don't have an account?
        <Link :href="route('register')" class="text-blue-600">Register</Link>
      </div>
      <template v-if="stats.appDemo">
        <hr />
        <div class="mt-6">
          <p class="text-center text-gray-500 mb-3">Demo Login</p>
          <div class="grid grid-cols-3 gap-3">
            <Button
              @click="demoLogin('admin')"
              severity="warn"
              variant="outlined"
              raised
            >
              Admin
            </Button>
            <Button
              @click="demoLogin('staff')"
              severity="info"
              variant="outlined"
              raised
            >
              Staff
            </Button>
            <Button
              @click="demoLogin('client')"
              severity="help"
              variant="outlined"
              raised
            >
              Client
            </Button>
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
import Button from "primevue/button";
import InputText from "primevue/inputtext";

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

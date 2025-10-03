<template>
  <AuthLayout>
    <form @submit.prevent="submit" class="space-y-4">
      <h1 class="text-2xl font-bold mb-6">تأیید رمز عبور</h1>

      <div>
        <label class="block text-sm mb-1">رمز عبور</label>
        <input v-model="form.password" type="password" class="input w-full" required />
      </div>

      <button type="submit" class="btn-primary w-full">تأیید</button>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  password: '',
})

function submit() {
  form.post(route('password.confirm'))
}
</script>

<template>
  <AuthLayout>
    <form @submit.prevent="submit" class="space-y-4">
      <h1 class="text-2xl font-bold mb-6">بازیابی رمز عبور</h1>

      <div>
        <label class="block text-sm mb-1">ایمیل</label>
        <input v-model="form.email" type="email" class="input w-full" required />
      </div>

      <button type="submit" class="btn-primary w-full">ارسال لینک بازیابی</button>

      <div class="mt-4 text-sm text-center">
        <Link :href="route('login')" class="text-blue-600">بازگشت به ورود</Link>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({ email: '' })

function submit() {
  form.post(route('password.email'))
}
</script>

<template>
  <AuthLayout>
    <form @submit.prevent="submit" class="space-y-4">
      <h1 class="text-2xl font-bold mb-6">ثبت نام</h1>

      <div>
        <label class="block text-sm mb-1">نام</label>
        <input v-model="form.name" type="text" class="input w-full" required />
      </div>

      <div>
        <label class="block text-sm mb-1">ایمیل</label>
        <input v-model="form.email" type="email" class="input w-full" required />
      </div>

      <div>
        <label class="block text-sm mb-1">رمز عبور</label>
        <input v-model="form.password" type="password" class="input w-full" required />
      </div>

      <div>
        <label class="block text-sm mb-1">تکرار رمز عبور</label>
        <input v-model="form.password_confirmation" type="password" class="input w-full" required />
      </div>

      <button type="submit" class="btn-primary w-full">ثبت نام</button>

      <div class="mt-4 text-sm text-center">
        حساب داری؟ 
        <Link :href="route('login')" class="text-blue-600">ورود</Link>
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post(route('register'))
}
</script>

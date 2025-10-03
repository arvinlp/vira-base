<template>
  <AuthLayout>
    <form @submit.prevent="submit" class="space-y-4">
      <h1 class="text-2xl font-bold mb-6">تنظیم رمز عبور جدید</h1>

      <input type="hidden" v-model="form.token" />

      <div>
        <label class="block text-sm mb-1">ایمیل</label>
        <input v-model="form.email" type="email" class="input w-full" required />
      </div>

      <div>
        <label class="block text-sm mb-1">رمز عبور جدید</label>
        <input v-model="form.password" type="password" class="input w-full" required />
      </div>

      <div>
        <label class="block text-sm mb-1">تکرار رمز عبور</label>
        <input v-model="form.password_confirmation" type="password" class="input w-full" required />
      </div>

      <button type="submit" class="btn-primary w-full">ذخیره رمز عبور</button>
    </form>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const form = useForm({
  token: page.props.token,
  email: page.props.email,
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post(route('password.update'))
}
</script>

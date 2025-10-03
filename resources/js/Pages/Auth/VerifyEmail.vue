<template>
  <AuthLayout>
    <div class="space-y-4 text-center">
      <h1 class="text-2xl font-bold mb-4">تأیید ایمیل</h1>

      <p>قبل از ادامه لطفاً ایمیل خود را بررسی کنید و لینک تأیید را بزنید.</p>

      <p v-if="status === 'verification-link-sent'" class="text-green-600">
        لینک تأیید جدید ارسال شد.
      </p>

      <form @submit.prevent="resendVerification">
        <button type="submit" class="btn-secondary mt-2">
          ارسال دوباره لینک تأیید
        </button>
      </form>

      <form @submit.prevent="logout">
        <button type="submit" class="btn-text mt-4">خروج از حساب</button>
      </form>
    </div>
  </AuthLayout>
</template>

<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useForm, router, usePage } from "@inertiajs/vue3";

const page = usePage();
const status = page.props.status;

function resendVerification() {
  router.post(route("verification.send"));
}

function logout() {
  router.post(route("logout"));
}
</script>

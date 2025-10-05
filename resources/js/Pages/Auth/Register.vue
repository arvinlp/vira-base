<template>
  <Head title="Register" />
  <AuthLayout>
    <form @submit.prevent="submit">
      <h1 class="text-2xl font-bold text-center mb-6">Register</h1>

      <!-- First Name -->
      <div class="mb-4">
        <label class="block text-sm mb-1">First Name</label>
        <InputText v-model="form.first_name" required class="w-full" />
      </div>

      <!-- Last Name -->
      <div class="mb-4">
        <label class="block text-sm mb-1">Last Name</label>
        <InputText v-model="form.last_name" required class="w-full" />
      </div>

      <!-- Email -->
      <div class="mb-4">
        <label class="block text-sm mb-1">Email</label>
        <InputText
          v-model="form.email"
          type="email"
          @input="validateEmail"
          required
          class="w-full"
          placeholder="email@domain.tld"
        />
        <p v-if="emailError" class="text-red-500 text-sm mt-1">
          {{ emailError }}
        </p>
        <p v-else-if="emailValid" class="text-green-500 text-sm mt-1">
          Valid email
        </p>
      </div>

      <!-- Mobile -->
      <div class="mb-4">
        <label class="block text-sm mb-1">Mobile</label>
        <InputText
          v-model="form.mobile"
          @input="validateMobile"
          placeholder="09xxxxxxxxx"
          maxlength="11"
          minlength="11"
          required
          class="w-full"
        />
        <p v-if="mobileError" class="text-red-500 text-sm mt-1">
          {{ mobileError }}
        </p>
      </div>

      <!-- Password -->
      <div class="mb-4">
        <label class="block text-sm mb-1">Password</label>
        <InputGroup class="w-full">
          <InputText
            v-model="form.password"
            type="text"
            placeholder="*****"
            class="flex-1"
            @input="checkPasswordStrength"
          />
          <Button
            type="button"
            @click="generatePassword()"
            class="bg-blue-500 text-white px-3 rounded"
          >
            Generate
          </Button>
        </InputGroup>
        <p class="text-sm mt-1" :class="passwordStrengthColor">
          {{ passwordStrengthMessage }}
        </p>
      </div>

      <!-- Password Confirm -->
      <div class="mb-4">
        <label class="block text-sm mb-1">Confirm Password</label>
        <InputText
          v-model="form.password_confirmation"
          required
          class="w-full"
        />
      </div>

      <!-- Submit -->
      <Button type="submit" class="btn-primary w-full py-2">Register</Button>

      <!-- Login Link -->
      <div class="mt-4 text-sm text-center">
        Already have an account?
        <Link
          :href="route('login')"
          class="text-blue-600 font-medium hover:underline"
          >Login</Link
        >
      </div>
    </form>
  </AuthLayout>
</template>

<script setup>
import { ref } from "vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useForm, Link, Head } from "@inertiajs/vue3";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import InputGroup from "primevue/inputgroup";

const form = useForm({
  first_name: "",
  last_name: "",
  mobile: "",
  email: "",
  password: "",
  password_confirmation: "",
});

// Mobile validation
const mobileError = ref("");
function validateMobile() {
  const pattern = /^09\d{9}$/;
  mobileError.value =
    form.mobile && !pattern.test(form.mobile)
      ? "Mobile must be a valid Iranian number"
      : "";
}

// Email validation
const emailError = ref("");
const emailValid = ref(false);
function validateEmail() {
  const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!form.email) {
    emailError.value = "";
    emailValid.value = false;
  } else if (!pattern.test(form.email)) {
    emailError.value = "Invalid email format";
    emailValid.value = false;
  } else {
    emailError.value = "";
    emailValid.value = true;
  }
}

// Password strength
const passwordStrengthMessage = ref("");
const passwordStrengthColor = ref("");

function checkPasswordStrength() {
  const pw = form.password;
  if (!pw) {
    passwordStrengthMessage.value = "";
    passwordStrengthColor.value = "";
    return;
  }
  let score = 0;
  if (pw.length >= 6) score++;
  if (/[A-Z]/.test(pw)) score++;
  if (/[0-9]/.test(pw)) score++;
  if (/[^A-Za-z0-9]/.test(pw)) score++;

  if (score <= 1) {
    passwordStrengthMessage.value = "Weak";
    passwordStrengthColor.value = "text-red-500";
  } else if (score === 2 || score === 3) {
    passwordStrengthMessage.value = "Medium";
    passwordStrengthColor.value = "text-yellow-500";
  } else {
    passwordStrengthMessage.value = "Strong";
    passwordStrengthColor.value = "text-green-500";
  }
}

// Generate random password
function generatePassword(length = 12) {
  const chars =
    "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$";
  let password = "";
  for (let i = 0; i < length; i++) {
    password += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  form.password = password;
  form.password_confirmation = password;
  checkPasswordStrength();
}

// Submit
function submit() {
  form.post(route("register"));
}
</script>

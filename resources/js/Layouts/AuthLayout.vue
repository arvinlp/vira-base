<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow">
      <slot />
    </div>
    <Toast />
    <Modal />
    <Dialog />
  </div>
</template>

<script setup>
import Toast from '@/Components/Toast.vue'
import Modal from '@/Components/Modal.vue'
import Dialog from '@/Components/Dialog.vue'
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <Sidebar />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="p-6">
        <slot />
      </main>
    </div>
    <Toast />
    <Modal />
    <Dialog />
  </div>
</template>

<script setup>
import Sidebar from '@/Components/Sidebar.vue'
import Navbar from '@/Components/Navbar.vue'
import Toast from '@/Components/Toast.vue'
import Modal from '@/Components/Modal.vue'
import Dialog from '@/Components/Dialog.vue'
</script>

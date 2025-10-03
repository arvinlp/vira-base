<template>
  <Head title="Welcome" />

  <div class="min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-4 bg-white shadow">
      <div class="text-2xl font-bold text-indigo-600">MyPanel</div>
      <div class="flex gap-6">
        <a href="#features" class="hover:text-indigo-600">Features</a>
        <a href="#pricing" class="hover:text-indigo-600">Pricing</a>
        <a href="#contact" class="hover:text-indigo-600">Contact</a>
      </div>
      <template v-if="stats.auth">
        <div>
          <Link
            :href="route('panel.dashboard')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700"
          >
            Dashboard
          </Link>
        </div>
      </template>
      <template v-else>
        <div>
          <Link
            :href="route('login')"
            class="bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700"
          >
            Login
          </Link>
        </div>
      </template>
    </nav>

    <!-- Hero Section -->
    <header
      class="flex-1 flex flex-col justify-center items-center text-center bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-20"
    >
      <h1 class="text-5xl font-extrabold mb-6">
        Manage Everything in One Place
      </h1>
      <p class="text-xl mb-8 max-w-2xl">
        A modern and powerful dashboard built with Laravel + Inertia + Vue3 +
        Tailwind. Simple, fast, and scalable.
      </p>
      <div class="flex gap-4">
        <Link
          href="/register"
          class="bg-white text-indigo-600 px-6 py-3 rounded-lg shadow hover:bg-gray-100 font-semibold"
        >
          Get Started
        </Link>
        <a
          href="#features"
          class="px-6 py-3 border border-white rounded-lg font-semibold hover:bg-white hover:text-indigo-600"
        >
          Learn More
        </a>
      </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-16 px-6 bg-gray-50 text-center">
      <h2 class="text-3xl font-bold mb-12">Awesome Features</h2>
      <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
          <i class="pi pi-lock text-4xl text-indigo-600 mb-4"></i>
          <h3 class="font-semibold text-lg mb-2">Secure Auth</h3>
          <p class="text-gray-600">
            Authentication with Login, Register, Email Verify and Password
            Reset.
          </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
          <i class="pi pi-chart-line text-4xl text-indigo-600 mb-4"></i>
          <h3 class="font-semibold text-lg mb-2">Analytics</h3>
          <p class="text-gray-600">
            Track activity, logs and system performance with charts and reports.
          </p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
          <i class="pi pi-users text-4xl text-indigo-600 mb-4"></i>
          <h3 class="font-semibold text-lg mb-2">User Management</h3>
          <p class="text-gray-600">
            Create, edit and manage staff and customers with ease.
          </p>
        </div>
      </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 px-6 text-center">
      <h2 class="text-3xl font-bold mb-12">Simple Pricing</h2>
      <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition">
          <h3 class="text-xl font-semibold mb-4">Free</h3>
          <p class="text-3xl font-bold mb-6">$0</p>
          <ul class="text-gray-600 space-y-2 mb-6">
            <li>✔ Basic Features</li>
            <li>✔ Limited Access</li>
            <li>✔ Community Support</li>
          </ul>
          <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
            Get Started
          </button>
        </div>
        <div
          class="bg-indigo-600 text-white p-8 rounded-lg shadow-lg transform scale-105"
        >
          <h3 class="text-xl font-semibold mb-4">Pro</h3>
          <p class="text-3xl font-bold mb-6">$29/mo</p>
          <ul class="space-y-2 mb-6">
            <li>✔ All Features</li>
            <li>✔ Unlimited Users</li>
            <li>✔ Priority Support</li>
          </ul>
          <button class="bg-white text-indigo-600 px-6 py-2 rounded-lg">
            Get Pro
          </button>
        </div>
        <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition">
          <h3 class="text-xl font-semibold mb-4">Enterprise</h3>
          <p class="text-3xl font-bold mb-6">Custom</p>
          <ul class="text-gray-600 space-y-2 mb-6">
            <li>✔ Dedicated Server</li>
            <li>✔ Custom Solutions</li>
            <li>✔ 24/7 Support</li>
          </ul>
          <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
            Contact Us
          </button>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 px-6 bg-gray-100 text-center">
      <h2 class="text-3xl font-bold mb-8">Get in Touch</h2>
      <p class="mb-6 text-gray-600">Have questions? Feel free to reach out!</p>
      <form class="max-w-xl mx-auto space-y-4">
        <input
          type="text"
          placeholder="Your Name"
          class="w-full p-3 rounded border"
        />
        <input
          type="email"
          placeholder="Your Email"
          class="w-full p-3 rounded border"
        />
        <textarea
          rows="4"
          placeholder="Your Message"
          class="w-full p-3 rounded border"
        ></textarea>
        <button
          type="submit"
          class="bg-indigo-600 text-white px-6 py-2 rounded-lg"
        >
          Send
        </button>
      </form>
    </section>

    <!-- Footer -->
    <footer class="py-6 text-center text-gray-500 border-t">
      © 2025 MyPanel. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
const page = usePage();
//
const stats = ref({
  auth: page.props.auth || false,
});
</script>

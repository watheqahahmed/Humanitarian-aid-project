<template>
  <div :class="darkMode ? 'dark bg-gray-900 text-gray-100' : 'bg-gray-50 text-gray-900'">
    <!-- Navbar -->
    <header class="flex justify-between items-center px-6 py-4 shadow-md bg-white dark:bg-gray-800">
      <h1 class="text-xl font-bold text-orange-500">Aid Platform</h1>
      <div class="flex items-center gap-4">
        <button @click="toggleDarkMode"
                class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 hover:opacity-80">
          {{ darkMode ? '🌙' : '☀️' }}
        </button>
        <img src="https://ui-avatars.com/api/?name=Admin" alt="user"
             class="w-8 h-8 rounded-full border border-gray-300">
      </div>
    </header>

    <!-- Layout -->
    <div class="flex h-screen">
      <!-- Sidebar -->
      <aside class="w-64 bg-white dark:bg-gray-800 shadow-md p-4">
        <nav class="space-y-3">
          <a href="#" class="block px-3 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">
            Dashboard
          </a>
          <a href="#" class="block px-3 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">
            Users
          </a>
          <a href="#" class="block px-3 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">
            Donations
          </a>
          <a href="#" class="block px-3 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">
            Aid Requests
          </a>
          <a href="#" class="block px-3 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">
            Distributions
          </a>
          <a href="#" class="block px-3 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">
            Notifications
          </a>
        </nav>
      </aside>

      <!-- Content Area -->
      <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Card Example -->
          <div class="p-6 rounded-2xl shadow bg-white dark:bg-gray-700">
            <h3 class="text-lg font-medium">Total Donations</h3>
            <p class="text-3xl font-bold text-orange-500 mt-2">120</p>
          </div>
          <div class="p-6 rounded-2xl shadow bg-white dark:bg-gray-700">
            <h3 class="text-lg font-medium">Active Volunteers</h3>
            <p class="text-3xl font-bold text-teal-500 mt-2">34</p>
          </div>
          <div class="p-6 rounded-2xl shadow bg-white dark:bg-gray-700">
            <h3 class="text-lg font-medium">Beneficiaries</h3>
            <p class="text-3xl font-bold text-blue-500 mt-2">210</p>
          </div>
          <div class="p-6 rounded-2xl shadow bg-white dark:bg-gray-700">
            <h3 class="text-lg font-medium">Requests Pending</h3>
            <p class="text-3xl font-bold text-red-500 mt-2">15</p>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const darkMode = ref(false)
function toggleDarkMode() {
  darkMode.value = !darkMode.value
}
</script>

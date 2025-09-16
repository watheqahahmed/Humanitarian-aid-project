<template>
  <div class="flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 h-screen shadow-lg">
      <div class="p-6">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white mb-8">Humanitarian Aid</h1>
        <nav class="flex flex-col gap-3">
          <router-link
            v-for="link in links"
            :key="link.name"
            :to="link.path"
            class="py-2 px-4 rounded hover:bg-teal-600 hover:text-white dark:hover:bg-teal-500"
          >
            {{ link.name }}
          </router-link>
        </nav>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 min-h-screen bg-gray-50 dark:bg-gray-900">
      <!-- Top Navbar -->
      <div class="flex justify-between items-center p-4 bg-white dark:bg-gray-800 shadow">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ pageTitle }}</h2>
        <div class="flex items-center gap-4">
          <button @click="toggleDarkMode" class="px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded">
            {{ darkMode ? 'Light Mode' : 'Dark Mode' }}
          </button>
          <button @click="logout" class="px-3 py-1 bg-red-600 text-white rounded">Logout</button>
        </div>
      </div>

      <div class="p-6">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const darkMode = ref(false);

// Simulated user role (dynamic after login)
const user = ref({ role: "admin", name: "John Doe" });

const toggleDarkMode = () => {
  darkMode.value = !darkMode.value;
  document.documentElement.classList.toggle("dark", darkMode.value);
};

const logout = () => {
  router.push("/login");
};

// Sidebar links dynamic based on role
const links = computed(() => {
  if (user.value.role === "admin") {
    return [
      { name: "Dashboard", path: "/dashboard" },
      { name: "Users", path: "/dashboard/users" },
      { name: "Donations", path: "/dashboard/donations" },
      { name: "Distributions", path: "/dashboard/distributions" },
      { name: "Aid Requests", path: "/dashboard/aid-requests" },
    ];
  } else if (user.value.role === "volunteer") {
    return [
      { name: "Dashboard", path: "/dashboard" },
      { name: "Assigned Deliveries", path: "/dashboard/assigned-deliveries" },
    ];
  } else if (user.value.role === "beneficiary") {
    return [
      { name: "Dashboard", path: "/dashboard" },
      { name: "Aid Requests", path: "/dashboard/aid-requests" },
    ];
  }
  return [];
});

const pageTitle = computed(() => {
  return router.currentRoute.value.meta.title || "Dashboard";
});
</script>

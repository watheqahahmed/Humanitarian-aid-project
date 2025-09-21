<template>
  <aside class="w-64 min-h-screen bg-gray-800 text-white p-6 flex flex-col">
    <div class="text-2xl font-bold mb-6">Dashboard</div>
    <nav class="flex-1">
      <ul>
        <li v-if="authStore.user && authStore.user.role === 'admin'" class="mb-4">
          <router-link :to="{ name: 'admin.dashboard' }" class="block p-2 rounded hover:bg-gray-700">Admin Dashboard</router-link>
          <router-link :to="{ name: 'admin.donations' }" class="block p-2 rounded hover:bg-gray-700">Donations</router-link>
          <router-link :to="{ name: 'admin.aid-requests' }" class="block p-2 rounded hover:bg-gray-700">Aid Requests</router-link>
          <router-link :to="{ name: 'admin.distributions' }" class="block p-2 rounded hover:bg-gray-700">Distributions</router-link>
          <router-link :to="{ name: 'admin.users' }" class="block p-2 rounded hover:bg-gray-700">Users Management</router-link>
        </li>
        <li v-if="authStore.user && authStore.user.role === 'volunteer'" class="mb-4">
          <router-link :to="{ name: 'volunteer.dashboard' }" class="block p-2 rounded hover:bg-gray-700">Volunteer Dashboard</router-link>
          <router-link :to="{ name: 'volunteer.assigned-deliveries' }" class="block p-2 rounded hover:bg-gray-700">Assigned Deliveries</router-link>
        </li>
        <li v-if="authStore.user && authStore.user.role === 'beneficiary'" class="mb-4">
          <router-link :to="{ name: 'beneficiary.dashboard' }" class="block p-2 rounded hover:bg-gray-700">Beneficiary Dashboard</router-link>
          <router-link :to="{ name: 'beneficiary.aid-requests' }" class="block p-2 rounded hover:bg-gray-700">My Aid Requests</router-link>
        </li>
      </ul>
    </nav>
  </aside>
</template>
<script setup>
import { useAuthStore } from '../../stores/auth';
const authStore = useAuthStore();
</script>

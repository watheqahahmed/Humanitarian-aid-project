<template>
  <nav class="bg-white shadow-md p-4 w-full">
    <div class="flex justify-between items-center">
      <div class="text-2xl font-bold text-gray-800">
        <router-link :to="{ name: 'home' }">Charity App</router-link>
      </div>
      <div>
        <button @click="logout" v-if="authStore.isAuthenticated" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
          Logout
        </button>
        <router-link :to="{ name: 'login' }" v-else class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Login
        </router-link>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useAuthStore } from '../../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const logout = () => {
  authStore.clearAuth();
  router.push({ name: 'home' });
};
</script>

<template>
  <div class="flex">
    <Sidebar v-if="isAuthenticated" />

    <div :class="{'w-full': !isAuthenticated, 'flex-1': isAuthenticated}">
      <Navbar />
      <main class="p-6">
        <router-view></router-view>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from './stores/auth';
import Navbar from './components/common/Navbar.vue';
import Sidebar from './components/common/Sidebar.vue';

const authStore = useAuthStore();
const route = useRoute();

const isAuthenticated = computed(() => {
  return authStore.isAuthenticated && route.name !== 'login';
});
</script>

<style scoped>
/* You can add global styles here if needed */
</style>

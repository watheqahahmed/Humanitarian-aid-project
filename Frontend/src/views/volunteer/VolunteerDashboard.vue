<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">مرحباً بك، {{ authStore.user.name }}!</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-600 mb-2">عدد مهام التوزيع المخصصة لك</h3>
        <p class="text-4xl font-bold text-blue-500">{{ assignedDeliveriesCount }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';

const assignedDeliveriesCount = ref(0);
const authStore = useAuthStore();

const fetchAssignedDeliveriesCount = async () => {
  try {
    const response = await apiClient.get('/volunteer/deliveries', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    assignedDeliveriesCount.value = response.data.length;
  } catch (error) {
    console.error('فشل في جلب مهام التوزيع:', error);
  }
};

onMounted(() => {
  fetchAssignedDeliveriesCount();
});
</script>

<style scoped>
</style>

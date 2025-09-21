<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">مرحباً بك، {{ authStore.user.name }}!</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-600 mb-2">عدد طلبات المساعدة المقدمة</h3>
        <p class="text-4xl font-bold text-blue-500">{{ totalAidRequests }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-600 mb-2">طلبات معلقة</h3>
        <p class="text-4xl font-bold text-yellow-500">{{ pendingRequests }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';

const totalAidRequests = ref(0);
const pendingRequests = ref(0);
const authStore = useAuthStore();

const fetchAidRequestsStats = async () => {
  try {
    const response = await apiClient.get('/beneficiary/aid-requests', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    totalAidRequests.value = response.data.length;
    pendingRequests.value = response.data.filter(req => req.status === 'pending').length;
  } catch (error) {
    console.error('فشل في جلب إحصائيات طلبات المساعدة:', error);
  }
};

onMounted(() => {
  fetchAidRequestsStats();
});
</script>

<style scoped>
</style>

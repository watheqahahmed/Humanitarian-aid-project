<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">لوحة تحكم المسؤول</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-600 mb-2">إجمالي التبرعات</h3>
        <p class="text-4xl font-bold text-blue-500">{{ stats.total_donations }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-600 mb-2">إجمالي طلبات المساعدة</h3>
        <p class="text-4xl font-bold text-green-500">{{ stats.total_aid_requests }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-600 mb-2">طلبات مساعدة معلقة</h3>
        <p class="text-4xl font-bold text-yellow-500">{{ stats.pending_requests }}</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-600 mb-2">عدد المتطوعين</h3>
        <p class="text-4xl font-bold text-red-500">{{ stats.total_volunteers }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';

const stats = ref({
  total_donations: 0,
  total_aid_requests: 0,
  pending_requests: 0,
  total_volunteers: 0
});

const authStore = useAuthStore();

const fetchDashboardData = async () => {
  try {
    const response = await apiClient.get('/admin/dashboard', {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    });
    stats.value = response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات لوحة التحكم:', error);
  }
};

onMounted(() => {
  fetchDashboardData();
});
</script>

<style scoped>
</style>

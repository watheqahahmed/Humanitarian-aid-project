<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إدارة التبرعات</h1>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="donation in donations" :key="donation.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.donor_name || 'تبرع مجهول' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.quantity }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';

const donations = ref([]);
const authStore = useAuthStore();

const fetchDonations = async () => {
  try {
    const response = await apiClient.get('/admin/donations', {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    });
    donations.value = response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات التبرعات:', error);
  }
};

onMounted(() => {
  fetchDonations();
});
</script>

<style scoped>
</style>

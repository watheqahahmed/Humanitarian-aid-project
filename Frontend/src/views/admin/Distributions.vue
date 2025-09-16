<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إدارة عمليات التوزيع</h1>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المتطوع</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستفيد</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">إثبات التسليم</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="distribution in distributions" :key="distribution.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ distribution.volunteer.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ distribution.beneficiary.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(distribution.delivery_status)">
                {{ distribution.delivery_status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <a v-if="distribution.proof_file" :href="distribution.proof_file" target="_blank" class="text-blue-500 hover:underline">عرض الملف</a>
              <span v-else>لا يوجد</span>
            </td>
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

const distributions = ref([]);
const authStore = useAuthStore();

const fetchDistributions = async () => {
  try {
    const response = await apiClient.get('/admin/distributions', {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    });
    distributions.value = response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات التوزيع:', error);
  }
};

const getStatusClass = (status) => {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-yellow-100 text-yellow-800': status === 'assigned',
    'bg-blue-100 text-blue-800': status === 'in_progress',
    'bg-green-100 text-green-800': status === 'completed',
  };
};

onMounted(() => {
  fetchDistributions();
});
</script>

<style scoped>
</style>

<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إدارة طلبات المساعدة</h1>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستفيد</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="request in requests" :key="request.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ request.beneficiary.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ request.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ request.description }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(request.status)">
                {{ request.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button @click="updateStatus(request.id, 'approved')" class="text-green-600 hover:text-green-900 ml-2">قبول</button>
              <button @click="updateStatus(request.id, 'denied')" class="text-red-600 hover:text-red-900">رفض</button>
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

const requests = ref([]);
const authStore = useAuthStore();

const fetchAidRequests = async () => {
  try {
    const response = await apiClient.get('/admin/aid-requests', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    requests.value = response.data;
  } catch (error) {
    console.error('فشل في جلب طلبات المساعدة:', error);
  }
};

const updateStatus = async (requestId, newStatus) => {
  try {
    await apiClient.put(`/admin/aid-requests/${requestId}`, { status: newStatus }, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    await fetchAidRequests(); // إعادة جلب البيانات
  } catch (error) {
    console.error('فشل في تحديث حالة الطلب:', error);
  }
};

const getStatusClass = (status) => {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-green-100 text-green-800': status === 'approved',
    'bg-red-100 text-red-800': status === 'denied',
  };
};

onMounted(() => {
  fetchAidRequests();
});
</script>

<style scoped>
</style>

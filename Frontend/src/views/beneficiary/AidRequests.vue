<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">طلبات المساعدة الخاصة بي</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
      <h2 class="text-2xl font-semibold mb-4 text-gray-700">تقديم طلب مساعدة جديد</h2>
      <form @submit.prevent="submitAidRequest">
        <div class="mb-4">
          <label for="type" class="block text-gray-700 text-sm font-bold mb-2">نوع المساعدة</label>
          <select v-model="newRequest.type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <option value="food">مواد غذائية</option>
            <option value="clothes">ملابس</option>
            <option value="medicine">دواء</option>
          </select>
        </div>
        <div class="mb-6">
          <label for="description" class="block text-gray-700 text-sm font-bold mb-2">الوصف</label>
          <textarea v-model="newRequest.description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          إرسال الطلب
        </button>
      </form>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <h2 class="text-2xl font-semibold mb-4 text-gray-700">طلباتي السابقة</h2>
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="request in requests" :key="request.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ request.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ request.description }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(request.status)">
                {{ request.status }}
              </span>
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
const newRequest = ref({ type: 'food', description: '' });
const authStore = useAuthStore();

const fetchAidRequests = async () => {
  try {
    const response = await apiClient.get('/beneficiary/aid-requests', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    requests.value = response.data;
  } catch (error) {
    console.error('فشل في جلب طلبات المساعدة:', error);
  }
};

const submitAidRequest = async () => {
  try {
    await apiClient.post('/beneficiary/aid-requests', newRequest.value, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    alert('تم إرسال طلبك بنجاح.');
    newRequest.value = { type: 'food', description: '' };
    await fetchAidRequests();
  } catch (error) {
    console.error('فشل في تقديم الطلب:', error);
    alert('فشل في تقديم الطلب.');
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

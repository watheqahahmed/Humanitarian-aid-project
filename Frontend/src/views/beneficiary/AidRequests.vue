<template>
  <div class="min-h-screen p-8 bg-gray-100 font-sans">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 text-center">طلبات المساعدة الخاصة بي</h1>

    <!-- نموذج تقديم طلب جديد -->
    <div class="bg-white p-8 rounded-3xl shadow-lg mb-8 relative z-0">
      <h2 class="text-2xl font-semibold mb-6 text-gray-800">تقديم طلب مساعدة جديد</h2>
      <form @submit.prevent="submitAidRequest" class="space-y-6">
        <div>
          <label for="type" class="block text-gray-700 text-lg font-semibold mb-2">نوع المساعدة</label>
          <select
            v-model="newRequest.type"
            id="type"
            class="w-full rounded-lg border border-gray-300 shadow-sm p-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          >
            <option value="food">مواد غذائية</option>
            <option value="clothes">ملابس</option>
            <option value="medicine">دواء</option>
          </select>
        </div>
        <div>
          <label for="description" class="block text-gray-700 text-lg font-semibold mb-2">الوصف</label>
          <textarea
            v-model="newRequest.description"
            id="description"
            rows="5"
            class="w-full rounded-lg border border-gray-300 shadow-sm p-3 text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            required
          ></textarea>
        </div>
        <button
          type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition w-full text-base shadow-md hover:shadow-lg"
        >
          إرسال الطلب
        </button>
      </form>
    </div>

    <!-- جدول الطلبات السابقة -->
    <div class="bg-white p-6 rounded-2xl shadow-md overflow-x-auto hover:shadow-lg transition relative z-0">
      <h2 class="text-2xl font-semibold mb-4 text-gray-700">طلباتي السابقة</h2>
      <table class="min-w-full divide-y divide-gray-200 direction-rtl">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="request in requests" :key="request.id" class="hover:bg-gray-50 transition-all duration-300 cursor-pointer">
            <td class="px-6 py-4 whitespace-nowrap text-right">{{ request.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">{{ request.description }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
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
import { ref, onMounted, getCurrentInstance } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';

const { proxy } = getCurrentInstance(); // للوصول إلى $toast
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
    proxy.$toast.open({
      message: '❌ فشل في جلب طلبات المساعدة',
      type: 'error',
      duration: 5000
    });
  }
};

const submitAidRequest = async () => {
  try {
    await apiClient.post('/beneficiary/aid-requests', newRequest.value, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    proxy.$toast.open({
      message: '✅ تم إرسال طلبك بنجاح',
      type: 'success',
      duration: 5000
    });
    newRequest.value = { type: 'food', description: '' };
    await fetchAidRequests();
  } catch (error) {
    proxy.$toast.open({
      message: '❌ فشل في تقديم الطلب',
      type: 'error',
      duration: 5000
    });
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
.direction-rtl {
  direction: rtl;
}

table th, table td {
  vertical-align: middle;
}

table tbody tr:hover {
  transform: scale(1.02);
  transition: transform 0.2s ease-in-out;
}
</style>

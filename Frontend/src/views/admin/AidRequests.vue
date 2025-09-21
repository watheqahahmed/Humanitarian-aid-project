<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إدارة طلبات المساعدة</h1>

    <!-- شريط البحث والفلترة + أزرار التصدير -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div class="flex flex-col md:flex-row gap-4 md:items-center">
        <input
          v-model="searchTerm"
          type="text"
          placeholder="بحث بالاسم أو النوع..."
          class="shadow appearance-none border rounded w-full md:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        >
        <select v-model="statusFilter" class="shadow appearance-none border rounded w-full md:w-1/4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <option value="">كل الحالات</option>
          <option value="pending">قيد الانتظار</option>
          <option value="approved">مقبول</option>
          <option value="denied">مرفوض</option>
        </select>
      </div>

      <!-- أزرار التصدير -->
      <div class="flex gap-2">
        <button @click="downloadAidRequests" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          تصدير Excel
        </button>
      </div>
    </div>

    <!-- جدول طلبات المساعدة -->
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
          <tr v-for="request in filteredRequests" :key="request.id">
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
      <div v-if="filteredRequests.length === 0" class="text-center p-4 text-gray-500">
        لا توجد نتائج مطابقة.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../../stores/auth';

const requests = ref([]);
const authStore = useAuthStore();
const searchTerm = ref('');
const statusFilter = ref('');

// جلب طلبات المساعدة
const fetchAidRequests = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/admin/aid-requests`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    requests.value = response.data;
  } catch (error) {
    console.error('فشل في جلب طلبات المساعدة:', error);
  }
};

// فلترة البيانات حسب البحث والحالة
const filteredRequests = computed(() => {
  let result = requests.value;

  if (searchTerm.value) {
    const lowerCaseSearchTerm = searchTerm.value.toLowerCase();
    result = result.filter(request =>
      (request.beneficiary.name && request.beneficiary.name.toLowerCase().includes(lowerCaseSearchTerm)) ||
      (request.type && request.type.toLowerCase().includes(lowerCaseSearchTerm))
    );
  }

  if (statusFilter.value) {
    result = result.filter(request => request.status === statusFilter.value);
  }

  return result;
});

// تحديث حالة الطلب
const updateStatus = async (requestId, newStatus) => {
  try {
    await axios.put(`${import.meta.env.VITE_API_BASE_URL}/admin/aid-requests/${requestId}`, { status: newStatus }, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    await fetchAidRequests();
  } catch (error) {
    console.error('فشل في تحديث حالة الطلب:', error);
  }
};

// تحميل تقرير Excel
const downloadAidRequests = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/admin/reports/aid-requests`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      responseType: 'blob',
    });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'aid_requests.xlsx');
    document.body.appendChild(link);
    link.click();
  } catch (error) {
    console.error('فشل في تنزيل التقرير:', error);
  }
};

// كلاس الحالة لتلوينها
const getStatusClass = (status) => {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-green-100 text-green-800': status === 'approved',
    'bg-red-100 text-red-800': status === 'denied',
  };
};

// عند تحميل الصفحة
onMounted(() => {
  fetchAidRequests();
});
</script>

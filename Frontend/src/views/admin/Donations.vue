<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إدارة التبرعات</h1>

    <!-- شريط البحث + زر التصدير -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <input
        v-model="searchTerm"
        type="text"
        placeholder="بحث بالاسم أو النوع..."
        class="shadow appearance-none border rounded w-full md:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      >

      <button @click="exportDonations" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded md:w-auto">
        تصدير Excel
      </button>
    </div>

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
          <tr v-for="donation in filteredDonations" :key="donation.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.donor_name || 'تبرع مجهول' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.quantity }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ donation.status }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="filteredDonations.length === 0" class="text-center p-4 text-gray-500">
        لا توجد نتائج مطابقة.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';
import axios from 'axios';

const donations = ref([]);
const authStore = useAuthStore();
const searchTerm = ref('');

// جلب بيانات التبرعات
const fetchDonations = async () => {
  try {
    const response = await apiClient.get('/admin/donations', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    donations.value = response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات التبرعات:', error);
  }
};

// فلترة التبرعات حسب البحث
const filteredDonations = computed(() => {
  if (!searchTerm.value) return donations.value;

  const lowerCaseSearchTerm = searchTerm.value.toLowerCase();
  return donations.value.filter(donation =>
    (donation.donor_name && donation.donor_name.toLowerCase().includes(lowerCaseSearchTerm)) ||
    (donation.type && donation.type.toLowerCase().includes(lowerCaseSearchTerm))
  );
});

// ✅ دالة تصدير Excel مع التوكن
const exportDonations = async () => {
  try {
    const response = await axios.get(
      `${import.meta.env.VITE_API_BASE_URL}/admin/reports/donations`,
      {
        headers: { Authorization: `Bearer ${authStore.token}` },
        responseType: 'blob',
      }
    );

    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'donations.xlsx');
    document.body.appendChild(link);
    link.click();
    link.remove();
  } catch (error) {
    console.error('فشل في تنزيل تقرير التبرعات:', error);
  }
};

onMounted(() => {
  fetchDonations();
});
</script>

<style scoped>
</style>

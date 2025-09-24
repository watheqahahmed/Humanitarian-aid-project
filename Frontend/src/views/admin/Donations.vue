<template>
  <div class="min-h-screen p-8 bg-gray-100 dark:bg-gray-900 transition-colors duration-300" dir="rtl">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100 text-center">إدارة التبرعات</h1>

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <input
        v-model="searchTerm"
        type="text"
        placeholder="بحث بالاسم أو النوع..."
        class="shadow-sm border border-gray-300 rounded-lg w-full md:w-1/3 py-2 px-4 text-gray-700 text-right focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 transition"
      >

      <select
        v-model="selectedStatus"
        class="shadow-sm border border-gray-300 rounded-lg w-full md:w-1/4 py-2 px-4 text-gray-700 text-right focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 transition"
      >
        <option value="">جميع الحالات</option>
        <option value="pending">معلق</option>
        <option value="received">مستلم</option>
        <option value="distributed">موزع</option>
      </select>

      <button
        @click="exportDonations"
        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105 md:w-auto"
      >
        تصدير Excel
      </button>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg overflow-x-auto transition-colors duration-300">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">الاسم</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">النوع</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">الكمية</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">الحالة</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
          <tr v-for="donation in filteredAndSortedDonations" :key="donation.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-300">
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-200 text-right">{{ donation.donor_name || 'تبرع مجهول' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300 text-right">{{ donation.type }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300 text-right">{{ donation.quantity }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
              <span :class="getStatusClass(donation.status)">
                {{ donation.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex gap-2 justify-end">
                <button
                  v-if="donation.status === 'pending'"
                  @click="updateDonationStatus(donation.id, 'received')"
                  class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition-colors"
                >
                  استلام
                </button>
                <button
                  v-if="donation.status === 'received'"
                  @click="updateDonationStatus(donation.id, 'distributed')"
                  class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-semibold transition-colors"
                >
                  توزيع
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="filteredAndSortedDonations.length === 0" class="text-center p-4 text-gray-500 dark:text-gray-400">
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
const selectedStatus = ref('');

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

// فلترة وفرز التبرعات
const filteredAndSortedDonations = computed(() => {
  let tempDonations = donations.value;

  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase();
    tempDonations = tempDonations.filter(d =>
      (d.donor_name && d.donor_name.toLowerCase().includes(term)) ||
      (d.type && d.type.toLowerCase().includes(term))
    );
  }

  if (selectedStatus.value) {
    tempDonations = tempDonations.filter(d => d.status === selectedStatus.value);
  }

  return tempDonations;
});

// دالة لتلوين حالة التبرع
const getStatusClass = (status) => {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': status === 'pending',
    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': status === 'received',
    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': status === 'distributed',
  };
};

// تحديث حالة التبرع
const updateDonationStatus = async (donationId, newStatus) => {
  try {
    await apiClient.put(`/admin/donations/${donationId}/status`, {
      status: newStatus
    }, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    await fetchDonations();
  } catch (error) {
    console.error('فشل في تحديث حالة التبرع:', error);
  }
};

// تصدير Excel
const exportDonations = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/admin/reports/donations`, {
      headers: { Authorization: `Bearer ${authStore.token}` },
      responseType: 'blob',
    });
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

<style scoped></style>

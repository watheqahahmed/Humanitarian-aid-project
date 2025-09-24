<template>
  <div class="min-h-screen p-8 bg-indigo-50/50">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">مرحباً بك، {{ authStore.user.name }}!</h1>

    <!-- الإحصاءات الأساسية -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div
        class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 transform transition hover:scale-105 hover:shadow-2xl"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-2">عدد طلبات المساعدة</h3>
        <p class="text-4xl font-bold text-blue-600">{{ totalAidRequests }}</p>
      </div>
      <div
        class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 transform transition hover:scale-105 hover:shadow-2xl"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-2">طلبات معلقة</h3>
        <p class="text-4xl font-bold text-yellow-500">{{ pendingRequests }}</p>
      </div>
      <div
        class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 transform transition hover:scale-105 hover:shadow-2xl"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-2">طلبات مكتملة</h3>
        <p class="text-4xl font-bold text-green-500">{{ completedRequests }}</p>
      </div>
    </div>

    <!-- المخططات -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 transform transition hover:scale-105 hover:shadow-2xl"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-4">طلبات المساعدة حسب الحالة</h3>
        <BarChart :data="barChartData" />
      </div>
      <div
        class="bg-white p-6 rounded-2xl shadow-2xl border border-gray-200 transform transition hover:scale-105 hover:shadow-2xl"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-4">توزيع الطلبات</h3>
        <PieChart :data="pieChartData" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';
import BarChart from '../../components/charts/BarChart.vue';
import PieChart from '../../components/charts/PieChart.vue';

const requests = ref([]);
const authStore = useAuthStore();

const totalAidRequests = ref(0);
const pendingRequests = ref(0);
const completedRequests = ref(0);
const deniedRequests = ref(0);

const fetchAidRequestsStats = async () => {
  try {
    const response = await apiClient.get('/beneficiary/aid-requests', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    requests.value = response.data;

    totalAidRequests.value = requests.value.length;
    pendingRequests.value = requests.value.filter(r => r.status === 'pending').length;
    completedRequests.value = requests.value.filter(r => r.status === 'approved').length;
    deniedRequests.value = requests.value.filter(r => r.status === 'denied').length;
  } catch (error) {
    console.error('فشل في جلب إحصائيات طلبات المساعدة:', error);
  }
};

onMounted(() => fetchAidRequestsStats());

// مجموعة الألوان الموحدة للمخططين (مطابقة لألوان لوحة المتطوع)
const chartColors = ['#60A5FA', '#34D399', '#FACC15']; // أزرق، أخضر، أصفر

// بيانات المخطط الشريطي
const barChartData = computed(() => ({
  labels: ['معلقة', 'مكتملة', 'مرفوضة'],
  datasets: [
    {
      label: 'عدد الطلبات',
      data: [pendingRequests.value, completedRequests.value, deniedRequests.value],
      backgroundColor: chartColors,
      borderRadius: 8
    }
  ]
}));

// بيانات مخطط الدائرة
const pieChartData = computed(() => ({
  labels: ['معلقة', 'مكتملة', 'مرفوضة'],
  datasets: [
    {
      data: [pendingRequests.value, completedRequests.value, deniedRequests.value],
      backgroundColor: chartColors,
      borderWidth: 2
    }
  ]
}));
</script>

<style scoped>
/* تأثير تفاعلي عند مرور المؤشر على البطاقات */
</style>

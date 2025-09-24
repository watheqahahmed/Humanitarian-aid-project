<template>
  <div class="min-h-screen p-6 bg-gray-100">
    <!-- عنوان ترحيبي باسم المستخدم -->
    <h1 class="text-3xl font-bold mb-8 text-gray-800 text-center">
      مرحباً بك، {{ authStore.user.name }}!
    </h1>

    <!-- إحصائيات سريعة -->
    <div v-if="stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
        <h3 class="text-lg font-semibold text-gray-500 mb-2">إجمالي التبرعات</h3>
        <p class="text-3xl font-bold text-blue-600">{{ stats.total_donations }}</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
        <h3 class="text-lg font-semibold text-gray-500 mb-2">إجمالي طلبات المساعدة</h3>
        <p class="text-3xl font-bold text-green-600">{{ stats.total_aid_requests }}</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
        <h3 class="text-lg font-semibold text-gray-500 mb-2">الطلبات المعلقة</h3>
        <p class="text-3xl font-bold text-yellow-500">{{ stats.pending_requests }}</p>
      </div>
      <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-1">
        <h3 class="text-lg font-semibold text-gray-500 mb-2">عدد المتطوعين</h3>
        <p class="text-3xl font-bold text-red-500">{{ stats.total_volunteers }}</p>
      </div>
    </div>

    <div v-else class="text-center text-gray-500 mb-8">جاري تحميل البيانات...</div>

    <!-- المخططات -->
    <div v-if="stats" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1">
        <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">مخطط إجمالي التبرعات</h3>
        <BarChart :data="barData" :options="barOptions" />
      </div>
      <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1">
        <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">مخطط توزيع طلبات المساعدة</h3>
        <PieChart :data="pieData" :options="pieOptions" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';
import BarChart from '../../components/charts/BarChart.vue';
import PieChart from '../../components/charts/PieChart.vue';

const authStore = useAuthStore();
const stats = ref(null);

const fetchDashboardData = async () => {
  try {
    const response = await apiClient.get('/admin/dashboard', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    stats.value = response.data.stats || response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات لوحة التحكم:', error);
  }
};

onMounted(() => fetchDashboardData());

// ألوان موحدة مع الإحصاءات أعلاه
const chartColors = ['#3B82F6', '#10B981', '#FACC15', '#EF4444']; // أزرق، أخضر، أصفر، أحمر

const barData = computed(() => ({
  labels: ['التبرعات', 'طلبات المساعدة', 'طلبات معلقة', 'المتطوعون'],
  datasets: [
    {
      label: 'الإحصائيات',
      backgroundColor: chartColors,
      data: stats.value
        ? [stats.value.total_donations, stats.value.total_aid_requests, stats.value.pending_requests, stats.value.total_volunteers]
        : [0, 0, 0, 0],
    },
  ],
}));

const barOptions = { responsive: true, plugins: { legend: { display: false } } };

const pieData = computed(() => ({
  labels: ['طلبات مكتملة', 'طلبات معلقة'],
  datasets: [
    {
      backgroundColor: [chartColors[1], chartColors[2]], // أخضر للمكتملة، أصفر للمعلقة
      data: stats.value
        ? [stats.value.total_aid_requests - stats.value.pending_requests, stats.value.pending_requests]
        : [0, 0],
    },
  ],
}));

const pieOptions = { responsive: true, plugins: { legend: { position: 'bottom' } } };
</script>

<style scoped>
/* تأثيرات بسيطة عند المرور على البطاقات أو المخططات */
</style>

<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 text-center">
      مرحباً بك، {{ authStore.user.name }}!
    </h1>

    <!-- الإحصاءات الأساسية -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div
        class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-2">
          عدد مهام التوزيع المخصصة لك
        </h3>
        <p class="text-4xl font-bold text-blue-500">
          {{ assignedDeliveriesCount }}
        </p>
      </div>
      <div
        class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-2">
          عدد المهام المكتملة
        </h3>
        <p class="text-4xl font-bold text-green-500">
          {{ completedDeliveriesCount }}
        </p>
      </div>
      <div
        class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1"
      >
        <h3 class="text-xl font-semibold text-gray-600 mb-2">
          عدد المهام المعلقة
        </h3>
        <p class="text-4xl font-bold text-yellow-500">
          {{ pendingDeliveriesCount }}
        </p>
      </div>
    </div>

    <!-- المخططات -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1"
      >
        <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">
          مهام التوزيع حسب الحالة
        </h3>
        <BarChart v-if="assignedDeliveriesCount" :data="barChartData" />
        <p v-else class="text-gray-500 text-center">
          لا توجد بيانات لعرض المخطط
        </p>
      </div>

      <div
        class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition-transform transform hover:-translate-y-1"
      >
        <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">
          توزيع المهام
        </h3>
        <PieChart v-if="assignedDeliveriesCount" :data="pieChartData" />
        <p v-else class="text-gray-500 text-center">
          لا توجد بيانات لعرض المخطط
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import apiClient from "../../axios";
import { useAuthStore } from "../../stores/auth";
import BarChart from "../../components/charts/BarChart.vue";
import PieChart from "../../components/charts/PieChart.vue";

const assignedDeliveries = ref([]);
const assignedDeliveriesCount = ref(0);
const completedDeliveriesCount = ref(0);
const pendingDeliveriesCount = ref(0);
const authStore = useAuthStore();

const fetchAssignedDeliveries = async () => {
  try {
    const response = await apiClient.get("/volunteer/deliveries", {
      headers: { Authorization: `Bearer ${authStore.token}` },
    });
    assignedDeliveries.value = response.data || [];

    assignedDeliveriesCount.value = assignedDeliveries.value.length;
    completedDeliveriesCount.value = assignedDeliveries.value.filter(
      (d) => d.delivery_status === "completed"
    ).length;
    pendingDeliveriesCount.value = assignedDeliveries.value.filter(
      (d) => d.delivery_status === "assigned"
    ).length;
  } catch (error) {
    console.error("فشل في جلب مهام التوزيع:", error);
  }
};

onMounted(fetchAssignedDeliveries);

const barChartData = computed(() => ({
  labels: ["معلقة", "مكتملة", "مخصصة"],
  datasets: [
    {
      label: "عدد المهام",
      data: [
        pendingDeliveriesCount.value,
        completedDeliveriesCount.value,
        assignedDeliveriesCount.value,
      ],
      backgroundColor: ["#FACC15", "#34D399", "#60A5FA"],
    },
  ],
}));

const pieChartData = computed(() => ({
  labels: ["معلقة", "مكتملة", "مخصصة"],
  datasets: [
    {
      data: [
        pendingDeliveriesCount.value,
        completedDeliveriesCount.value,
        assignedDeliveriesCount.value,
      ],
      backgroundColor: ["#FACC15", "#34D399", "#60A5FA"],
    },
  ],
}));
</script>

<style scoped>
canvas {
  max-height: 300px;
}
</style>

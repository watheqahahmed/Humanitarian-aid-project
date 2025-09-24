<template>
  <div class="bg-white p-4 rounded-lg shadow-md">
    <canvas ref="canvas"></canvas>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  data: {
    type: Object,
    required: true
  },
  options: {
    type: Object,
    default: () => ({})
  }
});

const canvas = ref(null);
let chartInstance = null;

const renderChart = () => {
  if (!canvas.value) return;
  if (chartInstance) chartInstance.destroy();

  chartInstance = new Chart(canvas.value, {
    type: 'bar',
    data: props.data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { enabled: true },
      },
      scales: {
        y: { beginAtZero: true },
      },
      ...props.options, // دمج الخيارات المرسلة من الخارج
    },
  });
};

// إعادة الرسم عند تغيير البيانات
watch(() => props.data, () => {
  if (props.data) renderChart();
}, { deep: true });

onMounted(() => {
  if (props.data) renderChart();
});
</script>

<style scoped>
canvas {
  width: 100%;
  height: 300px;
}
</style>

<template>
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold">My Aid Requests</h2>
      <button @click="openForm" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-500">New Request</button>
    </div>

    <!-- New Request Form -->
    <div v-if="showForm" class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
      <input type="text" v-model="form.type" placeholder="Aid Type" class="input-field" />
      <textarea v-model="form.description" placeholder="Description" class="input-field"></textarea>
      <input type="file" @change="handleFile" class="input-field" />
      <div class="flex gap-2">
        <button @click="submitRequest" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">Submit</button>
        <button @click="showForm = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
      </div>
    </div>

    <!-- Aid Requests Table -->
    <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow overflow-hidden">
      <thead>
        <tr class="bg-gray-200 dark:bg-gray-700 text-left">
          <th class="px-4 py-2">Type</th>
          <th class="px-4 py-2">Description</th>
          <th class="px-4 py-2">Status</th>
          <th class="px-4 py-2">Document</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="req in requests" :key="req.id" class="border-b border-gray-300 dark:border-gray-600">
          <td class="px-4 py-2">{{ req.type }}</td>
          <td class="px-4 py-2">{{ req.description }}</td>
          <td class="px-4 py-2 capitalize">{{ req.status }}</td>
          <td class="px-4 py-2">
            <a v-if="req.document_url" :href="`http://127.0.0.1:8000/storage/${req.document_url}`" target="_blank" class="text-teal-600 hover:underline">View</a>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Charts -->
    <div class="mt-6">
      <h3 class="text-xl font-semibold mb-4">My Requests Status</h3>
      <PieChart :chart-data="requestsData" :chart-options="chartOptions" />
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import api from "@/axios";
import { useToast } from "vue-toastification";
import { Pie } from "vue-chartjs";
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from "chart.js";

ChartJS.register(Title, Tooltip, Legend, ArcElement);

const toast = useToast();

const requests = ref([]);
const showForm = ref(false);
const file = ref(null);
const form = reactive({ type: "", description: "" });
const requestsData = ref({ labels: ["Pending", "Approved", "Denied"], datasets: [{ data: [0,0,0], backgroundColor: ["#FBBF24", "#34D399", "#F87171"] }] });
const chartOptions = { responsive: true, plugins: { legend: { position: "top" } } };

const fetchRequests = async () => {
  const res = await api.get("/aid-requests");
  requests.value = res.data;

  // تحديث بيانات الـChart
  const statusCount = { pending: 0, approved: 0, denied: 0 };
  res.data.forEach(r => statusCount[r.status]++);
  requestsData.value.datasets[0].data = [statusCount.pending, statusCount.approved, statusCount.denied];
};

const openForm = () => {
  form.type = "";
  form.description = "";
  file.value = null;
  showForm.value = true;
};

const handleFile = (e) => {
  file.value = e.target.files[0];
};

const submitRequest = async () => {
  const formData = new FormData();
  formData.append("type", form.type);
  formData.append("description", form.description);
  if (file.value) formData.append("document", file.value);

  await api.post("/aid-requests", formData);
  toast.success("Aid request submitted successfully");
  showForm.value = false;
  fetchRequests();
};

onMounted(fetchRequests);
</script>

<style scoped>
.input-field {
  width: 100%;
  padding: 0.5rem;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
  background-color: white;
  color: #111827;
}
.dark .input-field {
  background-color: #1f2937;
  border-color: #374151;
  color: #f9fafb;
}
</style>

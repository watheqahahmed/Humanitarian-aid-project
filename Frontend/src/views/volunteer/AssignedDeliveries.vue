<template>
  <div class="space-y-6">
    <h2 class="text-2xl font-bold">Assigned Deliveries</h2>

    <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow overflow-hidden">
      <thead>
        <tr class="bg-gray-200 dark:bg-gray-700">
          <th class="px-4 py-2">Beneficiary</th>
          <th class="px-4 py-2">Donation</th>
          <th class="px-4 py-2">Status</th>
          <th class="px-4 py-2">Proof</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="delivery in deliveries" :key="delivery.id" class="border-b border-gray-300 dark:border-gray-600">
          <td class="px-4 py-2">{{ delivery.beneficiary_name }}</td>
          <td class="px-4 py-2">{{ delivery.donation_name }}</td>
          <td class="px-4 py-2 capitalize">{{ delivery.delivery_status }}</td>
          <td class="px-4 py-2">
            <a v-if="delivery.proof_file" :href="`http://127.0.0.1:8000/storage/${delivery.proof_file}`" target="_blank" class="text-teal-600 hover:underline">View</a>
          </td>
          <td class="px-4 py-2">
            <input type="file" @change="e => handleFile(e, delivery.id)" />
            <button @click="markCompleted(delivery.id)" class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-500">Mark Completed</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import api from "@/axios";
import { useToast } from "vue-toastification";

const toast = useToast();
const deliveries = ref([]);
const files = ref({}); // لتخزين الملفات مؤقتًا لكل delivery

const fetchDeliveries = async () => {
  const res = await api.get("/assigned-deliveries");
  deliveries.value = res.data;
};

const handleFile = (e, id) => {
  files.value[id] = e.target.files[0];
};

const markCompleted = async (id) => {
  const formData = new FormData();
  if (files.value[id]) formData.append("proof_file", files.value[id]);
  formData.append("delivery_status", "completed");

  await api.put(`/distributions/${id}`, formData);
  toast.success("Delivery marked as completed");
  fetchDeliveries();
};

onMounted(fetchDeliveries);
</script>

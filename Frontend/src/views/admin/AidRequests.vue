<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Aid Requests</h2>
    <button @click="openForm()" class="mb-4 bg-teal-600 text-white px-4 py-2 rounded">
      + Submit Request
    </button>

    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow">
      <thead class="bg-gray-200 dark:bg-gray-700">
        <tr>
          <th class="py-2 px-4">Type</th>
          <th class="py-2 px-4">Description</th>
          <th class="py-2 px-4">Status</th>
          <th class="py-2 px-4">Document</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="r in requests" :key="r.id" class="border-b border-gray-200 dark:border-gray-700">
          <td class="py-2 px-4">{{ r.type }}</td>
          <td class="py-2 px-4">{{ r.description }}</td>
          <td class="py-2 px-4">{{ r.status }}</td>
          <td class="py-2 px-4">
            <a v-if="r.document_url" :href="r.document_url" target="_blank" class="text-blue-600 hover:underline">
              View
            </a>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal Form -->
    <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96">
        <h3 class="text-xl font-semibold mb-4">Submit Aid Request</h3>
        <form @submit.prevent="submitRequest" class="space-y-4">
          <input v-model="form.type" placeholder="Type" class="w-full p-2 border rounded"/>
          <textarea v-model="form.description" placeholder="Description" class="w-full p-2 border rounded"></textarea>
          <input type="file" @change="handleFile" />
          <div class="flex justify-end gap-2">
            <button type="button" @click="showForm=false" class="px-4 py-2 border rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded">Submit</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";

const toast = useToast();
const requests = ref([]);
const showForm = ref(false);
const file = ref(null);
const form = reactive({ type: "", description: "" });

const fetchRequests = async () => {
  const res = await axios.get("/api/aid-requests");
  requests.value = res.data;
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

  await axios.post("/api/aid-requests", formData);
  toast.success("Aid request submitted");
  showForm.value = false;
  fetchRequests();
};

onMounted(fetchRequests);
</script>

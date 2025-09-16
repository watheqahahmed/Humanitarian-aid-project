<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Manage Donations</h2>
    <button @click="openForm()" class="mb-4 bg-teal-600 text-white px-4 py-2 rounded">
      + Add Donation
    </button>

    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow">
      <thead class="bg-gray-200 dark:bg-gray-700">
        <tr>
          <th class="py-2 px-4">Donor Name</th>
          <th class="py-2 px-4">Type</th>
          <th class="py-2 px-4">Quantity</th>
          <th class="py-2 px-4">Status</th>
          <th class="py-2 px-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="d in donations" :key="d.id" class="border-b border-gray-200 dark:border-gray-700">
          <td class="py-2 px-4">{{ d.donor_name }}</td>
          <td class="py-2 px-4">{{ d.type }}</td>
          <td class="py-2 px-4">{{ d.quantity }}</td>
          <td class="py-2 px-4">{{ d.status }}</td>
          <td class="py-2 px-4">
            <button @click="editDonation(d)" class="text-blue-600 hover:underline mr-2">Edit</button>
            <button @click="deleteDonation(d.id)" class="text-red-600 hover:underline">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal Form -->
    <div v-if="showForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-96">
        <h3 class="text-xl font-semibold mb-4">{{ form.id ? 'Edit Donation' : 'Add Donation' }}</h3>
        <form @submit.prevent="saveDonation" class="space-y-4">
          <input v-model="form.donor_name" placeholder="Donor Name" class="w-full p-2 border rounded"/>
          <input v-model="form.type" placeholder="Type" class="w-full p-2 border rounded"/>
          <input v-model="form.quantity" type="number" placeholder="Quantity" class="w-full p-2 border rounded"/>
          <select v-model="form.status" class="w-full p-2 border rounded">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="denied">Denied</option>
          </select>
          <div class="flex justify-end gap-2">
            <button type="button" @click="showForm=false" class="px-4 py-2 border rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded">
              {{ form.id ? 'Update' : 'Create' }}
            </button>
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

const donations = ref([]);
const showForm = ref(false);
const form = reactive({ id: null, donor_name: "", type: "", quantity: 0, status: "pending" });

const fetchDonations = async () => {
  const res = await axios.get("/api/donations");
  donations.value = res.data;
};

const openForm = () => {
  form.id = null;
  form.donor_name = "";
  form.type = "";
  form.quantity = 0;
  form.status = "pending";
  showForm.value = true;
};

const editDonation = (d) => {
  Object.assign(form, d);
  showForm.value = true;
};

const saveDonation = async () => {
  try {
    if (form.id) {
      await axios.put(`/api/donations/${form.id}`, form);
      toast.success("Donation updated successfully");
    } else {
      await axios.post("/api/donations", form);
      toast.success("Donation created successfully");
    }
    showForm.value = false;
    fetchDonations();
  } catch (err) {
    toast.error("Error saving donation");
  }
};

const deleteDonation = async (id) => {
  if (confirm("Are you sure?")) {
    await axios.delete(`/api/donations/${id}`);
    toast.success("Donation deleted");
    fetchDonations();
  }
};

onMounted(fetchDonations);
</script>

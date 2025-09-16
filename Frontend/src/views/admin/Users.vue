<template>
  <div class="space-y-6">
    <h2 class="text-2xl font-bold">Manage Users</h2>
    <button @click="openForm" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-500">New User</button>

    <!-- New User Form -->
    <div v-if="showForm" class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
      <input type="text" v-model="form.name" placeholder="Name" class="input-field" />
      <input type="email" v-model="form.email" placeholder="Email" class="input-field" />
      <input type="password" v-model="form.password" placeholder="Password" class="input-field" />
      <select v-model="form.role" class="input-field">
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="volunteer">Volunteer</option>
        <option value="beneficiary">Beneficiary</option>
      </select>
      <div class="flex gap-2">
        <button @click="submitUser" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">Submit</button>
        <button @click="showForm=false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
      </div>
    </div>

    <!-- Users Table -->
    <table class="min-w-full bg-white dark:bg-gray-800 rounded shadow overflow-hidden">
      <thead>
        <tr class="bg-gray-200 dark:bg-gray-700">
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Role</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id" class="border-b border-gray-300 dark:border-gray-600">
          <td class="px-4 py-2">{{ user.name }}</td>
          <td class="px-4 py-2">{{ user.email }}</td>
          <td class="px-4 py-2 capitalize">{{ user.role }}</td>
          <td class="px-4 py-2">
            <button @click="deleteUser(user.id)" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-500">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import api from "@/axios";
import { useToast } from "vue-toastification";

const toast = useToast();
const users = ref([]);
const showForm = ref(false);
const form = reactive({ name: "", email: "", password: "", role: "" });

const fetchUsers = async () => {
  const res = await api.get("/users");
  users.value = res.data;
};

const openForm = () => {
  form.name = "";
  form.email = "";
  form.password = "";
  form.role = "";
  showForm.value = true;
};

const submitUser = async () => {
  await api.post("/users", form);
  toast.success("User created successfully");
  showForm.value = false;
  fetchUsers();
};

const deleteUser = async (id) => {
  await api.delete(`/users/${id}`);
  toast.success("User deleted");
  fetchUsers();
};

onMounted(fetchUsers);
</script>

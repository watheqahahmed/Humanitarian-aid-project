<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إدارة المستخدمين</h1>
    <div class="mb-4">
      <input
        v-model="searchTerm"
        type="text"
        placeholder="بحث بالاسم أو البريد الإلكتروني..."
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      >
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="user in filteredUsers" :key="user.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getRoleClass(user.role)">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button @click="openModal(user)" class="text-blue-600 hover:text-blue-900 ml-2">تعديل الدور</button>
              <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">حذف</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="filteredUsers.length === 0" class="text-center p-4 text-gray-500">
        لا توجد نتائج مطابقة.
      </div>
    </div>
  </div>

  <Modal :show="showModal" @close="closeModal">
    <template #header>
      تعديل دور المستخدم
    </template>
    <template #body>
      <form @submit.prevent="updateUserRole">
        <div class="mb-4">
          <label for="role" class="block text-gray-700 text-sm font-bold mb-2">الدور</label>
          <select v-model="selectedUser.role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="admin">مسؤول</option>
            <option value="volunteer">متطوع</option>
            <option value="beneficiary">مستفيد</option>
          </select>
        </div>
        <div class="flex justify-end">
          <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            حفظ التغييرات
          </button>
        </div>
      </form>
    </template>
  </Modal>

  <Notification :message="notification.message" :type="notification.type" />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';
import Modal from '../../components/common/Modal.vue';
import Notification from '../../components/common/Notifications.vue';

const users = ref([]);
const authStore = useAuthStore();
const showModal = ref(false);
const selectedUser = ref(null);
const searchTerm = ref('');
const notification = ref({
  message: '',
  type: 'success',
});

const fetchUsers = async () => {
  try {
    const response = await apiClient.get('/admin/users', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    users.value = response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات المستخدمين:', error);
    showNotification('فشل في جلب بيانات المستخدمين.', 'error');
  }
};

const showNotification = (message, type) => {
  notification.value.message = '';
  notification.value.type = type;
  setTimeout(() => {
    notification.value.message = message;
  }, 100);
};

const openModal = (user) => {
  selectedUser.value = { ...user };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedUser.value = null;
};

const updateUserRole = async () => {
  try {
    await apiClient.put(`/admin/users/${selectedUser.value.id}`, { role: selectedUser.value.role }, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    showNotification('تم تعديل دور المستخدم بنجاح.', 'success');
    closeModal();
    await fetchUsers();
  } catch (error) {
    console.error('فشل في تحديث دور المستخدم:', error);
    showNotification('فشل في تحديث دور المستخدم.', 'error');
  }
};

const deleteUser = async (userId) => {
  if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
    try {
      await apiClient.delete(`/admin/users/${userId}`, {
        headers: { Authorization: `Bearer ${authStore.token}` }
      });
      showNotification('تم حذف المستخدم بنجاح.', 'success');
      await fetchUsers();
    } catch (error) {
      console.error('فشل في حذف المستخدم:', error);
      showNotification('فشل في حذف المستخدم.', 'error');
    }
  }
};

const getRoleClass = (role) => {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-blue-100 text-blue-800': role === 'admin',
    'bg-green-100 text-green-800': role === 'volunteer',
    'bg-yellow-100 text-yellow-800': role === 'beneficiary',
  };
};

const filteredUsers = computed(() => {
  if (!searchTerm.value) {
    return users.value;
  }
  const lowerCaseSearchTerm = searchTerm.value.toLowerCase();
  return users.value.filter(user =>
    user.name.toLowerCase().includes(lowerCaseSearchTerm) ||
    user.email.toLowerCase().includes(lowerCaseSearchTerm)
  );
});

onMounted(() => {
  fetchUsers();
});
</script>

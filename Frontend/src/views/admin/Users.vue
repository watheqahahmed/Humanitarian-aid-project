<template>
  <div class="min-h-screen p-8 bg-gray-100" dir="rtl">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">إدارة المستخدمين</h1>

    <!-- شريط البحث -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <input
        v-model="searchTerm"
        type="text"
        placeholder="بحث بالاسم أو البريد الإلكتروني..."
        class="shadow-sm border border-gray-300 rounded-lg w-full md:w-1/2 py-2 px-4 text-gray-700 text-right focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
      >
    </div>

    <!-- جدول المستخدمين -->
    <div class="bg-white p-6 rounded-2xl shadow-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الاسم</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الدور</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 text-right">{{ user.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-right">{{ user.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
              <span :class="getRoleClass(user.role)">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end">
              <button @click="openModal(user)" class="text-blue-600 hover:text-blue-900 transition">تعديل الدور</button>
              <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900 transition">حذف</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="filteredUsers.length === 0" class="text-center p-4 text-gray-500">
        لا توجد نتائج مطابقة.
      </div>
    </div>

    <!-- نموذج تعديل الدور -->
    <Modal :show="showModal" @close="closeModal">
      <template #header>
        تعديل دور المستخدم
      </template>
      <template #body>
        <form @submit.prevent="updateUserRole">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">الدور</label>
            <select v-model="selectedUser.role" class="shadow-sm border border-gray-300 rounded-lg w-full py-2 px-3 text-gray-700 text-right focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
              <option value="admin">مسؤول</option>
              <option value="volunteer">متطوع</option>
              <option value="beneficiary">مستفيد</option>
            </select>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition transform hover:scale-105">
              حفظ التغييرات
            </button>
          </div>
        </form>
      </template>
    </Modal>

    <!-- إشعارات -->
    <Notification :message="notification.message" :type="notification.type" />
  </div>
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
const notification = ref({ message: '', type: 'success' });

// جلب المستخدمين
const fetchUsers = async () => {
  try {
    const response = await apiClient.get('/admin/users', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    users.value = response.data;
  } catch (error) {
    console.error(error);
    showNotification('فشل في جلب بيانات المستخدمين.', 'error');
  }
};

// إشعار
const showNotification = (message, type) => {
  notification.value.message = '';
  notification.value.type = type;
  setTimeout(() => { notification.value.message = message; }, 100);
};

// فتح/إغلاق المودال
const openModal = (user) => {
  selectedUser.value = { ...user };
  showModal.value = true;
};
const closeModal = () => {
  showModal.value = false;
  selectedUser.value = null;
};

// تحديث الدور
const updateUserRole = async () => {
  try {
    await apiClient.put(`/admin/users/${selectedUser.value.id}`, { role: selectedUser.value.role }, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    showNotification('تم تعديل دور المستخدم بنجاح.', 'success');
    closeModal();
    await fetchUsers();
  } catch (error) {
    console.error(error);
    showNotification('فشل في تحديث دور المستخدم.', 'error');
  }
};

// حذف المستخدم
const deleteUser = async (userId) => {
  if (!confirm('هل أنت متأكد من حذف هذا المستخدم؟')) return;
  try {
    await apiClient.delete(`/admin/users/${userId}`, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    showNotification('تم حذف المستخدم بنجاح.', 'success');
    await fetchUsers();
  } catch (error) {
    console.error(error);
    showNotification('فشل في حذف المستخدم.', 'error');
  }
};

// ألوان الدور
const getRoleClass = (role) => ({
  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
  'bg-blue-100 text-blue-800': role === 'admin',
  'bg-green-100 text-green-800': role === 'volunteer',
  'bg-yellow-100 text-yellow-800': role === 'beneficiary',
});

// فلترة المستخدمين
const filteredUsers = computed(() => {
  if (!searchTerm.value) return users.value;
  const term = searchTerm.value.toLowerCase();
  return users.value.filter(u => u.name.toLowerCase().includes(term) || u.email.toLowerCase().includes(term));
});

onMounted(() => {
  fetchUsers();
});
</script>

<style scoped>
/* تحسينات بسيطة للجدول أو الأزرار */
</style>

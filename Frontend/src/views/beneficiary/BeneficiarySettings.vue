<template>
  <div class="min-h-screen p-6 bg-gray-100 dark:bg-gray-900 transition-colors duration-300 flex items-center justify-center">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
      <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 text-center">الإعدادات</h1>

      <!-- إعادة تعيين كلمة السر -->
      <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">إعادة تعيين كلمة السر</h2>

        <input
          v-model="currentPassword"
          type="password"
          placeholder="كلمة السر الحالية"
          class="w-full p-3 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition"
        />

        <input
          v-model="newPassword"
          type="password"
          placeholder="كلمة السر الجديدة"
          class="w-full p-3 border border-gray-300 rounded-lg mb-3 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition"
        />

        <input
          v-model="confirmPassword"
          type="password"
          placeholder="تأكيد كلمة السر الجديدة"
          class="w-full p-3 border border-gray-300 rounded-lg mb-4 focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition"
        />

        <button
          @click="changePassword"
          class="w-full py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition transform hover:scale-105"
        >
          حفظ
        </button>
      </div>

      <!-- تسجيل الخروج -->
      <div>
        <button
          @click="logout"
          class="w-full py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition transform hover:scale-105"
        >
          تسجيل الخروج
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import apiClient from '@/axios';

const authStore = useAuthStore();

// الحقول
const currentPassword = ref('');
const newPassword = ref('');
const confirmPassword = ref('');

// تغيير كلمة السر
const changePassword = async () => {
  if (!currentPassword.value || !newPassword.value || !confirmPassword.value) {
    alert('يرجى ملء جميع الحقول');
    return;
  }
  if (newPassword.value !== confirmPassword.value) {
    alert('كلمة السر الجديدة وتأكيدها غير متطابقين');
    return;
  }

  try {
    await apiClient.post(
      '/user/change-password',
      {
        current_password: currentPassword.value,
        new_password: newPassword.value,
        new_password_confirmation: confirmPassword.value
      },
      {
        headers: { Authorization: `Bearer ${authStore.token}` }
      }
    );
    alert('تم تغيير كلمة السر بنجاح');
    currentPassword.value = '';
    newPassword.value = '';
    confirmPassword.value = '';
  } catch (err) {
    if (err.response && err.response.data?.message) {
      alert(err.response.data.message);
    } else {
      console.error(err);
      alert('حدث خطأ أثناء تغيير كلمة السر');
    }
  }
};

// تسجيل الخروج
const logout = () => {
  authStore.logout();
};
</script>

<style scoped>
/* تحسينات إضافية للواجهة */
input::placeholder {
  color: #9ca3af; /* نص رمادي لطيف */
}
</style>

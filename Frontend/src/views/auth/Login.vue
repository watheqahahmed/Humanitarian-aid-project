<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">تسجيل الدخول</h2>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
          <input v-model="email" type="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-6">
          <label for="password" class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور</label>
          <input v-model="password" type="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="flex items-center justify-between">
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            تسجيل الدخول
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from '../../axios'; // استخدام Axios المركزي
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const email = ref('');
const password = ref('');

const router = useRouter();
const authStore = useAuthStore();

const login = async () => {
  try {
    const response = await apiClient.post('/login', { // استخدام apiClient بدلاً من axios مباشرة
      email: email.value,
      password: password.value,
    });

    // تخزين البيانات في Pinia
    authStore.setToken(response.data.token);
    authStore.setUser(response.data.user);

    // إعادة التوجيه بناءً على دور المستخدم
    const role = response.data.user.role;
    if (role === 'admin') {
      router.push({ name: 'admin.dashboard' });
    } else if (role === 'volunteer') {
      router.push({ name: 'volunteer.dashboard' });
    } else if (role === 'beneficiary') {
      router.push({ name: 'beneficiary.dashboard' });
    }

  } catch (error) {
    console.error(error);
    alert('فشل تسجيل الدخول. الرجاء التحقق من البيانات.');
  }
};
</script>

<style scoped>
</style>

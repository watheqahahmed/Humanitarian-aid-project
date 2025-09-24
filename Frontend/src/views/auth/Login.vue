<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4" dir="rtl">
    <div class="bg-white p-8 md:p-10 rounded-2xl shadow-2xl w-full max-w-md animate-fade-in-up">

      <!-- نص ترحيبي -->
      <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">مرحبًا بك مجددًا!</h2>
        <p class="text-gray-600 text-sm md:text-base">سجّل الدخول للوصول إلى حسابك واستكشاف خدماتنا الخيرية.</p>
      </div>

      <form @submit.prevent="login" novalidate>
        <!-- البريد الإلكتروني -->
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">البريد الإلكتروني</label>
          <input
            v-model="email"
            type="email"
            id="email"
            :class="['shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-all duration-300', emailError ? 'border-red-500' : 'border-gray-300']"
            placeholder="example@mail.com"
            required
          >
          <p v-if="emailError" class="text-red-500 text-xs mt-1">{{ emailError }}</p>
        </div>

        <!-- كلمة المرور -->
        <div class="mb-6">
          <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">كلمة المرور</label>
          <input
            v-model="password"
            type="password"
            id="password"
            :class="['shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline transition-all duration-300', passwordError ? 'border-red-500' : 'border-gray-300']"
            placeholder="********"
            required
          >
          <p v-if="passwordError" class="text-red-500 text-xs mt-1">{{ passwordError }}</p>
        </div>

        <!-- زر تسجيل الدخول -->
        <div class="flex justify-center">
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full focus:outline-none focus:shadow-outline shadow-lg transition-transform transform hover:scale-105"
          >
            تسجيل الدخول
          </button>
        </div>
      </form>

      <!-- Toast Notification -->
      <transition name="fade">
        <div
          v-if="toast.visible"
          class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 animate-fade-in-up"
        >
          {{ toast.message }}
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import apiClient from '../../axios';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const email = ref('');
const password = ref('');
const emailError = ref('');
const passwordError = ref('');
const router = useRouter();
const authStore = useAuthStore();

const toast = reactive({
  visible: false,
  message: '',
});

const showToast = (message) => {
  toast.message = message;
  toast.visible = true;
  setTimeout(() => {
    toast.visible = false;
  }, 3000);
};

const login = async () => {
  emailError.value = '';
  passwordError.value = '';

  let valid = true;
  if (!email.value) {
    emailError.value = 'الرجاء إدخال البريد الإلكتروني';
    valid = false;
  }
  if (!password.value) {
    passwordError.value = 'الرجاء إدخال كلمة المرور';
    valid = false;
  }
  if (!valid) return;

  try {
    const response = await apiClient.post('/login', {
      email: email.value,
      password: password.value,
    });

    authStore.setToken(response.data.token);
    authStore.setUser(response.data.user);

    const role = response.data.user.role;
    if (role === 'admin') router.push({ name: 'admin.dashboard' });
    else if (role === 'volunteer') router.push({ name: 'volunteer.dashboard' });
    else if (role === 'beneficiary') router.push({ name: 'beneficiary.dashboard' });

  } catch (error) {
    console.error(error);
    showToast('فشل تسجيل الدخول. الرجاء التحقق من البيانات.');
  }
};
</script>

<style scoped>
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease forwards;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

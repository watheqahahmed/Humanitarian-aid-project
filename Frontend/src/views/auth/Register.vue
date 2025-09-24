<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white shadow-2xl rounded-2xl p-8 md:p-10 animate-fade-in-up">

      <!-- نص ترحيبي -->
      <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">مرحبًا بك!</h2>
        <p class="text-gray-600 text-sm md:text-base">قم بإنشاء حساب للوصول إلى خدماتنا واستكشاف جميع المزايا.</p>
      </div>

      <form @submit.prevent="handleRegister" novalidate class="space-y-5">
        <!-- الاسم الكامل -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
          <input
            v-model="form.name"
            type="text"
            placeholder="أدخل اسمك الكامل"
            :class="['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800 transition-all duration-300', errors.name ? 'border-red-500' : 'border-gray-300']"
            required
          />
          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <!-- البريد الإلكتروني -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
          <input
            v-model="form.email"
            type="email"
            placeholder="أدخل بريدك الإلكتروني"
            :class="['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800 transition-all duration-300', errors.email ? 'border-red-500' : 'border-gray-300']"
            required
          />
          <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
        </div>

        <!-- كلمة المرور -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
          <input
            v-model="form.password"
            type="password"
            placeholder="أدخل كلمة المرور"
            :class="['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800 transition-all duration-300', errors.password ? 'border-red-500' : 'border-gray-300']"
            required
          />
          <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
        </div>

        <!-- تأكيد كلمة المرور -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="أعد إدخال كلمة المرور"
            :class="['w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800 transition-all duration-300', errors.password_confirmation ? 'border-red-500' : 'border-gray-300']"
            required
          />
          <p v-if="errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ errors.password_confirmation }}</p>
        </div>

        <!-- الدور -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">الدور</label>
          <select
            v-model="form.role"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 text-gray-800 border-gray-300"
          >
            <!-- <option value="admin">مسؤول</option> -->
            <option value="beneficiary">مستفيد</option>
            <option value="volunteer">متطوع</option>
          </select>
        </div>

        <!-- زر التسجيل -->
        <button
          type="submit"
          class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full shadow-lg transition-transform transform hover:scale-105"
        >
          تسجيل
        </button>
      </form>

      <!-- رابط تسجيل الدخول -->
      <p class="mt-6 text-center text-sm text-gray-600">
        لديك حساب بالفعل؟
        <router-link to="/login" class="text-blue-600 hover:underline">سجل الدخول هنا</router-link>
      </p>

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
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'admin',
});

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const toast = reactive({
  visible: false,
  message: '',
});

const showToast = (message) => {
  toast.message = message;
  toast.visible = true;
  setTimeout(() => { toast.visible = false; }, 3000);
};

const handleRegister = async () => {
  errors.name = '';
  errors.email = '';
  errors.password = '';
  errors.password_confirmation = '';

  let valid = true;
  if (!form.value.name) { errors.name = 'الرجاء إدخال الاسم الكامل'; valid = false; }
  if (!form.value.email) { errors.email = 'الرجاء إدخال البريد الإلكتروني'; valid = false; }
  if (!form.value.password) { errors.password = 'الرجاء إدخال كلمة المرور'; valid = false; }
  if (form.value.password !== form.value.password_confirmation) {
    errors.password_confirmation = 'كلمتا المرور غير متطابقتين'; valid = false;
  }

  if (!valid) return;

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/register', form.value);
    localStorage.setItem('authToken', response.data.token);

    const role = response.data.user.role;
    if (role === 'admin') router.push('/admin/dashboard');
    else if (role === 'volunteer') router.push('/volunteer/dashboard');
    else router.push('/beneficiary/dashboard');
  } catch (error) {
    console.error('Registration failed:', error.response?.data || error.message);
    showToast(error.response?.data?.message || 'فشل التسجيل');
  }
};
</script>

<style scoped>
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-up { animation: fadeInUp 0.8s ease forwards; }

.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>

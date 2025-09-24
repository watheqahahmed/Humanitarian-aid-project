<template>
  <section id="donate" class="min-h-screen flex items-center justify-center py-16 bg-gradient-to-br from-blue-100 to-green-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-500" dir="rtl">
    <div class="bg-white dark:bg-gray-700 p-6 md:p-8 rounded-2xl shadow-xl w-full max-w-md transform transition-all duration-500 hover:scale-105">
      <h2 class="text-3xl md:text-4xl font-extrabold mb-4 text-center text-blue-600 dark:text-blue-400 animate-fade-in-down transition-colors duration-500">
        تبرع الآن
      </h2>
      <p class="text-center text-gray-600 dark:text-gray-300 mb-6 text-sm md:text-base animate-fade-in-up transition-colors duration-500">
        مساهمتك تحدث فرقًا. املأ النموذج أدناه لتساعدنا في الوصول إلى المزيد من المحتاجين.
      </p>
      <form @submit.prevent="submitDonation">
        <div class="mb-4">
          <label for="donor_name" class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm transition-colors duration-500">اسم المتبرع <span class="text-gray-400 font-normal dark:text-gray-500">(اختياري)</span></label>
          <input
            v-model="donationForm.donor_name"
            type="text"
            id="donor_name"
            class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
            :class="{ 'border-red-500': errors.donor_name }"
          />
          <p v-if="errors.donor_name" class="text-red-500 text-xs mt-1 animate-fade-in-down">{{ errors.donor_name }}</p>
        </div>

        <div class="mb-4">
          <label for="email" class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm transition-colors duration-500">البريد الإلكتروني</label>
          <input
            v-model="donationForm.email"
            type="email"
            id="email"
            class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
            :class="{ 'border-red-500': errors.email }"
          />
          <p v-if="errors.email" class="text-red-500 text-xs mt-1 animate-fade-in-down">{{ errors.email }}</p>
        </div>

        <div class="mb-4">
          <label for="phone" class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm transition-colors duration-500">رقم الهاتف</label>
          <input
            v-model="donationForm.phone"
            type="tel"
            id="phone"
            class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
            :class="{ 'border-red-500': errors.phone }"
          />
          <p v-if="errors.phone" class="text-red-500 text-xs mt-1 animate-fade-in-down">{{ errors.phone }}</p>
        </div>

        <div class="mb-4">
          <label for="type" class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm transition-colors duration-500">نوع التبرع</label>
          <select
            v-model="donationForm.type"
            id="type"
            class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
            :class="{ 'border-red-500': errors.type }"
          >
            <option value="food">مواد غذائية</option>
            <option value="clothes">ملابس</option>
            <option value="medicine">دواء</option>
          </select>
          <p v-if="errors.type" class="text-red-500 text-xs mt-1 animate-fade-in-down">{{ errors.type }}</p>
        </div>

        <div class="mb-6">
          <label for="quantity" class="block text-gray-700 dark:text-gray-200 font-semibold mb-1 text-sm transition-colors duration-500">الكمية</label>
          <input
            v-model.number="donationForm.quantity"
            type="number"
            id="quantity"
            class="shadow-sm border rounded-lg w-full py-2 px-3 text-gray-700 dark:text-gray-200 dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
            :class="{ 'border-red-500': errors.quantity }"
          />
          <p v-if="errors.quantity" class="text-red-500 text-xs mt-1 animate-fade-in-down">{{ errors.quantity }}</p>
        </div>

        <div class="flex items-center justify-center">
          <button
            type="submit"
            class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-10 rounded-full focus:outline-none focus:shadow-outline-blue transition-all duration-300 transform hover:-translate-y-1 shadow"
          >
            إرسال
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import apiClient from '../../axios';
import { useToast } from 'vue-toast-notification';

const donationForm = ref({
  donor_name: '',
  email: '',
  phone: '',
  type: 'food',
  quantity: 1,
});

const errors = ref({});
const toast = useToast();

const validateForm = () => {
  errors.value = {};
  if (!donationForm.value.email) errors.value.email = 'البريد الإلكتروني مطلوب.';
  if (!donationForm.value.phone) errors.value.phone = 'رقم الهاتف مطلوب.';
  if (!donationForm.value.type) errors.value.type = 'نوع التبرع مطلوب.';
  if (!donationForm.value.quantity || donationForm.value.quantity <= 0) errors.value.quantity = 'الكمية يجب أن تكون أكبر من صفر.';
  return Object.keys(errors.value).length === 0;
};

const submitDonation = async () => {
  if (!validateForm()) {
    toast.error('الرجاء تصحيح الأخطاء في النموذج قبل الإرسال.', { position: 'top' });
    return;
  }
  try {
    await apiClient.post('/donations', donationForm.value);
    toast.success('شكراً لك! تم استلام تبرعك بنجاح.', { position: 'top' });
    donationForm.value = { donor_name: '', email: '', phone: '', type: 'food', quantity: 1 };
  } catch (error) {
    console.error('فشل التبرع:', error);
    toast.error('فشل في إرسال التبرع. الرجاء المحاولة مرة أخرى.', { position: 'top' });
  }
};
</script>

<style scoped>
@keyframes fade-in-down {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-down { animation: fade-in-down 0.5s ease-out; }
.animate-fade-in-up { animation: fade-in-down 0.5s ease-out 0.2s backwards; }
</style>

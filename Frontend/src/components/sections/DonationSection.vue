<template>
  <section id="donate" class="min-h-screen flex items-center justify-center bg-gray-100 p-8">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
      <h2 class="text-4xl font-bold mb-6 text-center text-gray-800">تبرع الآن</h2>
      <form @submit.prevent="submitDonation">
        <!-- الاسم -->
        <div class="mb-4">
          <label for="donor_name" class="block text-gray-700 text-sm font-bold mb-2">اسم المتبرع (اختياري)</label>
          <input
            v-model="donationForm.donor_name"
            type="text"
            id="donor_name"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          >
        </div>

        <!-- البريد الإلكتروني -->
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
          <input
            v-model="donationForm.email"
            type="email"
            id="email"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            required
          >
        </div>

        <!-- رقم الهاتف -->
        <div class="mb-4">
          <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
          <input
            v-model="donationForm.phone"
            type="tel"
            id="phone"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            required
          >
        </div>

        <!-- نوع التبرع -->
        <div class="mb-4">
          <label for="type" class="block text-gray-700 text-sm font-bold mb-2">نوع التبرع</label>
          <select
            v-model="donationForm.type"
            id="type"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            required
          >
            <option value="food">مواد غذائية</option>
            <option value="clothes">ملابس</option>
            <option value="medicine">دواء</option>
          </select>
        </div>

        <!-- الكمية -->
        <div class="mb-6">
          <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">الكمية</label>
          <input
            v-model.number="donationForm.quantity"
            type="number"
            id="quantity"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            required
          >
        </div>

        <div class="flex items-center justify-center">
          <button
            type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded focus:outline-none focus:shadow-outline"
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

const donationForm = ref({
  donor_name: '',
  email: '',
  phone: '',
  type: 'food',
  quantity: 1,
});

const submitDonation = async () => {
  try {
    // استخدام المسار العام للتبرع بدون مصادقة
    await apiClient.post('/donations', donationForm.value);

    alert('شكراً لك! تم استلام تبرعك بنجاح.');

    // إعادة تعيين النموذج
    donationForm.value = { donor_name: '', email: '', phone: '', type: 'food', quantity: 1 };
  } catch (error) {
    console.error('فشل التبرع:', error);
    alert('فشل في إرسال التبرع. الرجاء المحاولة مرة أخرى.');
  }
};
</script>

<style scoped>
</style>

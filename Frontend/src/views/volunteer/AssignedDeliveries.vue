<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">مهام التوزيع المخصصة</h1>
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستفيد</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="delivery in deliveries" :key="delivery.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ delivery.beneficiary.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(delivery.delivery_status)">
                {{ delivery.delivery_status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button @click="markAsCompleted(delivery.id)" v-if="delivery.delivery_status === 'in_progress'" class="text-green-600 hover:text-green-900 ml-2">تأكيد التسليم</button>
              <label v-if="delivery.delivery_status === 'in_progress'" for="proof_file" class="text-blue-600 hover:text-blue-900 ml-2 cursor-pointer">
                رفع إثبات
              </label>
              <a v-if="delivery.proof_file" :href="delivery.proof_file" target="_blank" class="text-blue-500 hover:underline">عرض الملف</a>
              <span v-else>لا يوجد إثبات</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-if="selectedDeliveryId" class="mt-8 bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-xl font-bold mb-4">رفع إثبات التسليم</h2>
      <form @submit.prevent="uploadProof">
        <div class="mb-4">
          <label for="proof_file" class="block text-gray-700 text-sm font-bold mb-2">اختر ملف</label>
          <input type="file" @change="onFileChange" id="proof_file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          رفع
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';

const deliveries = ref([]);
const authStore = useAuthStore();
const proofFile = ref(null);
const selectedDeliveryId = ref(null);

const fetchDeliveries = async () => {
  try {
    const response = await apiClient.get('/volunteer/deliveries', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    deliveries.value = response.data;
  } catch (error) {
    console.error('فشل في جلب مهام التوزيع:', error);
  }
};

const onFileChange = (e) => {
  proofFile.value = e.target.files[0];
};

const markAsCompleted = async (deliveryId) => {
  selectedDeliveryId.value = deliveryId;
  // يمكنك هنا إضافة منطق لتأكيد التسليم بدون ملف إذا كان ذلك مسموحًا
};

const uploadProof = async () => {
  if (!selectedDeliveryId.value || !proofFile.value) {
    alert('الرجاء اختيار مهمة وملف لرفع الإثبات.');
    return;
  }

  const formData = new FormData();
  formData.append('proof_file', proofFile.value);
  formData.append('delivery_status', 'completed');

  try {
    await apiClient.post(`/volunteer/deliveries/${selectedDeliveryId.value}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${authStore.token}`
      }
    });
    alert('تم رفع إثبات التسليم بنجاح.');
    await fetchDeliveries(); // إعادة جلب البيانات
    selectedDeliveryId.value = null;
    proofFile.value = null;
  } catch (error) {
    console.error('فشل في رفع إثبات التسليم:', error);
    alert('فشل في رفع إثبات التسليم.');
  }
};

const getStatusClass = (status) => {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-yellow-100 text-yellow-800': status === 'assigned',
    'bg-blue-100 text-blue-800': status === 'in_progress',
    'bg-green-100 text-green-800': status === 'completed',
  };
};

onMounted(() => {
  fetchDeliveries();
});
</script>

<style scoped>
</style>

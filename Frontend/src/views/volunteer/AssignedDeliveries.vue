<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">مهام التوزيع المخصصة</h1>

    <!-- جدول المهام -->
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستفيد</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">نوع المساعدة</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="delivery in deliveries" :key="delivery.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ delivery.beneficiary?.name || 'غير محدد' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ delivery.donation?.type || 'نوع المساعدة غير محدد' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(delivery.delivery_status)">
                {{ delivery.delivery_status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <!-- زر رفع الإثبات لكل مهمة غير مكتملة -->
              <button
                v-if="delivery.delivery_status !== 'completed'"
                @click="selectDelivery(delivery.id)"
                class="text-green-600 hover:text-green-900 ml-2"
              >
                رفع إثبات / تأكيد التسليم
              </button>

              <!-- رابط الملف إذا موجود -->

             <a
  v-if="delivery.proof_file"
  :href="`http://localhost:8000/storage/${delivery.proof_file}`"
  target="_blank"
  class="text-blue-500 hover:underline ml-2"
>
  عرض الملف
</a>






              <!-- رسالة إذا لا يوجد إثبات -->
              <span v-else-if="delivery.delivery_status !== 'completed'" class="ml-2">لا يوجد إثبات</span>

              <!-- رسالة تم التسليم -->
              <span v-else class="ml-2 text-green-700 font-semibold">تم التسليم</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- نموذج رفع الإثبات -->
    <div v-if="selectedDeliveryId" class="mt-8 bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-xl font-bold mb-4">رفع إثبات التسليم</h2>
      <form @submit.prevent="uploadProof">
        <div class="mb-4">
          <label for="proof_file" class="block text-gray-700 text-sm font-bold mb-2">اختر ملف</label>
          <input
            type="file"
            @change="onFileChange"
            id="proof_file"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            required
          >
        </div>
        <button
          type="submit"
          :disabled="loading"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        >
          {{ loading ? 'جاري الرفع...' : 'رفع' }}
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
const loading = ref(false);

const fetchDeliveries = async () => {
  try {
    const response = await apiClient.get('/volunteer/deliveries', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    deliveries.value = response.data;
  } catch (error) {
    console.error('فشل في جلب مهام التوزيع:', error);
    alert('❌ فشل في جلب المهام.');
  }
};

const onFileChange = (e) => {
  proofFile.value = e.target.files[0];
  console.log('Selected file:', proofFile.value);
};

const selectDelivery = (deliveryId) => {
  selectedDeliveryId.value = deliveryId;
};

const uploadProof = async () => {
  if (!selectedDeliveryId.value || !proofFile.value) {
    alert('الرجاء اختيار مهمة وملف لرفع الإثبات.');
    return;
  }

  const formData = new FormData();
  formData.append('proof_file', proofFile.value);
  formData.append('delivery_status', 'completed');
  formData.append('_method', 'PUT'); // <- إضافة هذا السطر لتجنب خطأ 422

  loading.value = true;
  try {
    await apiClient.post(`/volunteer/deliveries/${selectedDeliveryId.value}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        Authorization: `Bearer ${authStore.token}`
      }
    });
    alert('✅ تم رفع إثبات التسليم بنجاح.');
    await fetchDeliveries();
    selectedDeliveryId.value = null;
    proofFile.value = null;
  } catch (error) {
    console.error('فشل في رفع إثبات التسليم:', error);
    const message = error.response?.data?.message || '❌ فشل في رفع الإثبات.';
    alert(message);
  } finally {
    loading.value = false;
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

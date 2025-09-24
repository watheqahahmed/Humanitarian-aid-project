<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 text-center">مهام التوزيع المخصصة</h1>

    <!-- جدول المهام -->
    <div class="bg-white p-6 rounded-2xl shadow-md overflow-x-auto mb-8">
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
          <tr v-for="delivery in deliveries" :key="delivery.id" class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap">{{ delivery.beneficiary?.name || 'غير محدد' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ delivery.donation?.type || 'نوع المساعدة غير محدد' }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(delivery.delivery_status)">
                {{ delivery.delivery_status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
              <!-- زر رفع الإثبات لكل مهمة غير مكتملة -->
              <button
                v-if="delivery.delivery_status !== 'completed'"
                @click="selectDelivery(delivery.id)"
                class="text-green-600 hover:text-green-900 transition"
              >
                رفع إثبات / تأكيد التسليم
              </button>

              <!-- رابط الملف إذا موجود -->
              <a
                v-if="delivery.proof_file"
                :href="`http://localhost:8000/storage/${delivery.proof_file}`"
                target="_blank"
                class="text-blue-500 hover:underline transition"
              >
                عرض الملف
              </a>

              <!-- رسالة إذا لا يوجد إثبات -->
              <span v-else-if="delivery.delivery_status !== 'completed'" class="text-gray-500">لا يوجد إثبات</span>

              <!-- رسالة تم التسليم -->
              <span v-else class="text-green-700 font-semibold">تم التسليم</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- نموذج رفع الإثبات -->
    <div v-if="selectedDeliveryId" class="mt-8 bg-white p-6 rounded-2xl shadow-md max-w-lg mx-auto">
      <h2 class="text-xl font-bold mb-4 text-gray-700">رفع إثبات التسليم</h2>
      <form @submit.prevent="uploadProof" class="space-y-4">
        <div>
          <label for="proof_file" class="block text-gray-700 text-sm font-semibold mb-2">اختر ملف</label>
          <input
            type="file"
            @change="onFileChange"
            id="proof_file"
            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
            required
          >
        </div>
        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
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
  formData.append('_method', 'PUT');

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

<style scoped>
/* تحسين عرض الملف */
a {
  white-space: nowrap;
}
</style>

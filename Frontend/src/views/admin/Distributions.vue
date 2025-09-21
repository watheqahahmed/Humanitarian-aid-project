<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">إدارة عمليات التوزيع</h1>

    <!-- زر إنشاء توزيع جديد -->
    <div class="mb-4 text-right">
      <button @click="showModal = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        إنشاء توزيع جديد
      </button>
    </div>

    <!-- جدول التوزيعات -->
    <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المتطوع</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المستفيد</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">التبرع</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">إثبات التسليم</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="distribution in distributions" :key="distribution.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ distribution.volunteer.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ distribution.beneficiary.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ distribution.donation.title || distribution.donation.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(distribution.delivery_status)">
                {{ distribution.delivery_status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <!-- عرض ملف الإثبات فقط إذا موجود -->
              <a
                v-if="distribution.proof_file"
                :href="`http://127.0.0.1:8000/storage/${distribution.proof_file}`"
                target="_blank"
                class="text-blue-500 hover:underline"
              >
                عرض الملف
              </a>
              <span v-else>لا يوجد</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- نموذج إنشاء توزيع جديد -->
    <Modal :show="showModal" @close="showModal = false">
      <template #header>
        إنشاء مهمة توزيع
      </template>
      <template #body>
        <form @submit.prevent="createDistribution">
          <div class="mb-4">
            <label for="volunteer" class="block text-gray-700 text-sm font-bold mb-2">اختر متطوعاً</label>
            <select v-model="form.volunteer_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <option disabled value="">الرجاء الاختيار</option>
              <option v-for="volunteer in volunteers" :key="volunteer.id" :value="volunteer.id">{{ volunteer.name }}</option>
            </select>
          </div>

          <div class="mb-4">
            <label for="beneficiary" class="block text-gray-700 text-sm font-bold mb-2">اختر مستفيداً</label>
            <select v-model="form.beneficiary_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <option disabled value="">الرجاء الاختيار</option>
              <option v-for="beneficiary in beneficiaries" :key="beneficiary.id" :value="beneficiary.id">{{ beneficiary.name }}</option>
            </select>
          </div>

          <div class="mb-4">
            <label for="donation" class="block text-gray-700 text-sm font-bold mb-2">اختر التبرع</label>
            <select v-model="form.donation_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              <option disabled value="">الرجاء الاختيار</option>
              <option v-for="donation in donations" :key="donation.id" :value="donation.id">{{ donation.title || donation.id }}</option>
            </select>
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
              إنشاء
            </button>
          </div>
        </form>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';
import Modal from '../../components/common/Modal.vue';

const distributions = ref([]);
const volunteers = ref([]);
const beneficiaries = ref([]);
const donations = ref([]);
const showModal = ref(false);
const form = ref({
  volunteer_id: '',
  beneficiary_id: '',
  donation_id: '',
  delivery_status: 'assigned',
});

const authStore = useAuthStore();

// جلب جميع التوزيعات
const fetchDistributions = async () => {
  try {
    const response = await apiClient.get('/admin/distributions', {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    distributions.value = response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات التوزيع:', error);
  }
};

// جلب خيارات المتطوعين والمستفيدين والتبرعات
const fetchSelectOptions = async () => {
  try {
    const [volunteersResponse, beneficiariesResponse, donationsResponse] = await Promise.all([
      apiClient.get('/admin/volunteers', { headers: { Authorization: `Bearer ${authStore.token}` } }),
      apiClient.get('/admin/beneficiaries', { headers: { Authorization: `Bearer ${authStore.token}` } }),
      apiClient.get('/admin/donations', { headers: { Authorization: `Bearer ${authStore.token}` } }),
    ]);
    volunteers.value = volunteersResponse.data;
    beneficiaries.value = beneficiariesResponse.data;
    donations.value = donationsResponse.data;
  } catch (error) {
    console.error('فشل في جلب بيانات الخيارات:', error);
  }
};

// إنشاء توزيع جديد
const createDistribution = async () => {
  try {
    await apiClient.post('/admin/distributions', form.value, {
      headers: { Authorization: `Bearer ${authStore.token}` }
    });
    alert('تم إنشاء مهمة التوزيع بنجاح.');
    showModal.value = false;
    form.value = { volunteer_id: '', beneficiary_id: '', donation_id: '', delivery_status: 'assigned' };
    await fetchDistributions();
  } catch (error) {
    console.error('فشل في إنشاء التوزيع:', error.response?.data || error);
    alert('فشل في إنشاء التوزيع.');
  }
};

// لتلوين حالة التوزيع
const getStatusClass = (status) => {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-yellow-100 text-yellow-800': status === 'assigned',
    'bg-blue-100 text-blue-800': status === 'in_progress',
    'bg-green-100 text-green-800': status === 'completed',
  };
};

onMounted(() => {
  fetchDistributions();
  fetchSelectOptions();
});
</script>

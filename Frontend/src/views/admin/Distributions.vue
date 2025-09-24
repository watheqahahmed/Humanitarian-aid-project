<template>
  <div class="min-h-screen p-8 bg-gray-100">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 text-center">إدارة عمليات التوزيع</h1>

    <!-- زر إنشاء توزيع جديد -->
    <div class="mb-6 flex justify-end">
      <button
        @click="showModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105"
      >
        إنشاء توزيع جديد
      </button>
    </div>

    <!-- جدول التوزيعات -->
    <div class="bg-white p-6 rounded-2xl shadow-lg overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200" dir="rtl">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">المتطوع</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">المستفيد</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">التبرع</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">نوع التبرع</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">الحالة</th>
            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">إثبات التسليم</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="distribution in distributions" :key="distribution.id" class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 text-right">{{ distribution.volunteer.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-right">{{ distribution.beneficiary.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-right">{{ distribution.donation.title || distribution.donation.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-right">{{ distribution.donation.type || 'غير محدد' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
              <span :class="getStatusClass(distribution.delivery_status)">
                {{ distribution.delivery_status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
              <a v-if="distribution.proof_file"
                 :href="`http://127.0.0.1:8000/storage/${distribution.proof_file}`"
                 target="_blank"
                 class="text-blue-800 font-semibold hover:underline transition">
                 عرض الملف
              </a>
              <span v-else class="text-red-600 font-semibold">لا يوجد إثبات</span>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="distributions.length === 0" class="text-center p-4 text-gray-500">
        لا توجد توزيعات.
      </div>
    </div>

    <!-- نموذج إنشاء توزيع جديد -->
    <Modal :show="showModal" @close="showModal = false">
      <template #header>
        إنشاء مهمة توزيع
      </template>
      <template #body>
        <form @submit.prevent="createDistribution" class="space-y-4">
          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-1">اختر متطوعاً</label>
            <select v-model="form.volunteer_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 text-gray-800">
              <option disabled value="">الرجاء الاختيار</option>
              <option v-for="volunteer in volunteers" :key="volunteer.id" :value="volunteer.id">{{ volunteer.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-1">اختر مستفيداً</label>
            <select v-model="form.beneficiary_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 text-gray-800">
              <option disabled value="">الرجاء الاختيار</option>
              <option v-for="beneficiary in beneficiaries" :key="beneficiary.id" :value="beneficiary.id">{{ beneficiary.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-gray-700 text-sm font-semibold mb-1">اختر التبرع</label>
            <select v-model="form.donation_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 text-gray-800">
              <option disabled value="">الرجاء الاختيار</option>
              <option v-for="donation in donations" :key="donation.id" :value="donation.id">
                {{ donation.title || donation.id }}
              </option>
            </select>
          </div>

          <!-- حقل عرض النوع تلقائيًا -->
          <div v-if="selectedDonationType" class="mt-2">
            <label class="block text-gray-700 text-sm font-semibold mb-1">نوع التبرع</label>
            <input type="text" :value="selectedDonationType" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-800">
          </div>

          <div class="flex justify-end">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow transition transform hover:scale-105">
              إنشاء
            </button>
          </div>
        </form>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import apiClient from '../../axios';
import { useAuthStore } from '../../stores/auth';
import Modal from '../../components/common/Modal.vue';

const distributions = ref([]);
const volunteers = ref([]);
const beneficiaries = ref([]);
const donations = ref([]);
const showModal = ref(false);
const form = ref({ volunteer_id: '', beneficiary_id: '', donation_id: '', delivery_status: 'assigned' });

const authStore = useAuthStore();

// حساب نوع التبرع المختار تلقائيًا
const selectedDonationType = computed(() => {
  const donation = donations.value.find(d => d.id === form.value.donation_id);
  return donation ? donation.type : '';
});

const fetchDistributions = async () => {
  try {
    const response = await apiClient.get('/admin/distributions', { headers: { Authorization: `Bearer ${authStore.token}` } });
    distributions.value = response.data;
  } catch (error) {
    console.error('فشل في جلب بيانات التوزيع:', error);
  }
};

const fetchSelectOptions = async () => {
  try {
    const [volunteersRes, beneficiariesRes, donationsRes] = await Promise.all([
      apiClient.get('/admin/volunteers', { headers: { Authorization: `Bearer ${authStore.token}` } }),
      apiClient.get('/admin/beneficiaries', { headers: { Authorization: `Bearer ${authStore.token}` } }),
      apiClient.get('/admin/donations', { headers: { Authorization: `Bearer ${authStore.token}` } }),
    ]);
    volunteers.value = volunteersRes.data;
    beneficiaries.value = beneficiariesRes.data;
    donations.value = donationsRes.data;
  } catch (error) {
    console.error('فشل في جلب بيانات الخيارات:', error);
  }
};

const createDistribution = async () => {
  try {
    await apiClient.post('/admin/distributions', form.value, { headers: { Authorization: `Bearer ${authStore.token}` } });
    alert('تم إنشاء مهمة التوزيع بنجاح.');
    showModal.value = false;
    form.value = { volunteer_id: '', beneficiary_id: '', donation_id: '', delivery_status: 'assigned' };
    await fetchDistributions();
  } catch (error) {
    console.error('فشل في إنشاء التوزيع:', error.response?.data || error);
    alert('فشل في إنشاء التوزيع.');
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
  fetchDistributions();
  fetchSelectOptions();
});
</script>

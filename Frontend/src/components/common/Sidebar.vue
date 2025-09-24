<template>
  <button
    class="fixed top-4 left-4 md:hidden z-[1000] flex flex-col justify-between w-8 h-6 p-1 bg-blue-500 rounded-md shadow-lg"
    @click="toggleSidebar"
    v-if="!isOpen"
  >
    <span class="block w-full h-[2px] bg-white"></span>
    <span class="block w-full h-[2px] bg-white"></span>
    <span class="block w-full h-[2px] bg-white"></span>
  </button>

  <aside
    :class="['w-64 min-h-screen bg-gradient-to-b from-indigo-50/70 via-white/60 to-blue-50/50 backdrop-blur-2xl border-r border-gray-200 text-gray-800 p-6 flex flex-col relative shadow-2xl z-50 transition-transform duration-300 ease-in-out', 'md:block', { 'translate-x-0': isOpen, '-translate-x-full': !isOpen }] "
    dir="rtl"
  >
    <button
      class="md:hidden absolute top-4 left-4 text-gray-600 p-2 rounded-full hover:bg-gray-200 transition"
      @click="toggleSidebar"
      v-if="isOpen"
    >
      ✖
    </button>

    <div class="relative text-2xl font-bold mb-10 text-right tracking-wide flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span
          class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-inner shadow-green-400"
          title="متصل الآن"
        ></span>
        <span class="text-gray-700">لوحة التحكم</span>
      </div>

      <div class="relative">
        <button @click="toggleDropdown" class="relative text-lg p-1 hover:bg-gray-100 rounded-full transition z-[1000]">
          🔔
          <span
            v-if="unreadCount > 0"
            class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse shadow-lg"
          >
            {{ unreadCount }}
          </span>
        </button>

        <div
          v-if="showDropdown"
          class="absolute right-0 mt-2 w-80 max-h-64 overflow-y-auto bg-white shadow-2xl rounded-xl border border-gray-200 z-[2000] text-sm"
        >
          <div v-if="notifications.length === 0" class="p-4 text-gray-500 text-center text-sm">
            لا توجد إشعارات
          </div>
          <div
            v-for="notif in notifications"
            :key="notif.id"
            class="p-3 border-b last:border-b-0 hover:bg-gray-50 cursor-pointer transition flex flex-col"
            @click="markAsRead(notif)"
          >
            <p :class="{ 'font-semibold text-gray-800': notif.status === 'unread', 'text-gray-600': notif.status === 'read' }">
              {{ notif.message }}
            </p>
            <small class="text-gray-400 mt-1">{{ formatDate(notif.created_at) }}</small>
          </div>
        </div>
      </div>
    </div>

    <nav class="flex-1 relative z-10">
      <ul class="space-y-3">
        <!-- روابط اللوحات المختلفة -->
        <li v-if="authStore.user && authStore.user.role === 'admin'">
          <router-link
            v-for="link in adminLinks"
            :key="link.name"
            :to="link.to"
            class="group flex items-center gap-3 p-3 rounded-2xl transition-all duration-500 text-right relative overflow-hidden"
            :class="{
              'bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-500 text-white shadow-lg scale-[1.02]': $route.name === link.name,
              'hover:bg-blue-50': $route.name !== link.name
            }"
            @click="closeSidebarOnMobile"
          >
            <span
              class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 text-base transition group-hover:scale-110 group-hover:rotate-12 group-hover:bg-blue-100 shadow-sm"
            >
              <i class="fas fa-angle-left"></i>
            </span>
            <span class="font-medium flex-1">{{ link.label }}</span>
          </router-link>
        </li>

        <li v-if="authStore.user && authStore.user.role === 'volunteer'">
          <router-link
            v-for="link in volunteerLinks"
            :key="link.name"
            :to="link.to"
            class="group flex items-center gap-3 p-3 rounded-2xl transition-all duration-500 text-right relative overflow-hidden"
            :class="{
              'bg-gradient-to-r from-blue-400 via-indigo-400 to-cyan-400 text-white shadow-lg scale-[1.02]': $route.name === link.name,
              'hover:bg-blue-50': $route.name !== link.name
            }"
            @click="closeSidebarOnMobile"
          >
            <span
              class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 text-base transition group-hover:scale-110 group-hover:rotate-12 group-hover:bg-blue-100 shadow-sm"
            >
              <i class="fas fa-check-circle"></i>
            </span>
            <span class="font-medium flex-1">{{ link.label }}</span>
          </router-link>
        </li>

        <li v-if="authStore.user && authStore.user.role === 'beneficiary'">
          <router-link
            v-for="link in beneficiaryLinks"
            :key="link.name"
            :to="link.to"
            class="group flex items-center gap-3 p-3 rounded-2xl transition-all duration-500 text-right relative overflow-hidden"
            :class="{
              'bg-gradient-to-r from-blue-300 via-indigo-300 to-cyan-300 text-white shadow-lg scale-[1.02]': $route.name === link.name,
              'hover:bg-blue-50': $route.name !== link.name
            }"
            @click="closeSidebarOnMobile"
          >
            <span
              class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 text-base transition group-hover:scale-110 group-hover:rotate-12 group-hover:bg-blue-100 shadow-sm"
            >
              <i class="fas fa-user"></i>
            </span>
            <span class="font-medium flex-1">{{ link.label }}</span>
          </router-link>
        </li>

        <!-- رابط الإعدادات ديناميكي حسب الدور -->
        <li v-if="authStore.user">
          <router-link
            :to="{ name: authStore.user.role + '.settings' }"
            class="group flex items-center gap-3 p-3 rounded-2xl transition-all duration-500 text-right relative overflow-hidden"
            @click="closeSidebarOnMobile"
          >
            <span
              class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 text-base transition group-hover:scale-110 group-hover:rotate-12 group-hover:bg-blue-100 shadow-sm"
            >
              <i class="fas fa-cog"></i>
            </span>
            <span class="font-medium flex-1">الإعدادات</span>
          </router-link>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../../stores/auth';
import apiClient from '@/axios';

const authStore = useAuthStore();
const isOpen = ref(false);
const toggleSidebar = () => (isOpen.value = !isOpen.value);

const adminLinks = [
  { name: 'admin.dashboard', label: 'الرئيسية', to: { name: 'admin.dashboard' } },
  { name: 'admin.donations', label: 'التبرعات', to: { name: 'admin.donations' } },
  { name: 'admin.aid-requests', label: 'طلبات المساعدة', to: { name: 'admin.aid-requests' } },
  { name: 'admin.distributions', label: 'التوزيعات', to: { name: 'admin.distributions' } },
  { name: 'admin.users', label: 'إدارة المستخدمين', to: { name: 'admin.users' } },
];
const volunteerLinks = [
  { name: 'volunteer.dashboard', label: 'لوحة المتطوع', to: { name: 'volunteer.dashboard' } },
  { name: 'volunteer.assigned-deliveries', label: 'مهام التوزيع ', to: { name: 'volunteer.assigned-deliveries' } },
];
const beneficiaryLinks = [
  { name: 'beneficiary.dashboard', label: 'لوحة المستفيد', to: { name: 'beneficiary.dashboard' } },
  { name: 'beneficiary.aid-requests', label: 'طلباتي للمساعدة', to: { name: 'beneficiary.aid-requests' } },
];

const notifications = ref([]);
const showDropdown = ref(false);
const toggleDropdown = () => (showDropdown.value = !showDropdown.value);
const unreadCount = computed(() => notifications.value.filter(n => n.status === 'unread').length);

const fetchNotifications = async () => {
  try {
    const res = await apiClient.get('/notifications', {
      headers: { Authorization: `Bearer ${authStore.token}` },
    });
    notifications.value = res.data.map(n => ({ ...n, status: n.status || 'unread' }));
  } catch (err) {
    console.error('فشل جلب الإشعارات:', err);
  }
};

const markAsRead = async notif => {
  if (notif.status === 'unread') {
    try {
      await apiClient.post(`/notifications/${notif.id}/mark-as-read`, {}, {
        headers: { Authorization: `Bearer ${authStore.token}` },
      });
      notif.status = 'read';
    } catch (err) {
      console.error('فشل وضع علامة مقروء:', err);
    }
  }
};

const formatDate = dateStr => new Date(dateStr).toLocaleString();

const closeSidebarOnMobile = () => {
  if (window.innerWidth < 768) {
    isOpen.value = false;
  }
};

onMounted(() => fetchNotifications());
</script>

<style scoped>
@media (max-width: 767px) {
  aside {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 999;
  }
}

@media (min-width: 768px) {
  aside {
    display: block !important;
    transform: none !important;
  }
}
</style>

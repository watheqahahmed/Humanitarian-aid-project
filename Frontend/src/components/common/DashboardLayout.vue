<template>
  <div class="flex min-h-screen">
    <!-- Sidebar ثابت على الكمبيوتر -->
    <Sidebar class="hidden md:block w-64" />

    <div class="flex-1 flex flex-col">
      <!-- Header مع زر الإشعارات -->
      <div >
        <h1 class="text-2xl font-bold text-gray-800"> </h1>
        <!-- Notifications -->
        <div >
          <!-- <button @click="toggleDropdown" class="relative text-xl p-2 hover:bg-gray-100 rounded-full transition">
            🔔
            <span
              v-if="unreadCount > 0"
              class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse shadow-lg"
            >
              {{ unreadCount }}
            </span>
          </button> -->

          <!-- قائمة الإشعارات -->
          <transition name="fade-slide">
            <div
              v-if="showDropdown"
              class="absolute right-0 mt-2 w-80 bg-white shadow-2xl rounded-xl overflow-hidden z-50 border border-gray-200"
            >
              <div v-if="notifications.length === 0" class="p-4 text-gray-500 text-center">
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
          </transition>
        </div>
      </div>

      <!-- Overlay + Sidebar متحرك للجوال -->
      <transition name="slide">
        <div v-if="showSidebar" class="fixed inset-0 z-50 flex">
          <!-- الخلفية الشفافة عند فتح Sidebar -->
          <div
            class="fixed inset-0 bg-black bg-opacity-30"
            @click="showSidebar = false"
          ></div>
          <!-- Sidebar نفسه -->
          <Sidebar class="relative w-64 bg-white shadow-lg" />
        </div>
      </transition>

      <!-- المحتوى الرئيسي -->
      <main class="p-6 bg-gray-100 flex-1 transition-all duration-300">
        <router-view></router-view>
      </main>

      <Footer />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Sidebar from './Sidebar.vue';
import Footer from './Footer.vue';
import apiClient from '@/axios';
import { useAuthStore } from '@/stores/auth';

const showSidebar = ref(false);

// Notifications logic
const authStore = useAuthStore();
const notifications = ref([]);
const showDropdown = ref(false);

const toggleDropdown = () => (showDropdown.value = !showDropdown.value);

const unreadCount = computed(() =>
  notifications.value.filter(n => n.status === 'unread').length
);

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
      await apiClient.post(
        `/notifications/${notif.id}/mark-as-read`,
        {},
        { headers: { Authorization: `Bearer ${authStore.token}` } }
      );
      notif.status = 'read';
    } catch (err) {
      console.error('فشل وضع علامة مقروء:', err);
    }
  }
};

const formatDate = dateStr => new Date(dateStr).toLocaleString();

onMounted(() => fetchNotifications());
</script>

<style scoped>
/* حركة انزلاق Sidebar للجوال */
.slide-enter-active, .slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from {
  transform: translateX(-100%);
}
.slide-enter-to {
  transform: translateX(0);
}
.slide-leave-from {
  transform: translateX(0);
}
.slide-leave-to {
  transform: translateX(-100%);
}

/* Fade + Slide للإشعارات */
.fade-slide-enter-active, .fade-slide-leave-active {
  transition: all 0.3s ease;
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}
.fade-slide-enter-to {
  opacity: 1;
  transform: translateY(0);
}
.fade-slide-leave-from {
  opacity: 1;
  transform: translateY(0);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>

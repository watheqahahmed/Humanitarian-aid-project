<template>
  <div class="relative">
    <!-- زر الجرس -->
    <button @click="toggleDropdown" class="relative">
      🔔
      <span
        v-if="unreadCount > 0"
        class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full px-1"
      >
        {{ unreadCount }}
      </span>
    </button>

    <!-- قائمة الإشعارات -->
    <div
      v-if="showDropdown"
      class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg overflow-hidden z-50"
    >
      <div v-if="notifications.length === 0" class="p-4 text-gray-500">
        لا توجد إشعارات
      </div>
      <div
        v-for="notif in notifications"
        :key="notif.id"
        class="p-2 border-b hover:bg-gray-100 cursor-pointer"
        @click="markAsRead(notif)"
      >
        <p :class="{ 'font-bold': notif.status === 'unread' }">{{ notif.message }}</p>
        <small class="text-gray-400">{{ formatDate(notif.created_at) }}</small>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import apiClient from '@/axios';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const notifications = ref([]);
const showDropdown = ref(false);

const toggleDropdown = () => (showDropdown.value = !showDropdown.value);

const unreadCount = computed(
  () => notifications.value.filter(n => n.status === 'unread').length
);

// جلب الإشعارات
const fetchNotifications = async () => {
  try {
    const res = await apiClient.get('/notifications', {
      headers: { Authorization: `Bearer ${authStore.token}` },
    });
    // إضافة status "unread" إذا لم يكن موجودًا
    notifications.value = res.data.map(n => ({
      ...n,
      status: n.status || 'unread',
    }));
  } catch (err) {
    console.error('فشل جلب الإشعارات:', err);
  }
};

// وضع علامة "مقروء"
const markAsRead = async notif => {
  if (notif.status === 'unread') {
    try {
      await apiClient.post(
        `/notifications/${notif.id}/mark-as-read`,
        {},
        {
          headers: { Authorization: `Bearer ${authStore.token}` },
        }
      );
      notif.status = 'read';
    } catch (err) {
      console.error('فشل وضع علامة مقروء:', err);
    }
  }
};

// تنسيق التاريخ
const formatDate = dateStr => new Date(dateStr).toLocaleString();

onMounted(() => {
  fetchNotifications();
});
</script>

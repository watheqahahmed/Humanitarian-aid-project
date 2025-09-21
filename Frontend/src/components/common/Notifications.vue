<template>
  <div class="fixed top-4 right-4 z-50 flex flex-col gap-2">
    <div
      v-for="n in notifications"
      :key="n.id"
      :class="getClasses(n.type)"
      class="opacity-90 hover:opacity-100 transition-opacity duration-300"
    >
      <p class="font-bold">{{ n.message }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useNotificationStore } from '../../stores/notifications';

// استدعاء store الخاص بالإشعارات
const store = useNotificationStore();
const notifications = computed(() => store.notifications);

// دالة لتحديد ألوان الإشعار حسب النوع
const getClasses = (type) => {
  const baseClasses = 'p-4 rounded-lg shadow-lg text-white';
  const typeClasses =
    type === 'success'
      ? 'bg-green-500'
      : type === 'error'
      ? 'bg-red-500'
      : 'bg-blue-500'; // نوع افتراضي info
  return `${baseClasses} ${typeClasses}`;
};
</script>

<style scoped>
/* يمكن إضافة حركات fade in/out إذا أحببت */
</style>

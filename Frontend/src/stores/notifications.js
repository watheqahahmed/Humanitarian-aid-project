import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useNotificationStore = defineStore('notifications', () => {
  const notifications = ref([]);

  /**
   * إضافة إشعار جديد
   * @param {string} message - نص الإشعار
   * @param {string} type - نوع الإشعار: 'success', 'error', 'info'
   * @param {number|null} duration - مدة ظهور الإشعار بالمللي ثانية، null للإبقاء على الإشعار
   */
  const pushNotification = (message, type = 'success', duration = 3000) => {
    const id = Date.now(); // معرف فريد للإشعار
    notifications.value.push({ id, message, type, status: 'unread' });

    // إزالة الإشعار تلقائيًا بعد انتهاء المدة فقط إذا duration محددة
    if (duration !== null) {
      setTimeout(() => {
        removeNotification(id);
      }, duration);
    }
  };

  /**
   * إزالة إشعار حسب المعرف
   * @param {number} id
   */
  const removeNotification = (id) => {
    notifications.value = notifications.value.filter(n => n.id !== id);
  };

  /**
   * وضع علامة "مقروء" على إشعار
   * @param {number} id
   */
  const markAsRead = (id) => {
    const notif = notifications.value.find(n => n.id === id);
    if (notif) notif.status = 'read';
  };

  return { notifications, pushNotification, removeNotification, markAsRead };
});

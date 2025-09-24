<template>
  <!-- خلفية التطبيق كاملة -->
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
    <!-- صفحات التطبيق -->
    <router-view />

    <!-- مكون الإشعارات -->
    <Notifications />
  </div>
</template>

<script setup>
import Notifications from './components/common/Notifications.vue';
import './echo'; // تهيئة Echo عند بدء التطبيق

// عند التشغيل، إذا كان الوضع الليلي محفوظ في localStorage نطبقه مباشرة
if (localStorage.getItem('darkMode') === 'true') {
  document.documentElement.classList.add('dark');
}
</script>

<style>
/* مافي داعي لاستايلات إضافية هنا، Tailwind يتكفل بكل شيء */
</style>

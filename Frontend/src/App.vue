<template>
  <router-view />
  <Notifications />
</template>

<script setup>
import Notifications from './components/common/Notifications.vue';
import './echo'; // تهيئة Echo عند بدء التطبيق
</script>

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';

// استدعاء Echo
import echo from './echo';

// استدعاء وتثبيت مكتبة الإشعارات (Toast)
import Toast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css';

// Importing the theme store is crucial
import { useThemeStore } from './stores/theme';

const app = createApp(App);
const pinia = createPinia();

// تثبيت Pinia و Router
app.use(pinia);
app.use(router);

// تثبيت مكتبة الإشعارات لتكون متاحة في التطبيق بأكمله
app.use(Toast, {
  position: 'top-right',
  duration: 5000,
});

// ربط Echo كـ global property لتستدعيه في أي كومبوننت
app.config.globalProperties.$echo = echo;

// تهيئة الـ Theme Store
const themeStore = useThemeStore(pinia);
themeStore.init();

app.mount('#app');

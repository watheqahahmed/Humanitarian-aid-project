import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';

// استدعاء Echo
import echo from './echo';

const app = createApp(App);

// تثبيت Pinia و Router
app.use(createPinia());
app.use(router);

// ربط Echo كـ global property لتستدعيه في أي كومبوننت
app.config.globalProperties.$echo = echo;

// تهيئة الـ Theme Store
import { useThemeStore } from './stores/theme';
const themeStore = useThemeStore();
themeStore.init();

app.mount('#app');

import { createApp } from 'vue';
import { createPinia } from 'pinia'; // استيراد Pinia
import App from './App.vue';
import router from './router';

const app = createApp(App);
const pinia = createPinia(); // إنشاء مثيل Pinia

app.use(pinia); // استخدام Pinia
app.use(router);
app.mount('#app');

// src/echo.js
import Echo from "laravel-echo";
import Pusher from "pusher-js";

// ربط Pusher بالنافذة
window.Pusher = Pusher;

// تهيئة Laravel Echo
const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,       // مفتاح Pusher من .env
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
});

export default echo;  // تصدير كـ default export

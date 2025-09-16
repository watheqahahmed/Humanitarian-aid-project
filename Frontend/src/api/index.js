// src/api/index.js
import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api', // عنوان URL الأساسي لـ Laravel API
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// مُعترض طلب (Request Interceptor) لإضافة التوكن إلى الرؤوس
api.interceptors.request.use(
  config => {
    const token = localStorage.getItem('access_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

// مُعترض استجابة (Response Interceptor) للتعامل مع أخطاء المصادقة
api.interceptors.response.use(
  response => response,
  error => {
    // إذا كان الخطأ 401 (غير مصرح به)، قم بتسجيل الخروج من المستخدم
    if (error.response && error.response.status === 401 && localStorage.getItem('access_token')) {
      // قم بإزالة التوكن وإعادة توجيه المستخدم إلى صفحة تسجيل الدخول
      localStorage.removeItem('access_token');
      localStorage.removeItem('user');
      // تأكد من أن الـ router قد تم استيراده إذا كنت تريد إعادة التوجيه هنا
      // أو قم بمعالجة هذا في المكونات التي تستخدم الـ API
      window.location.href = '/login'; // طريقة بسيطة لإعادة التوجيه
    }
    return Promise.reject(error);
  }
);

export default api;

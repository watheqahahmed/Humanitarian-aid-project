import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useThemeStore = defineStore('theme', () => {
  const darkMode = ref(false);

  // حفظ الوضع في localStorage عند تغييره
  watch(darkMode, (newVal) => {
    localStorage.setItem('darkMode', newVal ? 'true' : 'false');
    applyTheme();
  });

  // تطبيق الوضع على <html> أو <body>
  const applyTheme = () => {
    if (darkMode.value) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  };

  // تهيئة الوضع عند تحميل الصفحة
  const init = () => {
    const saved = localStorage.getItem('darkMode');
    darkMode.value = saved === 'true';
    applyTheme();
  };

  return { darkMode, applyTheme, init };
});

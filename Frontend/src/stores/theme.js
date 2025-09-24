import { defineStore } from 'pinia';
import { ref, watch } from 'vue';

export const useThemeStore = defineStore('theme', () => {
  const darkMode = ref(false);

  // تهيئة الوضع عند تحميل المخزن
  const init = () => {
    const saved = localStorage.getItem('darkMode');
    // إذا كان الوضع محفوظًا، قم بتعيين القيمة
    if (saved !== null) {
      darkMode.value = saved === 'true';
    } else {
      // إذا لم يتم حفظ أي شيء، استخدم إعدادات النظام المفضلة
      darkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    applyTheme();
  };

  // تطبيق الوضع على عنصر <html>
  const applyTheme = () => {
    if (darkMode.value) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  };

  // حفظ الوضع في localStorage وتطبيقه عند تغييره
  watch(darkMode, (newVal) => {
    localStorage.setItem('darkMode', newVal ? 'true' : 'false');
    applyTheme();
  }, { immediate: true }); // immediate: true يضمن تشغيل watch عند التحميل الأولي

  return { darkMode, applyTheme, init };
});

<template>
  <section id="contact" class="min-h-screen flex items-center justify-center py-20 bg-gray-50 dark:bg-gray-800 font-sans transition-colors duration-500" dir="rtl">
    <div class="container mx-auto px-6 max-w-4xl text-center">
      <h2 class="text-4xl md:text-5xl font-extrabold text-blue-600 dark:text-blue-400 mb-4 animate-on-scroll transition-colors duration-500">
        تواصل معنا
      </h2>
      <p class="text-xl text-gray-700 dark:text-gray-300 max-w-2xl mx-auto mb-12 animate-on-scroll transition-colors duration-500">
        يسعدنا تلقي استفساراتكم وملاحظاتكم. يمكنكم التواصل معنا عبر الطرق التالية:
      </p>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="flex flex-col items-center p-8 bg-white dark:bg-gray-700 rounded-3xl shadow-xl transform transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl animate-on-scroll">
          <svg class="w-16 h-16 text-blue-600 dark:text-blue-400 mb-4 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-2-3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5z"></path>
          </svg>
          <h3 class="font-bold text-xl md:text-2xl mb-2 text-gray-800 dark:text-gray-100 transition-colors duration-500">البريد الإلكتروني</h3>
          <a href="mailto:info@alkhair.org" class="text-lg text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">info@alkhair.org</a>
        </div>

        <div class="flex flex-col items-center p-8 bg-white dark:bg-gray-700 rounded-3xl shadow-xl transform transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl animate-on-scroll">
          <svg class="w-16 h-16 text-blue-600 dark:text-blue-400 mb-4 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.25a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11a2 2 0 012 2v7a2 2 0 01-2 2h-3.25a2 2 0 01-2-2v-7a2 2 0 012-2h3.25z"></path>
          </svg>
          <h3 class="font-bold text-xl md:text-2xl mb-2 text-gray-800 dark:text-gray-100 transition-colors duration-500">رقم الهاتف</h3>
          <a href="tel:+966501234567" class="text-lg text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-300">+967 777777777  </a>
        </div>

        <div class="flex flex-col items-center p-8 bg-white dark:bg-gray-700 rounded-3xl shadow-xl transform transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl animate-on-scroll">
          <svg class="w-16 h-16 text-blue-600 dark:text-blue-400 mb-4 transition-colors duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          <h3 class="font-bold text-xl md:text-2xl mb-2 text-gray-800 dark:text-gray-100 transition-colors duration-500">العنوان</h3>
          <p class="text-lg text-gray-600 dark:text-gray-400 transition-colors duration-500">شارع الخير، التحرير الأسفل، الجمهورية اليمنية</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue';

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in-up');
      }
    });
  }, { threshold: 0.2 });

  document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
});
</script>

<style scoped>
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}
.fade-in-up { animation: fadeInUp 1s ease forwards; }
.animate-on-scroll { opacity: 0; transform: translateY(30px); transition: all 0.8s ease; }
.animate-on-scroll.fade-in-up { opacity: 1; transform: translateY(0); }
</style>

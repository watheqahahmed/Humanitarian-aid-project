<template>
  <section id="projects" class="py-20 bg-gray-100 dark:bg-gray-800 transition-colors duration-500" dir="rtl">
    <div class="container mx-auto px-6 text-center">
      <h2 class="text-4xl md:text-5xl font-extrabold text-blue-600 dark:text-blue-400 mb-4 animate-on-scroll transition-colors duration-500">
        مشاريعنا
      </h2>
      <p class="text-xl text-gray-700 dark:text-gray-300 max-w-3xl mx-auto mb-12 animate-on-scroll transition-colors duration-500">
        نعمل على العديد من المشاريع لخدمة مجتمعنا. كل مشروع مصمم لإحداث فرق حقيقي ومستدام.
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="project-card bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 transform transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl animate-on-scroll">
          <div class="flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 mb-4 mx-auto transition-colors duration-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354c-1.182-1.144-2.738-1.956-4.498-1.956C5.035 2.398 3 4.433 3 7.02c0 2.217 2.122 4.254 5.378 7.006 1.135 1.018 2.31 1.933 3.535 2.766.113.08.232.16.353.238a.684.684 0 00.373.11c.142 0 .285-.03.427-.087a.684.684 0 00.373-.11c.121-.078.24-.158.353-.238 1.225-.833 2.4-1.748 3.535-2.766C18.878 11.274 21 9.237 21 7.02c0-2.587-2.035-4.622-4.502-4.622-1.76 0-3.316.812-4.498 1.956z"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2 transition-colors duration-500">حملة إغاثة الغذاء</h3>
          <p class="text-gray-600 dark:text-gray-300 transition-colors duration-500">
            تقديم وجبات غذائية يومية للأسر الفقيرة والنازحة في المناطق المتضررة.
          </p>
        </div>

        <div class="project-card bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 transform transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl animate-on-scroll">
          <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 mb-4 mx-auto transition-colors duration-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.103a4 4 0 000-5.656l-1.1-1.101m3.116 12.836a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.102 1.101"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2 transition-colors duration-500">كفالة الأيتام</h3>
          <p class="text-gray-600 dark:text-gray-300 transition-colors duration-500">
            توفير الدعم المالي والتعليمي للأطفال الأيتام لضمان مستقبل مشرق لهم.
          </p>
        </div>

        <div class="project-card bg-white dark:bg-gray-900 rounded-xl shadow-lg p-6 transform transition-all duration-500 hover:scale-105 hover:-translate-y-2 hover:shadow-2xl animate-on-scroll">
          <div class="flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400 mb-4 mx-auto transition-colors duration-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.835 5.564 9.475 5 8 5a4 4 0 000 8h4m-4 0a4 4 0 010-8h4m-4 8v12m8-12a4 4 0 000-8h4a4 4 0 010 8h-4m-4 0v12m0-12h4m-4 0a4 4 0 000-8h4m4 8v12"></path>
            </svg>
          </div>
          <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-2 transition-colors duration-500">مشاريع المياه</h3>
          <p class="text-gray-600 dark:text-gray-300 transition-colors duration-500">
            حفر آبار وتوفير مصادر مياه شرب نظيفة في المناطق التي تعاني من شح المياه.
          </p>
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

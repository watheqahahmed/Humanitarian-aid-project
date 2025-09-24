<template>
  <section id="about" class="relative py-20 bg-gray-50 dark:bg-gray-900 overflow-hidden transition-colors duration-500" dir="rtl">
    <div class="container mx-auto px-6 flex flex-col md:flex-row-reverse items-center justify-between gap-12">

      <div class="w-full md:w-1/2 flex justify-center animate-on-scroll">
        <div class="relative w-full max-w-lg">
          <img
            :src="aboutImage"
            alt="صورة توضيحية للعمل الخيري"
            class="w-full h-auto rounded-3xl shadow-2xl transform hover:scale-105 transition-transform duration-500 ease-in-out"
          />
          <div class="absolute inset-0 rounded-3xl bg-gradient-to-br from-blue-500 to-cyan-400 dark:from-blue-700 dark:to-cyan-600 opacity-20 pointer-events-none transition-colors duration-500"></div>
        </div>
      </div>

      <div class="w-full md:w-1/2 text-center md:text-right animate-on-scroll">
        <h2 class="text-4xl md:text-5xl font-extrabold text-blue-600 dark:text-blue-400 mb-4 transition-colors duration-500">
          قصة عطاء لا تنتهي
        </h2>
        <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 leading-relaxed mb-6 transition-colors duration-500">
          "تطبيق الخير" ليس مجرد منصة، بل هو مجتمع من المتبرعين والمتطوعين الذين يوحدون جهودهم لإحداث فرق حقيقي. نؤمن بأن كل عمل خير، مهما كان صغيرًا، يترك أثرًا كبيرًا في حياة المحتاجين.
        </p>
        <p class="text-lg md:text-xl text-gray-700 dark:text-gray-300 leading-relaxed mb-6 transition-colors duration-500">
          من خلال شفافية كاملة ومشاريع محددة، نضمن أن تبرعاتك تصل إلى مستحقيها، وأن كل قرش يساهم في بناء مستقبل أفضل. انضم إلينا اليوم وكن جزءًا من هذه الرحلة الإنسانية.
        </p>
        <a href="#projects" class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-full shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          اكتشف مشاريعنا
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
import aboutImage from '@/assets/images/about.jpg'

// Scroll animations
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

<style>
/* Animations */
@keyframes fadeInUp { 0% { opacity:0; transform:translateY(30px); } 100% { opacity:1; transform:translateY(0); } }
.fade-in-up { animation: fadeInUp 1s ease forwards; }
.animate-on-scroll { opacity: 0; transform: translateY(30px); transition: all 0.8s ease; }
.animate-on-scroll.fade-in-up { opacity: 1; transform: translateY(0); }
</style>

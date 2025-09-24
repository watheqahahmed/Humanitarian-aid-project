<template>
  <div class="landing-page font-sans text-gray-900 dark:text-gray-100 dark:bg-gray-900 transition-colors duration-300" dir="rtl">
    <nav class="bg-white dark:bg-gray-800 shadow-md fixed w-full z-50 transition-colors duration-300">
      <div class="container mx-auto px-6 flex justify-between items-center py-4">
        <a href="#" class="text-2xl font-extrabold text-blue-600 tracking-wider dark:text-blue-400 transition-colors duration-300">تطبيق الخير</a>

        <div class="hidden md:flex items-center space-x-6 space-x-reverse">
          <a href="#" class="text-base text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium transition duration-300">الرئيسية</a>
          <a href="#about" class="text-base text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium transition duration-300">من نحن</a>
          <a href="#projects" class="text-base text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium transition duration-300">مشاريعنا</a>
          <a href="#donate" class="text-base text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-800 transition duration-300">تبرع الآن</a>
          <a href="#contact" class="text-base text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium transition duration-300">اتصل بنا</a>

          <router-link to="/login"
            class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300 shadow-md">
            تسجيل الدخول
          </router-link>
          <router-link to="/register"
            class="px-4 py-2 text-sm border border-blue-600 text-blue-600 rounded-lg font-semibold hover:bg-blue-50 dark:hover:bg-gray-700 dark:text-blue-400 transition-all duration-300">
            التسجيل
          </router-link>

          <button @click="toggleDarkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-300">
            <svg v-if="isDarkMode" class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.106a.75.75 0 00-1.06-1.06l-1.59 1.59a.75.75 0 101.06 1.06l1.59-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5h2.25a.75.75 0 01.75.75zM16.155 18.894a.75.75 0 001.06 1.06l1.59-1.59a.75.75 0 10-1.06-1.06l-1.59 1.59zM12 18.75a.75.75 0 01-.75.75v2.25a.75.75 0 011.5 0v-2.25a.75.75 0 01-.75-.75zM6.106 17.69a.75.75 0 001.06 1.06l1.59-1.59a.75.75 0 00-1.06-1.06l-1.59 1.59zM3 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H3a.75.75 0 01-.75-.75zM4.515 5.864a.75.75 0 001.06-1.06l-1.59-1.59a.75.75 0 10-1.06 1.06l1.59 1.59z"></path>
            </svg>
            <svg v-else class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9.548 1.488a.75.75 0 01.455.321l.666 1.11a.75.75 0 00.175.253l1.328 1.328a.75.75 0 01.127.568l-.299 1.498a.75.75 0 00.999.999l1.498-.299a.75.75 0 01.568.127l1.328 1.328a.75.75 0 00.253.175l1.11.666a.75.75 0 01.321.455v2.5a.75.75 0 01-.75.75h-2.25a.75.75 0 01-.75-.75V11a5 5 0 00-5-5H6.5a.75.75 0 01-.75-.75V3a.75.75 0 01.75-.75h2.5a.75.75 0 01.455.321zM9.5 7.5a4.5 4.5 0 00-4.5 4.5v1.5a.75.75 0 01-1.5 0v-1.5a6 6 0 016-6h1.5a.75.75 0 010 1.5H9.5z"></path>
            </svg>
          </button>
        </div>

        <div class="md:hidden flex items-center gap-2">
          <button @click="toggleDarkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-300">
            <svg v-if="isDarkMode" class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.106a.75.75 0 00-1.06-1.06l-1.59 1.59a.75.75 0 101.06 1.06l1.59-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5h2.25a.75.75 0 01.75.75zM16.155 18.894a.75.75 0 001.06 1.06l1.59-1.59a.75.75 0 10-1.06-1.06l-1.59 1.59zM12 18.75a.75.75 0 01-.75.75v2.25a.75.75 0 011.5 0v-2.25a.75.75 0 01-.75-.75zM6.106 17.69a.75.75 0 001.06 1.06l1.59-1.59a.75.75 0 00-1.06-1.06l-1.59 1.59zM3 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H3a.75.75 0 01-.75-.75zM4.515 5.864a.75.75 0 001.06-1.06l-1.59-1.59a.75.75 0 10-1.06 1.06l1.59 1.59z"></path>
            </svg>
            <svg v-else class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9.548 1.488a.75.75 0 01.455.321l.666 1.11a.75.75 0 00.175.253l1.328 1.328a.75.75 0 01.127.568l-.299 1.498a.75.75 0 00.999.999l1.498-.299a.75.75 0 01.568.127l1.328 1.328a.75.75 0 00.253.175l1.11.666a.75.75 0 01.321.455v2.5a.75.75 0 01-.75.75h-2.25a.75.75 0 01-.75-.75V11a5 5 0 00-5-5H6.5a.75.75 0 01-.75-.75V3a.75.75 0 01.75-.75h2.5a.75.75 0 01.455.321zM9.5 7.5a4.5 4.5 0 00-4.5 4.5v1.5a.75.75 0 01-1.5 0v-1.5a6 6 0 016-6h1.5a.75.75 0 010 1.5H9.5z"></path>
            </svg>
          </button>

          <button @click="mobileMenuOpen = !mobileMenuOpen" class="focus:outline-none">
            <svg v-if="!mobileMenuOpen" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg v-else class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
      </div>

      <transition name="fade">
        <div v-if="mobileMenuOpen" class="md:hidden bg-white dark:bg-gray-800 shadow-lg transition-colors duration-300">
          <div class="flex flex-col px-6 py-4 space-y-3">
            <a href="#" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium">الرئيسية</a>
            <a href="#about" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium">من نحن</a>
            <a href="#projects" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium">مشاريعنا</a>
            <a href="#donate" class="text-blue-600 dark:text-blue-400 font-bold hover:text-blue-800">تبرع الآن</a>
            <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 font-medium">اتصل بنا</a>
            <router-link to="/login" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all duration-300 text-center">تسجيل الدخول</router-link>
            <router-link to="/register" class="px-4 py-2 text-sm border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 rounded-lg font-semibold hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 text-center">التسجيل</router-link>
          </div>
        </div>
      </transition>
    </nav>

    <div class="pt-24">
      <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-cyan-400 dark:from-blue-900 dark:to-cyan-700 transition-colors duration-500" style="clip-path: polygon(0 0, 100% 0, 100% 85%, 0% 100%); opacity:0.8;"></div>

        <div class="absolute w-72 h-72 bg-yellow-300 rounded-full opacity-30 animate-float-slow top-20 left-10"></div>
        <div class="absolute w-48 h-48 bg-pink-400 rounded-full opacity-40 animate-float-fast bottom-10 right-20"></div>
        <div class="absolute w-60 h-60 bg-green-300 rounded-full opacity-25 animate-float-slower top-1/3 right-1/4"></div>

        <div class="relative z-10 text-center px-6 max-w-3xl animate-fade-in-up">
          <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
            نحن هنا لنصنع <span class="text-yellow-300 dark:text-yellow-400 transition-colors duration-500">فرقًا</span>
          </h1>
          <p class="text-lg md:text-2xl text-white font-light mb-8">
            بصفتنا منظمة خيرية، نعمل معًا لتحقيق التغيير الإيجابي في حياة المحتاجين حول العالم.
          </p>
          <a href="#donate" class="inline-block px-10 py-4 bg-gradient-to-r from-yellow-400 to-yellow-300 dark:from-yellow-600 dark:to-yellow-500 text-blue-900 font-bold rounded-full text-lg shadow-xl transform transition-transform hover:scale-110 animate-bounce-slow">
            تبرع الآن وكن جزءًا من التغيير
          </a>
        </div>

        <img src="@/assets/images/about.jpg" alt="Background" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay" />
      </section>

      <AboutSection class="animate-on-scroll"/>
      <ProjectsSection class="animate-on-scroll"/>
      <DonationSection class="animate-on-scroll"/>
      <ContactSection class="animate-on-scroll"/>
    </div>

    <footer class="bg-gray-900 dark:bg-gray-900 text-white dark:text-gray-200 py-12 transition-colors duration-300">
      <div class="container mx-auto px-6 text-center">
        <h3 class="text-2xl font-extrabold text-blue-400 tracking-wider mb-4">تطبيق الخير</h3>
        <p class="max-w-xl mx-auto text-sm text-gray-400 dark:text-gray-500 leading-relaxed transition-colors duration-300">
          نحن منظمة خيرية مكرسة لإحداث تغيير إيجابي في المجتمعات المحتاجة.
          مساهماتكم تساعدنا على تحقيق رسالتنا.
        </p>

        <div class="flex justify-center flex-wrap gap-6 mt-8 mb-6 text-sm">
          <a href="#about" class="text-gray-300 dark:text-gray-400 hover:text-blue-400 transition-colors duration-300">من نحن</a>
          <a href="#projects" class="text-gray-300 dark:text-gray-400 hover:text-blue-400 transition-colors duration-300">مشاريعنا</a>
          <a href="#donate" class="text-gray-300 dark:text-gray-400 hover:text-blue-400 transition-colors duration-300">تبرع الآن</a>
          <a href="#contact" class="text-gray-300 dark:text-gray-400 hover:text-blue-400 transition-colors duration-300">اتصل بنا</a>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-700 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400 transition-colors duration-300">
          <p>جميع الحقوق محفوظة © 2024 تطبيق الخير</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AboutSection from '../../components/sections/AboutSection.vue';
import ProjectsSection from '../../components/sections/ProjectsSection.vue';
import DonationSection from '../../components/sections/DonationSection.vue';
import ContactSection from '../../components/sections/ContactSection.vue';
import aboutImage from '@/assets/images/about.jpg';

const mobileMenuOpen = ref(false);
const isDarkMode = ref(false);

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

// Check for user's system preference on component mount
onMounted(() => {
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  isDarkMode.value = prefersDark;
  if (prefersDark) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }

  // Scroll animations
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
.animate-fade-in-up, .fade-in-up { animation: fadeInUp 1s ease forwards; }

@keyframes floatSlow { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-20px); } }
@keyframes floatFast { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-30px); } }
@keyframes floatSlower { 0%,100% { transform:translateY(0); } 50% { transform:translateY(-10px); } }
@keyframes bounceSlow { 0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)} }

.animate-float-slow { animation: floatSlow 8s ease-in-out infinite; }
.animate-float-fast { animation: floatFast 6s ease-in-out infinite; }
.animate-float-slower { animation: floatSlower 10s ease-in-out infinite; }
.animate-bounce-slow { animation: bounceSlow 3s ease-in-out infinite; }

/* Mobile menu fade */
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>

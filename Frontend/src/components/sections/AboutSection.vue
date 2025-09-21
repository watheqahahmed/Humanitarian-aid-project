<template>
  <section
    id="about"
    class="relative min-h-screen flex items-center justify-center bg-gray-900 text-white"
  >
    <!-- خلفية الصورة -->
    <div
      class="absolute inset-0"
      :style="`background-image: url(${aboutImage}); background-size: cover; background-position: center;`"
    ></div>

    <!-- طبقة شفافة -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <!-- المحتوى -->
    <div class="relative z-10 text-center px-6 max-w-3xl">
      <h2 class="text-4xl md:text-6xl font-extrabold mb-6">
        من نحن
      </h2>
      <p class="text-lg md:text-xl leading-relaxed mb-6">
        جمعية الخير هي منظمة غير ربحية مكرسة لمساعدة المحتاجين.
        نعمل على تقديم الدعم الغذائي، الملابس، والمساعدات الطبية،
        لضمان حياة كريمة للجميع.
      </p>
      <a
        href="#projects"
        class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg shadow-md transition duration-300"
      >
        اكتشف مشاريعنا
      </a>
    </div>
  </section>
</template>

<script setup>
import aboutImage from '@/assets/images/about.jpg'
</script>

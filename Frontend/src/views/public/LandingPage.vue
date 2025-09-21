<template>
  <div class="landing-page">
    <nav class="bg-white shadow-md p-4 fixed w-full z-50">
      <div class="container mx-auto flex justify-between items-center">
        <a href="#" class="text-2xl font-bold text-gray-800">Charity App</a>
        <div class="flex items-center">
          <a href="#about" class="mx-4 text-gray-600 hover:text-blue-500">About Us</a>
          <a href="#projects" class="mx-4 text-gray-600 hover:text-blue-500">Projects</a>
          <a href="#donate" class="mx-4 text-gray-600 hover:text-blue-500 font-bold">Donate Now</a>
          <a href="#contact" class="mx-4 text-gray-600 hover:text-blue-500">Contact Us</a>

          <!-- أزرار المصادقة -->
          <router-link
            to="/login"
            class="ml-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
          >
            Login
          </router-link>
          <router-link
            to="/register"
            class="ml-2 px-4 py-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-50"
          >
            Register
          </router-link>
        </div>
      </div>
    </nav>

    <AboutSection class="pt-20" />
    <ProjectsSection class="pt-20" />
    <DonationSection class="pt-20" />
    <ContactSection class="pt-20" />
  </div>
</template>

<script setup>
import AboutSection from '../../components/sections/AboutSection.vue';
import ProjectsSection from '../../components/sections/ProjectsSection.vue';
import DonationSection from '../../components/sections/DonationSection.vue';
import ContactSection from '../../components/sections/ContactSection.vue';
</script>

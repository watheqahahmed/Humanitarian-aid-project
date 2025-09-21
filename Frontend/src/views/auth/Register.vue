<!-- src/views/auth/Register.vue -->
<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-md w-full bg-white shadow-lg rounded-2xl p-8">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Create an Account
      </h2>

      <form @submit.prevent="handleRegister" class="space-y-5">
        <!-- Full Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Full Name
          </label>
          <input
            v-model="form.name"
            type="text"
            placeholder="Enter your full name"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 bg-white border-gray-300 text-gray-800"
            required
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Email
          </label>
          <input
            v-model="form.email"
            type="email"
            placeholder="Enter your email"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 bg-white border-gray-300 text-gray-800"
            required
          />
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Password
          </label>
          <input
            v-model="form.password"
            type="password"
            placeholder="Enter your password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 bg-white border-gray-300 text-gray-800"
            required
          />
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Confirm Password
          </label>
          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm your password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 bg-white border-gray-300 text-gray-800"
            required
          />
        </div>

        <!-- Role Selection -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Role
          </label>
          <select
            v-model="form.role"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500 bg-white border-gray-300 text-gray-800"
          >
            <option value="admin">Admin</option>
            <option value="beneficiary">Beneficiary</option>
            <option value="volunteer">Volunteer</option>
          </select>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full py-2 px-4 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg shadow-md"
        >
          Register
        </button>
      </form>

      <!-- Link to Login -->
      <p class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <router-link to="/login" class="text-teal-600 hover:underline">
          Login here
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'admin',
});

const handleRegister = async () => {
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/register', form.value);
    console.log('Registration successful:', response.data);

    // حفظ التوكن مؤقتًا في localStorage
    localStorage.setItem('authToken', response.data.token);

    // إعادة التوجيه حسب الدور
    const role = response.data.user.role;
    if (role === 'admin') router.push('/admin/dashboard');
    else if (role === 'volunteer') router.push('/volunteer/dashboard');
    else router.push('/beneficiary/dashboard');
  } catch (error) {
    console.error('Registration failed:', error.response?.data || error.message);
    alert(error.response?.data?.message || 'Registration failed');
  }
};
</script>

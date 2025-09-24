// src/stores/auth.js
import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null,
    user: null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    setToken(token) {
      this.token = token;
    },
    setUser(user) {
      this.user = user;
    },
    clearAuth() {
      this.token = null;
      this.user = null;
    },
    logout() {
      // مسح بيانات المستخدم
      this.clearAuth();

      // إعادة التوجيه للصفحة الرئيسية الخارجية
      window.location.href = 'http://localhost:5173/';
    },
  },
});

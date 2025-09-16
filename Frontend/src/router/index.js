import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
  // مسار الصفحة الرئيسية العامة
  {
    path: '/',
    name: 'home',
    component: () => import('../views/public/LandingPage.vue'),
    meta: { requiresAuth: false }
  },
  // مسار تسجيل الدخول
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/auth/Login.vue'),
    meta: { requiresAuth: false }
  },

  // مسارات المسؤول (Admin)
  {
    path: '/admin/dashboard',
    name: 'admin.dashboard',
    component: () => import('../views/admin/AdminDashboard.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/admin/donations',
    name: 'admin.donations',
    component: () => import('../views/admin/Donations.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/admin/aid-requests',
    name: 'admin.aid-requests',
    component: () => import('../views/admin/AidRequests.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },
  {
    path: '/admin/distributions',
    name: 'admin.distributions',
    component: () => import('../views/admin/Distributions.vue'),
    meta: { requiresAuth: true, role: 'admin' }
  },

  // مسارات المتطوع (Volunteer)
  {
    path: '/volunteer/dashboard',
    name: 'volunteer.dashboard',
    component: () => import('../views/volunteer/VolunteerDashboard.vue'),
    meta: { requiresAuth: true, role: 'volunteer' }
  },
  {
    path: '/volunteer/assigned-deliveries',
    name: 'volunteer.assigned-deliveries',
    component: () => import('../views/volunteer/AssignedDeliveries.vue'),
    meta: { requiresAuth: true, role: 'volunteer' }
  },

  // مسارات المستفيد (Beneficiary)
  {
    path: '/beneficiary/dashboard',
    name: 'beneficiary.dashboard',
    component: () => import('../views/beneficiary/BeneficiaryDashboard.vue'),
    meta: { requiresAuth: true, role: 'beneficiary' }
  },
  {
    path: '/beneficiary/aid-requests',
    name: 'beneficiary.aid-requests',
    component: () => import('../views/beneficiary/AidRequests.vue'),
    meta: { requiresAuth: true, role: 'beneficiary' }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.isAuthenticated;
  const requiredAuth = to.meta.requiresAuth;
  const requiredRole = to.meta.role;

  if (requiredAuth && !isAuthenticated) {
    next({ name: 'login' });
  } else if (isAuthenticated && to.name === 'login') {
    next({ name: authStore.user.role + '.dashboard' });
  } else if (isAuthenticated && requiredRole && authStore.user.role !== requiredRole) {
    next({ name: 'home' });
  } else {
    next();
  }
});

export default router;

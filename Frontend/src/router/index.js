import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import DashboardLayout from '../components/common/DashboardLayout.vue';

// الصفحات العامة
const LandingPage = () => import('../views/public/LandingPage.vue');
const LoginPage = () => import('../views/auth/Login.vue');

// لوحة المسؤول
const AdminDashboard = () => import('../views/admin/AdminDashboard.vue');
const Donations = () => import('../views/admin/Donations.vue');
const AidRequests = () => import('../views/admin/AidRequests.vue');
const Distributions = () => import('../views/admin/Distributions.vue');
const Users = () => import('../views/admin/Users.vue');

// لوحة المتطوع
const VolunteerDashboard = () => import('../views/volunteer/VolunteerDashboard.vue');
const AssignedDeliveries = () => import('../views/volunteer/AssignedDeliveries.vue');

// لوحة المستفيد
const BeneficiaryDashboard = () => import('../views/beneficiary/BeneficiaryDashboard.vue');
const BeneficiaryAidRequests = () => import('../views/beneficiary/AidRequests.vue');

// صفحة 404
const NotFound = () => import('../views/public/NotFound.vue');

const routes = [
  { path: '/', name: 'home', component: LandingPage, meta: { requiresAuth: false } },
  { path: '/login', name: 'login', component: LoginPage, meta: { requiresAuth: false } },

  // لوحة المسؤول
  {
    path: '/admin',
    component: DashboardLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      { path: 'dashboard', name: 'admin.dashboard', component: AdminDashboard },
      { path: 'donations', name: 'admin.donations', component: Donations },
      { path: 'aid-requests', name: 'admin.aid-requests', component: AidRequests },
      { path: 'distributions', name: 'admin.distributions', component: Distributions },
      { path: 'users', name: 'admin.users', component: Users }
    ]
  },

  // لوحة المتطوع
  {
    path: '/volunteer',
    component: DashboardLayout,
    meta: { requiresAuth: true, role: 'volunteer' },
    children: [
      { path: 'dashboard', name: 'volunteer.dashboard', component: VolunteerDashboard },
      { path: 'assigned-deliveries', name: 'volunteer.assigned-deliveries', component: AssignedDeliveries }
    ]
  },

  // لوحة المستفيد
  {
    path: '/beneficiary',
    component: DashboardLayout,
    meta: { requiresAuth: true, role: 'beneficiary' },
    children: [
      { path: 'dashboard', name: 'beneficiary.dashboard', component: BeneficiaryDashboard },
      { path: 'aid-requests', name: 'beneficiary.aid-requests', component: BeneficiaryAidRequests }
    ]
  },

  // صفحة 404 لأي رابط غير موجود
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
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
    // إعادة التوجيه للوحة المستخدم حسب الدور مع حماية من عدم وجود الدور
    next({ name: authStore.user?.role ? authStore.user.role + '.dashboard' : 'home' });
  } else if (isAuthenticated && requiredRole && authStore.user?.role !== requiredRole) {
    next({ name: 'home' });
  } else {
    next();
  }
});

export default router;

import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "@/views/Dashboard.vue";
import Users from "@/views/admin/Users.vue";
import Donations from "@/views/admin/Donations.vue";
import Distributions from "@/views/admin/Distributions.vue";
import AidRequestsAdmin from "@/views/admin/AidRequests.vue";
import AssignedDeliveries from "@/views/volunteer/AssignedDeliveries.vue";
import AidRequestsBeneficiary from "@/views/beneficiary/AidRequests.vue";
import Login from "@/views/auth/Login.vue";
import Register from "@/views/auth/Register.vue";

// Simulated Auth (replace with Vuex/Pinia store or API)
let currentUser = { role: null, name: null };

export const setUser = (user) => {
  currentUser = user;
};

const routes = [
  { path: "/login", component: Login, meta: { guest: true } },
  { path: "/register", component: Register, meta: { guest: true } },
  {
    path: "/dashboard",
    component: Dashboard,
    meta: { requiresAuth: true },
    children: [
      { path: "", component: Dashboard, meta: { title: "Dashboard" } },

      // Admin Routes
      { path: "users", component: Users, meta: { requiresAuth: true, roles: ["admin"], title: "Users" } },
      { path: "donations", component: Donations, meta: { requiresAuth: true, roles: ["admin"], title: "Donations" } },
      { path: "distributions", component: Distributions, meta: { requiresAuth: true, roles: ["admin"], title: "Distributions" } },
      { path: "aid-requests", component: AidRequestsAdmin, meta: { requiresAuth: true, roles: ["admin"], title: "Aid Requests" } },

      // Volunteer Routes
      { path: "assigned-deliveries", component: AssignedDeliveries, meta: { requiresAuth: true, roles: ["volunteer"], title: "Assigned Deliveries" } },

      // Beneficiary Routes
      { path: "beneficiary-aid-requests", component: AidRequestsBeneficiary, meta: { requiresAuth: true, roles: ["beneficiary"], title: "Aid Requests" } },
    ],
  },
  { path: "/:pathMatch(.*)*", redirect: "/login" },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Route Guards
router.beforeEach((to, from, next) => {
  if (to.meta.guest) {
    return next();
  }

  if (to.meta.requiresAuth) {
    if (!currentUser.role) return next("/login");
    if (to.meta.roles && !to.meta.roles.includes(currentUser.role)) {
      return next("/dashboard"); // redirect if role not authorized
    }
  }

  next();
});

export default router;

// src/main.js
import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import VueApexCharts from "vue3-apexcharts";

// Font Awesome Imports
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// استورد الأيقونات التي تستخدمها هنا
import {
  faChartLine,
  faHandHoldingUsd,
  faHandsHelping,
  faHeartbeat,
  faUsersCog,
  faBars,
  faTimes,
  faSun,
  faMoon,
  faBell,
  faChevronDown,
  faUserCircle,
  faCog,
  faSignOutAlt,
  faCheckCircle,
  faUsers,
  faHourglassHalf,
  faPlusCircle,
  faUserCheck,
  faTasks,
  faUpload,
  faArrowRight
} from '@fortawesome/free-solid-svg-icons'

// أضف الأيقونات إلى المكتبة
library.add(
  faChartLine,
  faHandHoldingUsd,
  faHandsHelping,
  faHeartbeat,
  faUsersCog,
  faBars,
  faTimes,
  faSun,
  faMoon,
  faBell,
  faChevronDown,
  faUserCircle,
  faCog,
  faSignOutAlt,
  faCheckCircle,
  faUsers,
  faHourglassHalf,
  faPlusCircle,
  faUserCheck,
  faTasks,
  faUpload,
  faArrowRight
)

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(VueApexCharts);
app.component('font-awesome-icon', FontAwesomeIcon) // تسجيل مكون Font Awesome

app.mount('#app')

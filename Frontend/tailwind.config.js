// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // Make sure dark mode is enabled for class strategy
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}", // هذا السطر مهم جداً
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

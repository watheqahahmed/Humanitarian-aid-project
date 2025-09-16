import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000/api/v1", // رابط API الخاص بك
  withCredentials: true, // إذا كنت تستخدم Laravel Sanctum
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default api;

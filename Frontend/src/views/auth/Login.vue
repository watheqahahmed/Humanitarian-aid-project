<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const form = ref({ email: "", password: "" });
const errorMessage = ref("");

const handleLogin = () => {
  if (!form.value.email || !form.value.password) {
    errorMessage.value = "Please enter your email and password.";
    return;
  }

  let role = null;

  if (form.value.email === "admin@test.com" && form.value.password === "123456") {
    role = "admin";
    router.push("/admin/dashboard");
  } else if (form.value.email === "volunteer@test.com" && form.value.password === "123456") {
    role = "volunteer";
    router.push("/volunteer");
  } else if (form.value.email === "beneficiary@test.com" && form.value.password === "123456") {
    role = "beneficiary";
    router.push("/beneficiary");
  } else {
    errorMessage.value = "Invalid credentials. Try again.";
    return;
  }

  // تخزين الدور في localStorage
  localStorage.setItem("userRole", role);
};
</script>

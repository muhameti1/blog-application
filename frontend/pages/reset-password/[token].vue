<template>
  <div
    class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center px-4"
  >
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h1>
        <p class="text-gray-600">Enter your new password below.</p>
      </div>

      <!-- Success Message -->
      <div
        v-if="success"
        class="mb-6 bg-green-50 border border-green-200 rounded-md p-4"
      >
        <p class="text-sm text-green-800">
          ✓ Password has been reset successfully. Redirecting to login...
        </p>
      </div>

      <!-- Error Message -->
      <div
        v-if="error"
        class="mb-6 bg-red-50 border border-red-200 rounded-md p-4"
      >
        <p class="text-sm text-red-600">{{ error }}</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <div>
          <label
            for="email"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            Email Address
          </label>
          <input
            id="email"
            v-model="formData.email"
            type="email"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="your.email@example.com"
          />
        </div>

        <div>
          <label
            for="password"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            New Password
          </label>
          <input
            id="password"
            v-model="formData.password"
            type="password"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Enter new password"
            minlength="8"
          />
        </div>

        <div>
          <label
            for="password_confirmation"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            v-model="formData.password_confirmation"
            type="password"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            placeholder="Confirm new password"
            minlength="8"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          {{ loading ? "Resetting..." : "Reset Password" }}
        </button>
      </form>

      <div class="mt-6 text-center">
        <NuxtLink
          to="/login"
          class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"
        >
          ← Back to Login
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: "guest",
});

const { resetPassword } = useAuth();
const route = useRoute();
const router = useRouter();

const token = route.params.token as string;

const formData = ref({
  token: token,
  email: (route.query.email as string) || "",
  password: "",
  password_confirmation: "",
});

const loading = ref(false);
const error = ref("");
const success = ref(false);

const handleSubmit = async () => {
  error.value = "";
  success.value = false;

  // Validate passwords match
  if (formData.value.password !== formData.value.password_confirmation) {
    error.value = "Passwords do not match.";
    return;
  }

  loading.value = true;

  try {
    await resetPassword(formData.value);
    success.value = true;

    // Redirect to login after 2 seconds
    setTimeout(() => {
      router.push("/login");
    }, 2000);
  } catch (err: any) {
    error.value =
      err.data?.message || "Failed to reset password. Please try again.";
  } finally {
    loading.value = false;
  }
};
</script>

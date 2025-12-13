<template>
  <div class="bg-indigo-600 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-white mb-4">
          Subscribe to our Newsletter
        </h2>
        <p class="text-indigo-100 mb-8 max-w-2xl mx-auto">
          Get notified when new blog posts are published. No spam, unsubscribe
          anytime.
        </p>

        <form @submit.prevent="handleSubscribe" class="max-w-md mx-auto">
          <div class="flex gap-2">
            <input
              v-model="email"
              type="email"
              placeholder="Enter your email"
              required
              class="flex-1 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300"
              :disabled="loading"
            />
            <button
              type="submit"
              :disabled="loading"
              class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-medium hover:bg-indigo-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ loading ? "Subscribing..." : "Subscribe" }}
            </button>
          </div>

          <!-- Success Message -->
          <div
            v-if="successMessage"
            class="mt-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm"
          >
            {{ successMessage }}
          </div>

          <!-- Error Message -->
          <div
            v-if="errorMessage"
            class="mt-4 p-3 bg-red-100 text-red-800 rounded-lg text-sm"
          >
            {{ errorMessage }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { subscribe } = useNewsletter();

const email = ref("");
const loading = ref(false);
const successMessage = ref("");
const errorMessage = ref("");

const handleSubscribe = async () => {
  if (!email.value) return;

  loading.value = true;
  successMessage.value = "";
  errorMessage.value = "";

  const result = await subscribe(email.value);

  loading.value = false;

  if (result.success) {
    successMessage.value =
      "✓ Successfully subscribed! Check your email for confirmation.";
    email.value = "";

    // Clear success message after 5 seconds
    setTimeout(() => {
      successMessage.value = "";
    }, 5000);
  } else {
    errorMessage.value = result.error;

    // Clear error message after 5 seconds
    setTimeout(() => {
      errorMessage.value = "";
    }, 5000);
  }
};
</script>

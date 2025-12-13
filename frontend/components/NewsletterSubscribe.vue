<template>
  <div class="bg-white border border-gray-200 rounded-xl p-8">
    <div class="max-w-2xl mx-auto text-center">
      <div
        class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4"
      >
        <svg
          class="w-8 h-8 text-primary-600"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
          />
        </svg>
      </div>
      <h2 class="text-2xl font-bold text-gray-900 mb-3">
        Subscribe to Newsletter
      </h2>
      <p class="text-gray-600 mb-6">
        Get notified when new blog posts are published. No spam, unsubscribe
        anytime.
      </p>

      <form @submit.prevent="handleSubscribe" class="max-w-md mx-auto">
        <div class="flex flex-col sm:flex-row gap-3">
          <input
            v-model="email"
            type="email"
            placeholder="Enter your email address"
            required
            class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
            :disabled="loading"
          />
          <button
            type="submit"
            :disabled="loading"
            class="bg-primary-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
          >
            {{ loading ? "Subscribing..." : "Subscribe" }}
          </button>
        </div>

        <!-- Success Message -->
        <div
          v-if="successMessage"
          class="mt-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center gap-2"
        >
          <svg
            class="w-5 h-5 flex-shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          {{ successMessage }}
        </div>

        <!-- Error Message -->
        <div
          v-if="errorMessage"
          class="mt-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center gap-2"
        >
          <svg
            class="w-5 h-5 flex-shrink-0"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          {{ errorMessage }}
        </div>
      </form>
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

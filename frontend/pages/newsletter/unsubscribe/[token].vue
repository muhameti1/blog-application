<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
      <!-- Loading State -->
      <div v-if="loading" class="text-center">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"
        ></div>
        <p class="text-gray-600">Processing your request...</p>
      </div>

      <!-- Success State -->
      <div v-else-if="success" class="text-center">
        <div class="text-green-500 text-5xl mb-4">✓</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">
          Successfully Unsubscribed
        </h1>
        <p class="text-gray-600 mb-6">
          You have been unsubscribed from our newsletter. You won't receive any
          more email notifications.
        </p>
        <NuxtLink
          to="/posts"
          class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors"
        >
          Browse Posts
        </NuxtLink>
      </div>

      <!-- Error State -->
      <div v-else class="text-center">
        <div class="text-red-500 text-5xl mb-4">✕</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">
          Unsubscribe Failed
        </h1>
        <p class="text-gray-600 mb-6">
          {{ errorMessage }}
        </p>
        <NuxtLink
          to="/"
          class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition-colors"
        >
          Go Home
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute();
const { unsubscribe } = useNewsletter();

const loading = ref(true);
const success = ref(false);
const errorMessage = ref("");

onMounted(async () => {
  const token = route.params.token as string;

  if (!token) {
    loading.value = false;
    errorMessage.value = "Invalid unsubscribe link. No token provided.";
    return;
  }

  const result = await unsubscribe(token);
  loading.value = false;

  if (result.success) {
    success.value = true;
  } else {
    errorMessage.value = result.error;
  }
});
</script>

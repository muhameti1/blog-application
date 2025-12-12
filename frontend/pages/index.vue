<template>
  <div
    class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500"
  >
    <div class="flex flex-col items-center justify-center py-16 px-4">
      <div class="text-center mb-12">
        <h1 class="text-6xl font-bold text-white mb-6">Welcome to Blog App</h1>
        <p class="text-xl text-white mb-8">
          A modern blog platform with authentication, posts, comments, and more.
        </p>

        <div class="space-x-4">
          <NuxtLink
            v-if="!isAuthenticated"
            to="/login"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-gray-50"
          >
            Sign In
          </NuxtLink>
          <NuxtLink
            v-if="!isAuthenticated"
            to="/register"
            class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-indigo-700"
          >
            Get Started
          </NuxtLink>
          <NuxtLink
            to="/posts"
            class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-indigo-700"
          >
            Browse Posts
          </NuxtLink>
          <NuxtLink
            v-if="isAuthenticated"
            to="/dashboard"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-gray-50"
          >
            Go to Dashboard
          </NuxtLink>
        </div>

        <div
          class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto"
        >
          <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-lg p-6">
            <div class="text-3xl mb-2">✍️</div>
            <h3 class="text-lg font-semibold text-white mb-2">Create Posts</h3>
            <p class="text-white text-sm">
              Authors can write and publish blog posts
            </p>
          </div>
          <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-lg p-6">
            <div class="text-3xl mb-2">💬</div>
            <h3 class="text-lg font-semibold text-white mb-2">
              Comment & Like
            </h3>
            <p class="text-white text-sm">
              Engage with posts through comments and likes
            </p>
          </div>
          <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-lg p-6">
            <div class="text-3xl mb-2">✅</div>
            <h3 class="text-lg font-semibold text-white mb-2">
              Admin Approval
            </h3>
            <p class="text-white text-sm">
              Posts require admin approval before publishing
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { isAuthenticated, initAuth } = useAuth();

onMounted(() => {
  initAuth();
});
</script>

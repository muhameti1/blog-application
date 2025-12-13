<template>
  <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <NuxtLink to="/" class="flex items-center space-x-2">
            <span class="text-2xl">📝</span>
            <span class="text-xl font-semibold text-gray-900">BlogApp</span>
          </NuxtLink>

          <div class="hidden md:ml-10 md:flex md:items-center md:space-x-8">
            <NuxtLink
              to="/posts"
              class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors"
            >
              Posts
            </NuxtLink>

            <template v-if="isAuthenticated">
              <NuxtLink
                to="/dashboard"
                class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors"
              >
                Dashboard
              </NuxtLink>

              <NuxtLink
                v-if="canCreatePosts"
                to="/posts/create"
                class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors"
              >
                Write
              </NuxtLink>

              <NuxtLink
                v-if="isAdmin"
                to="/admin/posts"
                class="text-gray-700 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors"
              >
                Admin
              </NuxtLink>
            </template>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <template v-if="isAuthenticated">
            <div class="hidden md:flex items-center space-x-3">
              <div class="flex items-center space-x-2 text-sm text-gray-700">
                <span
                  class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-medium"
                >
                  {{ user?.name?.charAt(0).toUpperCase() }}
                </span>
                <span class="font-medium">{{ user?.name }}</span>
              </div>
            </div>

            <button
              @click="handleLogout"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
            >
              Logout
            </button>
          </template>

          <template v-else>
            <NuxtLink
              to="/login"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors"
            >
              Login
            </NuxtLink>
            <NuxtLink
              to="/register"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 transition-colors"
            >
              Sign Up
            </NuxtLink>
          </template>

          <!-- Mobile menu button -->
          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-700 hover:bg-gray-100"
          >
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                v-if="!mobileMenuOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile menu -->
      <div
        v-if="mobileMenuOpen"
        class="md:hidden py-4 border-t border-gray-200"
      >
        <div class="flex flex-col space-y-2">
          <NuxtLink
            to="/posts"
            class="text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium"
            @click="mobileMenuOpen = false"
          >
            Posts
          </NuxtLink>

          <template v-if="isAuthenticated">
            <NuxtLink
              to="/dashboard"
              class="text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium"
              @click="mobileMenuOpen = false"
            >
              Dashboard
            </NuxtLink>

            <NuxtLink
              v-if="canCreatePosts"
              to="/posts/create"
              class="text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium"
              @click="mobileMenuOpen = false"
            >
              Write
            </NuxtLink>

            <NuxtLink
              v-if="isAdmin"
              to="/admin/posts"
              class="text-gray-700 hover:text-primary-600 px-3 py-2 text-base font-medium"
              @click="mobileMenuOpen = false"
            >
              Admin
            </NuxtLink>

            <div class="px-3 py-2 text-sm text-gray-500">
              Logged in as {{ user?.name }}
            </div>
          </template>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
const { isAuthenticated, user, logout, canCreatePosts, isAdmin } = useAuth();
const router = useRouter();
const mobileMenuOpen = ref(false);

const handleLogout = async () => {
  try {
    await logout();
    router.push("/");
  } catch (err) {
    console.error("Logout failed:", err);
  }
};
</script>

<template>
  <footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center space-x-2 mb-4">
            <span class="text-2xl">📝</span>
            <span class="text-xl font-semibold text-gray-900">BlogApp</span>
          </div>
          <p class="text-gray-600 text-sm">
            A modern blog platform for writers and readers. Share your thoughts,
            engage with the community, and discover great content.
          </p>
        </div>

        <div>
          <h3 class="text-sm font-semibold text-gray-900 mb-4">Platform</h3>
          <ul class="space-y-3">
            <li>
              <NuxtLink
                to="/posts"
                class="text-sm text-gray-600 hover:text-primary-600 transition-colors"
              >
                Browse Posts
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                to="/posts/create"
                class="text-sm text-gray-600 hover:text-primary-600 transition-colors"
              >
                Write a Post
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                to="/dashboard"
                class="text-sm text-gray-600 hover:text-primary-600 transition-colors"
              >
                Dashboard
              </NuxtLink>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="text-sm font-semibold text-gray-900 mb-4">Connect</h3>
          <ul class="space-y-3">
            <li>
              <a
                href="#"
                class="text-sm text-gray-600 hover:text-primary-600 transition-colors"
              >
                About Us
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-gray-600 hover:text-primary-600 transition-colors"
              >
                Contact
              </a>
            </li>
            <li>
              <a
                href="#"
                class="text-sm text-gray-600 hover:text-primary-600 transition-colors"
              >
                Privacy Policy
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="mt-8 pt-8 border-t border-gray-200">
        <p class="text-center text-sm text-gray-500">
          © {{ new Date().getFullYear() }} BlogApp. All rights reserved.
        </p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts"></script>

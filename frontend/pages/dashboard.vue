<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-gray-900">Blog Dashboard</h1>
            </div>
          </div>
          <div class="flex items-center">
            <button
              @click="handleLogout"
              class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow rounded-lg p-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">
            Welcome, {{ user?.name }}!
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg">
              <div class="text-sm text-blue-600 font-medium">Email</div>
              <div class="text-lg text-gray-900">{{ user?.email }}</div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
              <div class="text-sm text-green-600 font-medium">Role</div>
              <div class="text-lg text-gray-900 capitalize">{{ user?.role }}</div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg">
              <div class="text-sm text-purple-600 font-medium">Status</div>
              <div class="text-lg text-gray-900">
                {{ user?.email_verified_at ? 'Verified' : 'Unverified' }}
              </div>
            </div>
          </div>

          <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Permissions</h3>
            <ul class="space-y-2">
              <li class="flex items-center">
                <span :class="canCreatePosts ? 'text-green-500' : 'text-gray-400'" class="mr-2">
                  {{ canCreatePosts ? '✓' : '✗' }}
                </span>
                <span>Can create posts</span>
              </li>
              <li class="flex items-center">
                <span :class="isAdmin ? 'text-green-500' : 'text-gray-400'" class="mr-2">
                  {{ isAdmin ? '✓' : '✗' }}
                </span>
                <span>Can approve posts</span>
              </li>
              <li class="flex items-center">
                <span class="text-green-500 mr-2">✓</span>
                <span>Can comment on posts</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth',
})

const { user, logout, isAdmin, canCreatePosts } = useAuth()
const router = useRouter()

const handleLogout = async () => {
  await logout()
  router.push('/login')
}
</script>

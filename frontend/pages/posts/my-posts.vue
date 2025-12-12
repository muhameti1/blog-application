<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const { getMyPosts } = usePosts();
const { user } = useAuth();

const loading = ref(true);
const error = ref("");
const posts = ref<any[]>([]);

onMounted(async () => {
  try {
    posts.value = await getMyPosts();
  } catch (err: any) {
    error.value = err.data?.message || "Failed to load posts";
  } finally {
    loading.value = false;
  }
});

const getStatusBadgeClass = (status: string) => {
  switch (status) {
    case "approved":
      return "bg-green-100 text-green-800";
    case "pending":
      return "bg-yellow-100 text-yellow-800";
    case "rejected":
      return "bg-red-100 text-red-800";
    case "draft":
      return "bg-gray-100 text-gray-800";
    default:
      return "bg-gray-100 text-gray-800";
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Posts</h1>
        <NuxtLink
          to="/posts/create"
          class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium"
        >
          Create New Post
        </NuxtLink>
      </div>

      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-600">Loading your posts...</p>
      </div>

      <div v-else-if="error" class="text-center py-12">
        <p class="text-red-600">{{ error }}</p>
      </div>

      <div v-else-if="posts.length === 0" class="text-center py-12">
        <p class="text-gray-600 mb-4">You haven't created any posts yet.</p>
        <NuxtLink
          to="/posts/create"
          class="inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium"
        >
          Create Your First Post
        </NuxtLink>
      </div>

      <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Title
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Status
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Created
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="post in posts" :key="post.id" class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ post.title }}
                  </div>
                  <div v-if="post.excerpt" class="text-sm text-gray-500 mt-1">
                    {{ post.excerpt.substring(0, 100)
                    }}{{ post.excerpt.length > 100 ? "..." : "" }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getStatusBadgeClass(post.status)"
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                  >
                    {{ post.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ new Date(post.created_at).toLocaleDateString() }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                >
                  <NuxtLink
                    :to="`/posts/${post.slug}`"
                    class="text-blue-600 hover:text-blue-900 mr-4"
                  >
                    View
                  </NuxtLink>
                  <NuxtLink
                    :to="`/posts/edit/${post.slug}`"
                    class="text-green-600 hover:text-green-900"
                  >
                    Edit
                  </NuxtLink>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

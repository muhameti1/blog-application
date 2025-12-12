<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Blog Posts</h1>
          <NuxtLink
            v-if="user"
            to="/dashboard"
            class="text-sm text-blue-600 hover:text-blue-800 mt-1 inline-block"
          >
            ← Back to Dashboard
          </NuxtLink>
        </div>
        <NuxtLink
          v-if="canCreatePosts"
          to="/posts/create"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Create Post
        </NuxtLink>
      </div>

      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-500">Loading posts...</p>
      </div>

      <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
        <p class="text-red-800">{{ error }}</p>
      </div>

      <div v-else-if="posts.length === 0" class="text-center py-12">
        <p class="text-gray-500">No posts found.</p>
      </div>

      <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <NuxtLink
          v-for="post in posts"
          :key="post.id"
          :to="`/posts/${post.slug}`"
          class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
        >
          <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">
              {{ post.title }}
            </h2>
            <p class="text-gray-600 mb-4">{{ post.excerpt }}</p>
            <div
              class="flex items-center justify-between text-sm text-gray-500"
            >
              <div class="flex items-center">
                <span class="font-medium">{{ post.user?.name }}</span>
              </div>
              <div class="flex items-center space-x-4">
                <span v-if="post.comments_count !== undefined">
                  💬 {{ post.comments_count }}
                </span>
                <span v-if="post.likes_count !== undefined">
                  ❤️ {{ post.likes_count }}
                </span>
              </div>
            </div>
            <div class="mt-2 text-xs text-gray-400">
              {{ formatDate(post.published_at || post.created_at) }}
            </div>
          </div>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { PostListItem } from "~/types/post";

const { getPosts } = usePosts();
const { canCreatePosts, initAuth } = useAuth();

const posts = ref<PostListItem[]>([]);
const loading = ref(true);
const error = ref("");

onMounted(async () => {
  initAuth();
  try {
    posts.value = await getPosts();
  } catch (err: any) {
    error.value = "Failed to load posts";
  } finally {
    loading.value = false;
  }
});

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};
</script>

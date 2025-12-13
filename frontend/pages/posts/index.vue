<template>
  <div>
    <AppHeader />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8"
      >
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Blog Posts</h1>
          <p class="text-gray-600">Discover stories from our community</p>
        </div>
        <NuxtLink
          v-if="canCreatePosts"
          to="/posts/create"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 transition-colors"
        >
          <svg
            class="w-5 h-5 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 4v16m8-8H4"
            />
          </svg>
          Create Post
        </NuxtLink>
      </div>

      <div v-if="loading" class="text-center py-12">
        <div
          class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
        ></div>
      </div>

      <div
        v-else-if="error"
        class="bg-red-50 border border-red-200 rounded-lg p-4"
      >
        <p class="text-red-800">{{ error }}</p>
      </div>

      <div v-else-if="!posts || posts.length === 0" class="text-center py-20">
        <div class="text-gray-400 text-5xl mb-4">📝</div>
        <p class="text-gray-500 text-lg mb-2">No posts found</p>
        <p class="text-gray-400 text-sm">Be the first to create a post!</p>
      </div>

      <div v-else-if="posts && posts.length > 0">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <article
            v-for="post in posts"
            :key="post.id"
            class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow"
          >
            <NuxtLink :to="`/posts/${post.slug}`" class="block p-6">
              <div class="flex items-center gap-3 mb-4">
                <div
                  class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-medium"
                >
                  {{ post.user?.name?.charAt(0).toUpperCase() }}
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ post.user?.name }}
                  </p>
                  <p class="text-xs text-gray-500">
                    {{ formatDate(post.published_at || post.created_at) }}
                  </p>
                </div>
              </div>

              <h2 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
                {{ post.title }}
              </h2>
              <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                {{ post.excerpt }}
              </p>

              <div
                class="flex items-center justify-between text-sm text-gray-500 pt-4 border-t border-gray-100"
              >
                <div class="flex items-center gap-4">
                  <span class="flex items-center gap-1">
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                      />
                    </svg>
                    {{ post.likes_count || 0 }}
                  </span>
                  <span class="flex items-center gap-1">
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                      />
                    </svg>
                    {{ post.comments_count || 0 }}
                  </span>
                </div>
                <span class="text-primary-600 font-medium">Read more →</span>
              </div>
            </NuxtLink>
          </article>
        </div>

        <div v-if="lastUpdated" class="text-center mt-8 text-sm text-gray-500">
          Last updated: {{ formatTime(lastUpdated) }}
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup lang="ts">
import type { PostListItem } from "~/types/post";

const { getPosts } = usePosts();
const { canCreatePosts, initAuth } = useAuth();

// Use real-time updates
const {
  data: posts,
  loading,
  error,
  lastUpdated,
} = useRealtime<PostListItem[]>(
  async () => {
    return await getPosts();
  },
  { interval: 10000, initialData: [] } // Update every 10 seconds
);

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

const formatTime = (date: Date) => {
  return date.toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
  });
};

onMounted(() => {
  initAuth();
});
</script>

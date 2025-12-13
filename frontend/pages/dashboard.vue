<template>
  <div>
    <AppHeader />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Welcome Section -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Welcome back, {{ user?.name }}!
        </h1>
        <p class="text-gray-600">
          Here's what's happening with your blog today
        </p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <div class="flex items-center justify-between mb-2">
            <div class="text-sm font-medium text-gray-600">Total Posts</div>
            <div
              class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center"
            >
              <svg
                class="w-4 h-4 text-primary-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
            </div>
          </div>
          <div class="text-3xl font-bold text-gray-900">
            {{ stats.totalPosts }}
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <div class="flex items-center justify-between mb-2">
            <div class="text-sm font-medium text-gray-600">Published</div>
            <div
              class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center"
            >
              <svg
                class="w-4 h-4 text-green-600"
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
            </div>
          </div>
          <div class="text-3xl font-bold text-gray-900">
            {{ stats.approvedPosts }}
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <div class="flex items-center justify-between mb-2">
            <div class="text-sm font-medium text-gray-600">Pending</div>
            <div
              class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center"
            >
              <svg
                class="w-4 h-4 text-yellow-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>
          </div>
          <div class="text-3xl font-bold text-gray-900">
            {{ stats.pendingPosts }}
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <div class="flex items-center justify-between mb-2">
            <div class="text-sm font-medium text-gray-600">Drafts</div>
            <div
              class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center"
            >
              <svg
                class="w-4 h-4 text-gray-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
              </svg>
            </div>
          </div>
          <div class="text-3xl font-bold text-gray-900">
            {{ stats.draftPosts }}
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <NuxtLink
            to="/posts"
            class="flex flex-col items-center justify-center p-6 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50 transition-all group"
          >
            <svg
              class="w-8 h-8 text-gray-600 group-hover:text-primary-600 mb-3"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
              />
            </svg>
            <span
              class="text-sm font-medium text-gray-900 group-hover:text-primary-600"
              >Browse Posts</span
            >
          </NuxtLink>

          <NuxtLink
            v-if="canCreatePosts"
            to="/posts/create"
            class="flex flex-col items-center justify-center p-6 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50 transition-all group"
          >
            <svg
              class="w-8 h-8 text-gray-600 group-hover:text-primary-600 mb-3"
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
            <span
              class="text-sm font-medium text-gray-900 group-hover:text-primary-600"
              >Create Post</span
            >
          </NuxtLink>

          <NuxtLink
            v-if="canCreatePosts"
            to="/posts/my-posts"
            class="flex flex-col items-center justify-center p-6 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50 transition-all group"
          >
            <svg
              class="w-8 h-8 text-gray-600 group-hover:text-primary-600 mb-3"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
            <span
              class="text-sm font-medium text-gray-900 group-hover:text-primary-600"
              >My Posts</span
            >
          </NuxtLink>

          <NuxtLink
            v-if="isAdmin"
            to="/admin/posts"
            class="flex flex-col items-center justify-center p-6 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50 transition-all group"
          >
            <svg
              class="w-8 h-8 text-gray-600 group-hover:text-primary-600 mb-3"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
              />
            </svg>
            <span
              class="text-sm font-medium text-gray-900 group-hover:text-primary-600"
              >Admin Panel</span
            >
          </NuxtLink>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold text-gray-900">Recent Posts</h2>
          <NuxtLink
            v-if="canCreatePosts"
            to="/posts/my-posts"
            class="text-sm text-primary-600 hover:text-primary-700 font-medium"
          >
            View All
          </NuxtLink>
        </div>

        <div v-if="loading" class="text-center py-12">
          <div
            class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
          ></div>
        </div>

        <div
          v-else-if="recentPosts && recentPosts.length > 0"
          class="space-y-4"
        >
          <div
            v-for="post in recentPosts"
            :key="post.id"
            class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <div class="flex-1 min-w-0">
              <NuxtLink :to="`/posts/${post.slug}`" class="block">
                <h3 class="text-sm font-medium text-gray-900 truncate mb-1">
                  {{ post.title }}
                </h3>
                <p class="text-xs text-gray-500">
                  {{ formatDate(post.created_at) }}
                </p>
              </NuxtLink>
            </div>
            <div class="flex items-center gap-3 ml-4">
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="{
                  'bg-green-100 text-green-800': post.status === 'approved',
                  'bg-yellow-100 text-yellow-800': post.status === 'pending',
                  'bg-red-100 text-red-800': post.status === 'rejected',
                  'bg-gray-100 text-gray-800': post.status === 'draft',
                }"
              >
                {{ post.status }}
              </span>
              <div class="flex items-center gap-2 text-sm text-gray-500">
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
            </div>
          </div>
        </div>

        <div v-else class="text-center py-12">
          <p class="text-gray-500 mb-4">You haven't created any posts yet</p>
          <NuxtLink
            v-if="canCreatePosts"
            to="/posts/create"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700"
          >
            Create Your First Post
          </NuxtLink>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const { user, canCreatePosts, isAdmin } = useAuth();
const { getMyPosts } = usePosts();

const loading = ref(true);
const recentPosts = ref<any[]>([]);
const stats = ref({
  totalPosts: 0,
  approvedPosts: 0,
  pendingPosts: 0,
  draftPosts: 0,
});

const loadDashboardData = async () => {
  if (!canCreatePosts.value) {
    loading.value = false;
    return;
  }

  try {
    const posts = await getMyPosts();

    recentPosts.value = posts.slice(0, 5);
    stats.value = {
      totalPosts: posts.length,
      approvedPosts: posts.filter((p: any) => p.status === "approved").length,
      pendingPosts: posts.filter((p: any) => p.status === "pending").length,
      draftPosts: posts.filter((p: any) => p.status === "draft").length,
    };
  } catch (err) {
    console.error("Failed to load dashboard data:", err);
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

onMounted(() => {
  loadDashboardData();
});
</script>

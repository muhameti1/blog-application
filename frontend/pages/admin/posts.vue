<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const router = useRouter();
const { user } = useAuth();
const { getAdminPosts, approvePost, rejectPost } = usePosts();

// Redirect if not admin
onMounted(() => {
  if (user.value?.role !== "admin") {
    router.push("/dashboard");
  }
});

const loading = ref(true);
const error = ref("");
const posts = ref<any[]>([]);
const processingIds = ref<Set<number>>(new Set());

const loadPosts = async () => {
  loading.value = true;
  try {
    posts.value = await getAdminPosts();
  } catch (err: any) {
    error.value = err.data?.message || "Failed to load posts";
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  if (user.value?.role === "admin") {
    await loadPosts();
  }
});

const handleApprove = async (postId: number) => {
  processingIds.value.add(postId);
  error.value = "";

  try {
    await approvePost(postId);
    await loadPosts(); // Reload posts
  } catch (err: any) {
    error.value = err.data?.message || "Failed to approve post";
  } finally {
    processingIds.value.delete(postId);
  }
};

const handleReject = async (postId: number) => {
  if (!confirm("Are you sure you want to reject this post?")) return;

  processingIds.value.add(postId);
  error.value = "";

  try {
    await rejectPost(postId);
    await loadPosts(); // Reload posts
  } catch (err: any) {
    error.value = err.data?.message || "Failed to reject post";
  } finally {
    processingIds.value.delete(postId);
  }
};

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

const pendingPosts = computed(() =>
  posts.value.filter((p) => p.status === "pending")
);
const approvedPosts = computed(() =>
  posts.value.filter((p) => p.status === "approved")
);
const rejectedPosts = computed(() =>
  posts.value.filter((p) => p.status === "rejected")
);
const draftPosts = computed(() =>
  posts.value.filter((p) => p.status === "draft")
);
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage posts and moderate content</p>
      </div>

      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-600">Loading posts...</p>
      </div>

      <div v-else-if="user?.role !== 'admin'" class="text-center py-12">
        <p class="text-red-600">Access denied. Admin only.</p>
      </div>

      <div v-else>
        <div v-if="error" class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
          <p class="text-sm text-red-600">{{ error }}</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Pending Review</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">
              {{ pendingPosts.length }}
            </p>
          </div>
          <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Approved</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
              {{ approvedPosts.length }}
            </p>
          </div>
          <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Rejected</p>
            <p class="text-3xl font-bold text-red-600 mt-2">
              {{ rejectedPosts.length }}
            </p>
          </div>
          <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Drafts</p>
            <p class="text-3xl font-bold text-gray-600 mt-2">
              {{ draftPosts.length }}
            </p>
          </div>
        </div>

        <!-- Pending Posts (Priority) -->
        <div v-if="pendingPosts.length > 0" class="mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-4">
            Pending Review ({{ pendingPosts.length }})
          </h2>
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Title
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Author
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Created
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="post in pendingPosts" :key="post.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ post.title }}
                      </div>
                      <div v-if="post.excerpt" class="text-sm text-gray-500 mt-1">
                        {{ post.excerpt.substring(0, 80)
                        }}{{ post.excerpt.length > 80 ? "..." : "" }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ post.author?.name || "Unknown" }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ new Date(post.created_at).toLocaleDateString() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                      <NuxtLink
                        :to="`/posts/${post.slug}`"
                        class="text-blue-600 hover:text-blue-900"
                      >
                        View
                      </NuxtLink>
                      <button
                        @click="handleApprove(post.id)"
                        :disabled="processingIds.has(post.id)"
                        class="text-green-600 hover:text-green-900 disabled:opacity-50"
                      >
                        Approve
                      </button>
                      <button
                        @click="handleReject(post.id)"
                        :disabled="processingIds.has(post.id)"
                        class="text-red-600 hover:text-red-900 disabled:opacity-50"
                      >
                        Reject
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- All Posts -->
        <div>
          <h2 class="text-xl font-bold text-gray-900 mb-4">
            All Posts ({{ posts.length }})
          </h2>
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Title
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Author
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                      Created
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ post.author?.name || "Unknown" }}
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
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <NuxtLink
                        :to="`/posts/${post.slug}`"
                        class="text-blue-600 hover:text-blue-900"
                      >
                        View
                      </NuxtLink>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

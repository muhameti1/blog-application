<template>
  <div>
    <AppHeader />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div v-if="loading" class="text-center py-20">
        <div
          class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"
        ></div>
        <p class="text-gray-500 mt-4">Loading post...</p>
      </div>

      <div
        v-else-if="error"
        class="bg-red-50 border border-red-200 rounded-lg p-4"
      >
        <p class="text-red-800">{{ error }}</p>
      </div>

      <article
        v-else-if="post"
        class="bg-white border border-gray-200 rounded-xl overflow-hidden"
      >
        <div class="p-8">
          <header class="mb-8">
            <!-- Status Badge -->
            <div v-if="post.status !== 'approved'" class="mb-4">
              <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                :class="{
                  'bg-yellow-100 text-yellow-800': post.status === 'pending',
                  'bg-red-100 text-red-800': post.status === 'rejected',
                  'bg-gray-100 text-gray-800': post.status === 'draft',
                }"
              >
                {{ post.status.toUpperCase() }}
              </span>
            </div>

            <!-- Title -->
            <h1
              class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight"
            >
              {{ post.title }}
            </h1>

            <!-- Author Info -->
            <div
              class="flex items-center justify-between flex-wrap gap-4 pb-6 border-b border-gray-200"
            >
              <div class="flex items-center gap-3">
                <div
                  class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-medium text-lg"
                >
                  {{ post.user?.name?.charAt(0).toUpperCase() }}
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ post.user?.name }}
                  </p>
                  <p class="text-xs text-gray-500">
                    {{ formatDate(post.published_at || post.created_at) }}
                  </p>
                </div>
              </div>

              <div class="flex items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center gap-1.5">
                  <svg
                    class="w-5 h-5"
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
                <span class="flex items-center gap-1.5">
                  <svg
                    class="w-5 h-5"
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

            <!-- Action Buttons -->
            <div v-if="canEdit" class="flex flex-wrap gap-2 mt-6">
              <NuxtLink
                :to="`/posts/edit/${post.slug}`"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
              >
                <svg
                  class="w-4 h-4 mr-2"
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
                Edit
              </NuxtLink>
              <button
                v-if="isAdmin && post.status !== 'approved'"
                @click="handleApprove"
                :disabled="approving"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                <svg
                  v-if="approving"
                  class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                {{ approving ? "Approving..." : "Approve Post" }}
              </button>
              <button
                @click="handleDelete"
                :disabled="deleting"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                <svg
                  v-if="deleting"
                  class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
                {{ deleting ? "Deleting..." : "Delete" }}
              </button>
            </div>
          </header>

          <!-- Content -->
          <div
            class="prose prose-lg max-w-none mb-8"
            v-html="post.content"
          ></div>
        </div>

        <!-- Like Button Section -->
        <div class="border-t border-gray-200 px-8 py-6 bg-gray-50">
          <LikeButton
            :post-id="post.id"
            :initial-count="post.likes_count || 0"
          />
        </div>
      </article>

      <!-- Comments Section -->
      <div v-if="post" class="mt-8">
        <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Comments</h2>
          <CommentForm :post-id="post.id" @comment-added="handleCommentAdded" />
        </div>
        <CommentList ref="commentListRef" :post-id="post.id" />
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup lang="ts">
import type { Post } from "~/types/post";

const route = useRoute();
const router = useRouter();
const { getPost, deletePost, approvePost } = usePosts();
const { user, isAdmin, initAuth } = useAuth();

const post = ref<Post | null>(null);
const loading = ref(true);
const error = ref("");
const deleting = ref(false);
const approving = ref(false);
const commentListRef = ref<any>(null);

const canEdit = computed(() => {
  if (!user.value || !post.value) return false;
  return isAdmin.value || post.value.user?.id === user.value.id;
});

const handleCommentAdded = () => {
  if (commentListRef.value) {
    commentListRef.value.loadComments();
  }
};

onMounted(async () => {
  // Initialize auth first to load token
  initAuth();

  // Add a small delay to ensure token is loaded from localStorage
  await new Promise((resolve) => setTimeout(resolve, 50));

  try {
    post.value = await getPost(route.params.slug as string);
  } catch (err: any) {
    error.value = err.data?.message || "Failed to load post";
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

const handleDelete = async () => {
  if (!post.value || !confirm("Are you sure you want to delete this post?"))
    return;

  deleting.value = true;
  try {
    await deletePost(post.value.id);
    router.push("/posts");
  } catch (err: any) {
    alert(err.data?.message || "Failed to delete post");
  } finally {
    deleting.value = false;
  }
};

const handleApprove = async () => {
  if (!post.value) return;

  approving.value = true;
  try {
    post.value = await approvePost(post.value.id);
    alert("Post approved successfully");
  } catch (err: any) {
    alert(err.data?.message || "Failed to approve post");
  } finally {
    approving.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-500">Loading post...</p>
      </div>

      <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
        <p class="text-red-800">{{ error }}</p>
      </div>

      <article v-else-if="post" class="bg-white rounded-lg shadow-lg p-8">
        <header class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 mb-4">
            {{ post.title }}
          </h1>

          <div
            class="flex items-center justify-between text-sm text-gray-600 mb-4"
          >
            <div class="flex items-center space-x-4">
              <span class="font-medium">By {{ post.user?.name }}</span>
              <span>{{
                formatDate(post.published_at || post.created_at)
              }}</span>
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

          <div v-if="post.status !== 'approved'" class="mb-4">
            <span
              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
              :class="{
                'bg-yellow-100 text-yellow-800': post.status === 'pending',
                'bg-red-100 text-red-800': post.status === 'rejected',
                'bg-gray-100 text-gray-800': post.status === 'draft',
              }"
            >
              {{ post.status.toUpperCase() }}
            </span>
          </div>

          <div v-if="canEdit" class="flex space-x-2">
            <NuxtLink
              :to="`/posts/${post.id}/edit`"
              class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Edit
            </NuxtLink>
            <button
              v-if="isAdmin"
              @click="handleApprove"
              :disabled="post.status === 'approved' || approving"
              class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 disabled:opacity-50"
            >
              {{ approving ? "Approving..." : "Approve" }}
            </button>
            <button
              @click="handleDelete"
              :disabled="deleting"
              class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 disabled:opacity-50"
            >
              {{ deleting ? "Deleting..." : "Delete" }}
            </button>
          </div>
        </header>

        <div class="prose prose-lg max-w-none" v-html="post.content"></div>
      </article>
    </div>
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

const canEdit = computed(() => {
  if (!user.value || !post.value) return false;
  return isAdmin.value || post.value.user?.id === user.value.id;
});

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

<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const router = useRouter();
const { user } = useAuth();
const { getAdminComments, approveComment, rejectComment, deleteComment } =
  useComments();

// Redirect if not admin
onMounted(() => {
  if (user.value?.role !== "admin") {
    router.push("/dashboard");
  }
});

const loading = ref(true);
const error = ref("");
const comments = ref<any[]>([]);
const processingIds = ref<Set<number>>(new Set());

const loadComments = async () => {
  loading.value = true;
  try {
    comments.value = await getAdminComments();
  } catch (err: any) {
    error.value = err.data?.message || "Failed to load comments";
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  if (user.value?.role === "admin") {
    await loadComments();
  }
});

const handleApprove = async (commentId: number) => {
  processingIds.value.add(commentId);
  error.value = "";

  try {
    await approveComment(commentId);
    await loadComments();
  } catch (err: any) {
    error.value = err.data?.message || "Failed to approve comment";
  } finally {
    processingIds.value.delete(commentId);
  }
};

const handleReject = async (commentId: number) => {
  if (!confirm("Are you sure you want to reject this comment?")) return;

  processingIds.value.add(commentId);
  error.value = "";

  try {
    await rejectComment(commentId);
    await loadComments();
  } catch (err: any) {
    error.value = err.data?.message || "Failed to reject comment";
  } finally {
    processingIds.value.delete(commentId);
  }
};

const handleDelete = async (commentId: number) => {
  if (!confirm("Are you sure you want to delete this comment?")) return;

  processingIds.value.add(commentId);
  error.value = "";

  try {
    await deleteComment(commentId);
    await loadComments();
  } catch (err: any) {
    error.value = err.data?.message || "Failed to delete comment";
  } finally {
    processingIds.value.delete(commentId);
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
    default:
      return "bg-gray-100 text-gray-800";
  }
};

const pendingComments = computed(() =>
  comments.value.filter((c) => c.status === "pending")
);
const approvedComments = computed(() =>
  comments.value.filter((c) => c.status === "approved")
);
const rejectedComments = computed(() =>
  comments.value.filter((c) => c.status === "rejected")
);
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Comment Moderation</h1>
        <p class="text-gray-600 mt-2">Review and moderate user comments</p>
      </div>

      <div v-if="loading" class="text-center py-12">
        <p class="text-gray-600">Loading comments...</p>
      </div>

      <div v-else-if="user?.role !== 'admin'" class="text-center py-12">
        <p class="text-red-600">Access denied. Admin only.</p>
      </div>

      <div v-else>
        <div
          v-if="error"
          class="mb-6 bg-red-50 border border-red-200 rounded-md p-4"
        >
          <p class="text-sm text-red-600">{{ error }}</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Pending Review</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">
              {{ pendingComments.length }}
            </p>
          </div>
          <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Approved</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
              {{ approvedComments.length }}
            </p>
          </div>
          <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Rejected</p>
            <p class="text-3xl font-bold text-red-600 mt-2">
              {{ rejectedComments.length }}
            </p>
          </div>
        </div>

        <!-- Pending Comments -->
        <div v-if="pendingComments.length > 0" class="mb-8">
          <h2 class="text-xl font-bold text-gray-900 mb-4">
            Pending Review ({{ pendingComments.length }})
          </h2>
          <div class="space-y-4">
            <div
              v-for="comment in pendingComments"
              :key="comment.id"
              class="bg-white rounded-lg shadow-md p-6"
            >
              <div class="flex justify-between items-start">
                <div class="flex-1">
                  <div class="flex items-center space-x-2 mb-2">
                    <span class="font-semibold text-gray-900">{{
                      comment.user.name
                    }}</span>
                    <span class="text-sm text-gray-500">
                      on post "{{ comment.post?.title || "Unknown" }}"
                    </span>
                  </div>
                  <p class="text-gray-700 mb-2 whitespace-pre-wrap">
                    {{ comment.content }}
                  </p>
                  <p class="text-xs text-gray-500">
                    {{ new Date(comment.created_at).toLocaleString() }}
                  </p>
                </div>
              </div>
              <div class="mt-4 flex space-x-2">
                <button
                  @click="handleApprove(comment.id)"
                  :disabled="processingIds.has(comment.id)"
                  class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 text-sm"
                >
                  Approve
                </button>
                <button
                  @click="handleReject(comment.id)"
                  :disabled="processingIds.has(comment.id)"
                  class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50 text-sm"
                >
                  Reject
                </button>
                <button
                  @click="handleDelete(comment.id)"
                  :disabled="processingIds.has(comment.id)"
                  class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 disabled:opacity-50 text-sm"
                >
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- All Comments -->
        <div>
          <h2 class="text-xl font-bold text-gray-900 mb-4">
            All Comments ({{ comments.length }})
          </h2>
          <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                    >
                      Author
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                    >
                      Comment
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                    >
                      Post
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                    >
                      Status
                    </th>
                    <th
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                    >
                      Date
                    </th>
                    <th
                      class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                    >
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr
                    v-for="comment in comments"
                    :key="comment.id"
                    class="hover:bg-gray-50"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ comment.user.name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                      <div class="max-w-xs truncate">{{ comment.content }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ comment.post?.title || "N/A" }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="getStatusBadgeClass(comment.status)"
                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                      >
                        {{ comment.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ new Date(comment.created_at).toLocaleDateString() }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                    >
                      <button
                        @click="handleDelete(comment.id)"
                        :disabled="processingIds.has(comment.id)"
                        class="text-red-600 hover:text-red-900 disabled:opacity-50"
                      >
                        Delete
                      </button>
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

<script setup lang="ts">
import type { Comment } from "~/types/comment";

const props = defineProps<{
  postId: number;
}>();

const { user, isAdmin } = useAuth();
const { getPostComments, deleteComment } = useComments();

const deletingIds = ref<Set<number>>(new Set());

// Use real-time updates for comments
const {
  data: comments,
  loading,
  error,
  refresh,
} = useRealtime<Comment[]>(
  async () => {
    return await getPostComments(props.postId);
  },
  { interval: 8000 } // Update every 8 seconds
);

const loadComments = async () => {
  await refresh();
};

const handleDelete = async (commentId: number) => {
  if (!confirm("Are you sure you want to delete this comment?")) return;

  deletingIds.value.add(commentId);
  try {
    await deleteComment(commentId);
    await loadComments();
  } catch (err: any) {
    alert(err.data?.message || "Failed to delete comment");
  } finally {
    deletingIds.value.delete(commentId);
  }
};

const canDelete = (comment: Comment) => {
  if (!user.value) return false;
  return isAdmin.value || comment.user.id === user.value.id;
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffMs = now.getTime() - date.getTime();
  const diffMins = Math.floor(diffMs / 60000);
  const diffHours = Math.floor(diffMs / 3600000);
  const diffDays = Math.floor(diffMs / 86400000);

  if (diffMins < 1) return "Just now";
  if (diffMins < 60) return `${diffMins}m ago`;
  if (diffHours < 24) return `${diffHours}h ago`;
  if (diffDays < 7) return `${diffDays}d ago`;

  return date.toLocaleDateString("en-US", {
    month: "short",
    day: "numeric",
    year: date.getFullYear() !== now.getFullYear() ? "numeric" : undefined,
  });
};

defineExpose({
  loadComments,
});
</script>

<template>
  <div>
    <div v-if="loading" class="text-center py-12">
      <div
        class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
      ></div>
      <p class="text-gray-500 mt-4 text-sm">Loading comments...</p>
    </div>

    <div
      v-else-if="error"
      class="bg-red-50 border border-red-200 rounded-lg p-4"
    >
      <p class="text-red-800 text-sm">{{ error }}</p>
    </div>

    <div
      v-else-if="!comments || comments.length === 0"
      class="bg-white border border-gray-200 rounded-xl p-12 text-center"
    >
      <svg
        class="w-16 h-16 text-gray-300 mx-auto mb-4"
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
      <p class="text-gray-500 text-lg">No comments yet</p>
      <p class="text-gray-400 text-sm mt-1">
        Be the first to share your thoughts!
      </p>
    </div>

    <div v-else class="space-y-4">
      <article
        v-for="comment in comments"
        :key="comment.id"
        class="bg-white border border-gray-200 rounded-xl p-6 hover:border-gray-300 transition-colors"
      >
        <div class="flex items-start gap-3">
          <div
            class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-medium flex-shrink-0"
          >
            {{ comment.user.name?.charAt(0).toUpperCase() }}
          </div>

          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="font-semibold text-gray-900">
                  {{ comment.user.name }}
                </span>
                <span class="text-sm text-gray-500">
                  {{ formatDate(comment.created_at) }}
                </span>
              </div>

              <button
                v-if="canDelete(comment)"
                @click="handleDelete(comment.id)"
                :disabled="deletingIds.has(comment.id)"
                class="text-gray-400 hover:text-red-600 transition-colors disabled:opacity-50 flex-shrink-0"
                title="Delete comment"
              >
                <svg
                  v-if="deletingIds.has(comment.id)"
                  class="animate-spin h-5 w-5"
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
                <svg
                  v-else
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
              </button>
            </div>

            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">
              {{ comment.content }}
            </p>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>

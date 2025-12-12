<script setup lang="ts">
import type { Comment } from "~/types/comment";

const props = defineProps<{
  postId: number;
}>();

const { user, isAdmin } = useAuth();
const { getPostComments, deleteComment, createComment } = useComments();

const comments = ref<Comment[]>([]);
const loading = ref(true);
const error = ref("");
const deletingIds = ref<Set<number>>(new Set());
const replyingTo = ref<number | null>(null);
const replyContent = ref("");
const submittingReply = ref(false);
const editingId = ref<number | null>(null);
const editContent = ref("");

const loadComments = async () => {
  loading.value = true;
  try {
    comments.value = await getPostComments(props.postId);
  } catch (err: any) {
    error.value = err.data?.message || "Failed to load comments";
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadComments();
});

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

const startReply = (commentId: number) => {
  replyingTo.value = commentId;
  replyContent.value = "";
};

const cancelReply = () => {
  replyingTo.value = null;
  replyContent.value = "";
};

const submitReply = async (parentId: number) => {
  if (!replyContent.value.trim()) return;

  submittingReply.value = true;
  try {
    await createComment({
      post_id: props.postId,
      parent_id: parentId,
      content: replyContent.value,
    });

    replyingTo.value = null;
    replyContent.value = "";
    await loadComments();
  } catch (err: any) {
    alert(err.data?.message || "Failed to post reply");
  } finally {
    submittingReply.value = false;
  }
};

const canEdit = (comment: Comment) => {
  if (!user.value) return false;
  return isAdmin.value || comment.user.id === user.value.id;
};

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

defineExpose({
  loadComments,
});
</script>

<template>
  <div class="space-y-6">
    <h3 class="text-2xl font-bold text-gray-900">
      Comments ({{ comments.length }})
    </h3>

    <div v-if="loading" class="text-center py-8">
      <p class="text-gray-500">Loading comments...</p>
    </div>

    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-md p-4">
      <p class="text-sm text-red-600">{{ error }}</p>
    </div>

    <div v-else-if="comments.length === 0" class="text-center py-8">
      <p class="text-gray-500">No comments yet. Be the first to comment!</p>
    </div>

    <div v-else class="space-y-4">
      <div
        v-for="comment in comments"
        :key="comment.id"
        class="bg-white rounded-lg shadow p-6"
      >
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <div class="flex items-center space-x-2 mb-2">
              <span class="font-semibold text-gray-900">{{
                comment.user.name
              }}</span>
              <span class="text-sm text-gray-500">{{
                formatDate(comment.created_at)
              }}</span>

            </div>
            <p class="text-gray-700 whitespace-pre-wrap">{{ comment.content }}</p>

            <div class="mt-4 flex items-center space-x-4 text-sm">
              <button
                v-if="user"
                @click="startReply(comment.id)"
                class="text-blue-600 hover:text-blue-800 font-medium"
              >
                Reply
              </button>
              <button
                v-if="canEdit(comment)"
                @click="handleDelete(comment.id)"
                :disabled="deletingIds.has(comment.id)"
                class="text-red-600 hover:text-red-800 font-medium disabled:opacity-50"
              >
                {{ deletingIds.has(comment.id) ? "Deleting..." : "Delete" }}
              </button>
            </div>

            <!-- Reply Form -->
            <div v-if="replyingTo === comment.id" class="mt-4 ml-4 pl-4 border-l-2 border-gray-200">
              <textarea
                v-model="replyContent"
                rows="3"
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border"
                placeholder="Write your reply..."
              ></textarea>
              <div class="mt-2 flex justify-end space-x-2">
                <button
                  @click="cancelReply"
                  class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
                >
                  Cancel
                </button>
                <button
                  @click="submitReply(comment.id)"
                  :disabled="submittingReply || !replyContent.trim()"
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                >
                  {{ submittingReply ? "Posting..." : "Post Reply" }}
                </button>
              </div>
            </div>

            <!-- Replies -->
            <div
              v-if="comment.replies && comment.replies.length > 0"
              class="mt-4 ml-8 space-y-4"
            >
              <div
                v-for="reply in comment.replies"
                :key="reply.id"
                class="bg-gray-50 rounded-lg p-4 border-l-2 border-blue-200"
              >
                <div class="flex items-center space-x-2 mb-2">
                  <span class="font-semibold text-gray-900">{{
                    reply.user.name
                  }}</span>
                  <span class="text-sm text-gray-500">{{
                    formatDate(reply.created_at)
                  }}</span>

                </div>
                <p class="text-gray-700 whitespace-pre-wrap">{{ reply.content }}</p>
                <div v-if="canEdit(reply)" class="mt-2">
                  <button
                    @click="handleDelete(reply.id)"
                    :disabled="deletingIds.has(reply.id)"
                    class="text-sm text-red-600 hover:text-red-800 font-medium disabled:opacity-50"
                  >
                    {{ deletingIds.has(reply.id) ? "Deleting..." : "Delete" }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

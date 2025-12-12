<script setup lang="ts">
import type { Comment } from "~/types/comment";

const props = defineProps<{
  postId: number;
}>();

const emit = defineEmits<{
  commentAdded: [];
}>();

const { user } = useAuth();
const { createComment } = useComments();

const content = ref("");
const submitting = ref(false);
const error = ref("");

const handleSubmit = async () => {
  if (!content.value.trim()) return;

  error.value = "";
  submitting.value = true;

  try {
    await createComment({
      post_id: props.postId,
      content: content.value,
    });

    content.value = "";
    emit("commentAdded");
  } catch (err: any) {
    error.value = err.data?.message || "Failed to post comment";
  } finally {
    submitting.value = false;
  }
};
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add a Comment</h3>

    <div v-if="!user" class="text-center py-4">
      <p class="text-gray-600 mb-4">You need to be logged in to comment.</p>
      <NuxtLink
        to="/login"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
      >
        Login to Comment
      </NuxtLink>
    </div>

    <form v-else @submit.prevent="handleSubmit">
      <div
        v-if="error"
        class="mb-4 bg-red-50 border border-red-200 rounded-md p-3"
      >
        <p class="text-sm text-red-600">{{ error }}</p>
      </div>

      <textarea
        v-model="content"
        rows="4"
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border"
        placeholder="Write your comment..."
        required
      ></textarea>

      <div class="mt-4 flex justify-end">
        <button
          type="submit"
          :disabled="submitting || !content.trim()"
          class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed font-medium"
        >
          {{ submitting ? "Posting..." : "Post Comment" }}
        </button>
      </div>
    </form>
  </div>
</template>

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
  <div>
    <div
      v-if="!user"
      class="text-center py-6 bg-gray-50 rounded-lg border border-gray-200"
    >
      <svg
        class="w-12 h-12 text-gray-400 mx-auto mb-3"
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
      <p class="text-gray-600 mb-4">You need to be logged in to comment</p>
      <NuxtLink
        to="/login"
        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium"
      >
        Login to Comment
      </NuxtLink>
    </div>

    <form v-else @submit.prevent="handleSubmit">
      <div
        v-if="error"
        class="mb-4 bg-red-50 border border-red-200 rounded-lg p-3 flex items-center gap-2"
      >
        <svg
          class="w-5 h-5 text-red-600 flex-shrink-0"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
        <p class="text-sm text-red-600">{{ error }}</p>
      </div>

      <div class="flex items-start gap-3">
        <div
          class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-medium flex-shrink-0"
        >
          {{ user?.name?.charAt(0).toUpperCase() }}
        </div>
        <div class="flex-1">
          <textarea
            v-model="content"
            rows="4"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 px-4 py-3 border transition-colors"
            placeholder="Write your comment..."
            required
          ></textarea>
          <div class="mt-3 flex justify-end">
            <button
              type="submit"
              :disabled="submitting || !content.trim()"
              class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed font-medium transition-colors"
            >
              {{ submitting ? "Posting..." : "Post Comment" }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

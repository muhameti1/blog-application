<template>
  <div>
    <AppHeader />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Post</h1>
        <p class="text-gray-600">Share your thoughts with the community</p>
      </div>

      <div class="bg-white border border-gray-200 rounded-xl p-8">
        <form @submit.prevent="handleSubmit">
          <div
            v-if="error"
            class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4"
          >
            <p class="text-red-800 text-sm">{{ error }}</p>
          </div>

          <div class="space-y-6">
            <div>
              <label
                for="title"
                class="block text-sm font-medium text-gray-900 mb-2"
              >
                Title *
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                required
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 px-4 py-2.5 border transition-colors"
                placeholder="Enter a captivating title"
              />
            </div>

            <div>
              <label
                for="excerpt"
                class="block text-sm font-medium text-gray-900 mb-2"
              >
                Excerpt
              </label>
              <textarea
                id="excerpt"
                v-model="form.excerpt"
                rows="3"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 px-4 py-2.5 border transition-colors"
                placeholder="Brief description of your post (will be auto-generated if left empty)"
              ></textarea>
              <p class="mt-1 text-xs text-gray-500">
                Optional - A short summary that appears in post listings
              </p>
            </div>

            <div>
              <label
                for="content"
                class="block text-sm font-medium text-gray-900 mb-2"
              >
                Content *
              </label>
              <textarea
                id="content"
                v-model="form.content"
                rows="16"
                required
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 px-4 py-2.5 border transition-colors font-mono text-sm"
                placeholder="Write your post content here... (supports HTML)"
              ></textarea>
              <p class="mt-1 text-xs text-gray-500">
                You can use HTML tags for formatting
              </p>
            </div>

            <div>
              <label
                for="status"
                class="block text-sm font-medium text-gray-900 mb-2"
              >
                Publish Status
              </label>
              <select
                id="status"
                v-model="form.status"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 px-4 py-2.5 border transition-colors"
              >
                <option value="pending">Submit for Review</option>
                <option value="draft">Save as Draft</option>
              </select>
              <p class="mt-1 text-xs text-gray-500">
                <span v-if="form.status === 'pending'"
                  >Your post will be submitted for admin approval</span
                >
                <span v-else>Save as draft to continue editing later</span>
              </p>
            </div>
          </div>

          <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
            <NuxtLink
              to="/posts"
              class="inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
            >
              Cancel
            </NuxtLink>
            <button
              type="submit"
              :disabled="loading"
              class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <svg
                v-if="loading"
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
              {{ loading ? "Creating..." : "Create Post" }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const router = useRouter();
const { createPost } = usePosts();
const { canCreatePosts } = useAuth();

// Redirect if user can't create posts
if (!canCreatePosts.value) {
  router.push("/dashboard");
}

const form = reactive({
  title: "",
  content: "",
  excerpt: "",
  status: "pending" as "draft" | "pending",
});

const loading = ref(false);
const error = ref("");

const handleSubmit = async () => {
  loading.value = true;
  error.value = "";

  try {
    const post = await createPost({
      title: form.title,
      content: form.content,
      excerpt: form.excerpt || undefined,
      status: form.status,
    });

    router.push(`/posts/${post.slug}`);
  } catch (err: any) {
    error.value = err.data?.message || "Failed to create post";
  } finally {
    loading.value = false;
  }
};
</script>

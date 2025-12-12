<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Create New Post</h1>

        <form @submit.prevent="handleSubmit">
          <div v-if="error" class="mb-6 bg-red-50 p-4 rounded-md">
            <p class="text-red-800">{{ error }}</p>
          </div>

          <div class="space-y-6">
            <div>
              <label
                for="title"
                class="block text-sm font-medium text-gray-700"
              >
                Title *
              </label>
              <input
                id="title"
                v-model="form.title"
                type="text"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 border"
                placeholder="Enter post title"
              />
            </div>

            <div>
              <label
                for="excerpt"
                class="block text-sm font-medium text-gray-700"
              >
                Excerpt (optional)
              </label>
              <textarea
                id="excerpt"
                v-model="form.excerpt"
                rows="2"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 border"
                placeholder="Brief description of your post"
              ></textarea>
            </div>

            <div>
              <label
                for="content"
                class="block text-sm font-medium text-gray-700"
              >
                Content *
              </label>
              <textarea
                id="content"
                v-model="form.content"
                rows="15"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 border"
                placeholder="Write your post content here..."
              ></textarea>
            </div>

            <div>
              <label
                for="status"
                class="block text-sm font-medium text-gray-700"
              >
                Status
              </label>
              <select
                id="status"
                v-model="form.status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2 border"
              >
                <option value="pending">Pending (submit for approval)</option>
                <option value="draft">Draft (save for later)</option>
              </select>
            </div>
          </div>

          <div class="mt-8 flex justify-end space-x-4">
            <NuxtLink
              to="/posts"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancel
            </NuxtLink>
            <button
              type="submit"
              :disabled="loading"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50"
            >
              {{ loading ? "Creating..." : "Create Post" }}
            </button>
          </div>
        </form>
      </div>
    </div>
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

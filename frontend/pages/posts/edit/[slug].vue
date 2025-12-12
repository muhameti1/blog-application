<script setup lang="ts">
definePageMeta({
  middleware: "auth",
});

const route = useRoute();
const router = useRouter();
const { getPost, updatePost, deletePost } = usePosts();

const slug = route.params.slug as string;
const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const error = ref("");
const post = ref<any>(null);

const form = reactive({
  title: "",
  content: "",
  excerpt: "",
  status: "draft" as "draft" | "pending",
});

onMounted(async () => {
  try {
    post.value = await getPost(slug);
    form.title = post.value.title;
    form.content = post.value.content;
    form.excerpt = post.value.excerpt || "";
    form.status = post.value.status === "approved" ? "pending" : post.value.status;
  } catch (err: any) {
    error.value = err.data?.message || "Failed to load post";
  } finally {
    loading.value = false;
  }
});

const handleSubmit = async () => {
  if (!post.value) return;

  error.value = "";
  saving.value = true;

  try {
    await updatePost(post.value.id, {
      title: form.title,
      content: form.content,
      excerpt: form.excerpt || undefined,
      status: form.status,
    });

    router.push(`/posts/${slug}`);
  } catch (err: any) {
    error.value = err.data?.message || "Failed to update post";
  } finally {
    saving.value = false;
  }
};

const handleDelete = async () => {
  if (!post.value) return;
  if (!confirm("Are you sure you want to delete this post?")) return;

  deleting.value = true;
  error.value = "";

  try {
    await deletePost(post.value.id);
    router.push("/posts/my-posts");
  } catch (err: any) {
    error.value = err.data?.message || "Failed to delete post";
    deleting.value = false;
  }
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div v-if="loading" class="text-center">
        <p class="text-gray-600">Loading post...</p>
      </div>

      <div v-else-if="error && !post" class="text-center">
        <p class="text-red-600">{{ error }}</p>
        <NuxtLink
          to="/posts"
          class="mt-4 inline-block text-blue-600 hover:text-blue-800"
        >
          Back to Posts
        </NuxtLink>
      </div>

      <div v-else class="bg-white rounded-lg shadow-md p-8">
        <div class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Edit Post</h1>
          <button
            @click="handleDelete"
            :disabled="deleting"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ deleting ? "Deleting..." : "Delete Post" }}
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-md p-4">
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">
              Title
            </label>
            <input
              type="text"
              id="title"
              v-model="form.title"
              required
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border"
              placeholder="Enter post title"
            />
          </div>

          <div>
            <label for="excerpt" class="block text-sm font-medium text-gray-700">
              Excerpt (Optional)
            </label>
            <textarea
              id="excerpt"
              v-model="form.excerpt"
              rows="2"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border"
              placeholder="Brief description of your post"
            ></textarea>
          </div>

          <div>
            <label for="content" class="block text-sm font-medium text-gray-700">
              Content
            </label>
            <textarea
              id="content"
              v-model="form.content"
              required
              rows="12"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border"
              placeholder="Write your post content..."
            ></textarea>
          </div>

          <div>
            <label for="status" class="block text-sm font-medium text-gray-700">
              Status
            </label>
            <select
              id="status"
              v-model="form.status"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border"
            >
              <option value="draft">Draft</option>
              <option value="pending">Submit for Review</option>
            </select>
          </div>

          <div class="flex gap-4">
            <button
              type="submit"
              :disabled="saving"
              class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed font-medium"
            >
              {{ saving ? "Saving..." : "Update Post" }}
            </button>
            <NuxtLink
              :to="`/posts/${slug}`"
              class="px-6 py-3 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 font-medium text-center"
            >
              Cancel
            </NuxtLink>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

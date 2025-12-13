<template>
  <div>
    <AppHeader />

    <!-- Hero Section -->
    <section class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-3xl mx-auto">
          <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
            Share Your Stories with the World
          </h1>
          <p class="text-xl text-gray-600 mb-8">
            A modern blog platform where writers and readers come together.
            Write, share, and discover amazing content.
          </p>

          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <NuxtLink
              v-if="!isAuthenticated"
              to="/register"
              class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 transition-colors"
            >
              Get Started
            </NuxtLink>
            <NuxtLink
              to="/posts"
              class="inline-flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
            >
              Explore Posts
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            Everything You Need to Blog
          </h2>
          <p class="text-lg text-gray-600">
            Powerful features designed for modern content creators
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div
            class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg transition-shadow"
          >
            <div
              class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4"
            >
              <span class="text-2xl">✍️</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">
              Create & Publish
            </h3>
            <p class="text-gray-600">
              Write and publish blog posts with our intuitive editor. Share your
              expertise with the world.
            </p>
          </div>

          <div
            class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg transition-shadow"
          >
            <div
              class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4"
            >
              <span class="text-2xl">💬</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">
              Engage & Discuss
            </h3>
            <p class="text-gray-600">
              Comment on posts, like content, and engage in meaningful
              discussions with the community.
            </p>
          </div>

          <div
            class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-lg transition-shadow"
          >
            <div
              class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-4"
            >
              <span class="text-2xl">🔔</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">
              Stay Updated
            </h3>
            <p class="text-gray-600">
              Subscribe to our newsletter and never miss a new post. Get
              real-time updates on content you care about.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Recent Posts Section -->
    <section class="py-20 bg-white border-t border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
          <div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Latest Posts</h2>
            <p class="text-gray-600">
              Discover the newest content from our community
            </p>
          </div>
          <NuxtLink
            to="/posts"
            class="text-primary-600 hover:text-primary-700 font-medium flex items-center gap-2"
          >
            View All
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
                d="M9 5l7 7-7 7"
              />
            </svg>
          </NuxtLink>
        </div>

        <div v-if="loading" class="text-center py-12">
          <div
            class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
          ></div>
        </div>

        <div
          v-else-if="recentPosts && recentPosts.length > 0"
          class="grid grid-cols-1 md:grid-cols-3 gap-8"
        >
          <article
            v-for="post in recentPosts"
            :key="post.id"
            class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow"
          >
            <NuxtLink :to="`/posts/${post.slug}`" class="block p-6">
              <div class="flex items-center gap-3 mb-4">
                <div
                  class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-medium"
                >
                  {{ post.user?.name?.charAt(0).toUpperCase() }}
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ post.user?.name }}
                  </p>
                  <p class="text-xs text-gray-500">
                    {{ formatDate(post.published_at || post.created_at) }}
                  </p>
                </div>
              </div>

              <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
                {{ post.title }}
              </h3>
              <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                {{ post.excerpt }}
              </p>

              <div
                class="flex items-center justify-between text-sm text-gray-500"
              >
                <div class="flex items-center gap-4">
                  <span class="flex items-center gap-1">
                    <svg
                      class="w-4 h-4"
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
                  <span class="flex items-center gap-1">
                    <svg
                      class="w-4 h-4"
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
            </NuxtLink>
          </article>
        </div>

        <div v-else class="text-center py-12">
          <p class="text-gray-500">No posts available yet.</p>
        </div>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 border-t border-gray-200">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Stay in the Loop</h2>
        <p class="text-lg text-gray-600 mb-8">
          Subscribe to our newsletter and get notified when new posts are
          published
        </p>
        <NewsletterSubscribe />
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup lang="ts">
const { isAuthenticated, initAuth } = useAuth();
const { getPosts } = usePosts();

const loading = ref(true);
const recentPosts = ref<any[]>([]);

const loadRecentPosts = async () => {
  try {
    const posts = await getPosts();
    recentPosts.value = posts ? posts.slice(0, 3) : [];
  } catch (err) {
    console.error("Failed to load recent posts:", err);
    recentPosts.value = [];
  } finally {
    loading.value = false;
  }
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
};

onMounted(() => {
  initAuth();
  loadRecentPosts();
});
</script>

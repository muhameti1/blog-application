<script setup lang="ts">
const props = defineProps<{
  postId: number;
  initialLiked?: boolean;
  initialCount?: number;
}>();

const { user } = useAuth();
const { toggleLike, checkLikeStatus } = useLikes();

const liked = ref(props.initialLiked || false);
const likesCount = ref(props.initialCount || 0);
const loading = ref(false);

onMounted(async () => {
  // Load current like status
  if (user.value) {
    try {
      const status = await checkLikeStatus(props.postId);
      liked.value = status.liked;
      likesCount.value = status.likes_count;
    } catch (err) {
      // If error, use initial values
      likesCount.value = props.initialCount || 0;
    }
  } else {
    likesCount.value = props.initialCount || 0;
  }
});

const handleToggle = async () => {
  if (!user.value) {
    // Redirect to login or show message
    navigateTo("/login");
    return;
  }

  loading.value = true;

  try {
    const result = await toggleLike(props.postId);
    liked.value = result.liked;
    likesCount.value = result.likes_count;
  } catch (err: any) {
    console.error("Failed to toggle like:", err);
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <button
    @click="handleToggle"
    :disabled="loading"
    class="inline-flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
    :class="[
      liked
        ? 'bg-red-50 text-red-600 hover:bg-red-100 border-2 border-red-200'
        : 'bg-gray-50 text-gray-600 hover:bg-gray-100 border-2 border-gray-200',
    ]"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      :class="[
        'w-5 h-5 transition-transform duration-200',
        loading ? 'animate-pulse' : '',
        liked ? 'scale-110' : '',
      ]"
      :fill="liked ? 'currentColor' : 'none'"
      viewBox="0 0 24 24"
      stroke="currentColor"
      stroke-width="2"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
      />
    </svg>
    <span class="font-semibold">{{ likesCount }}</span>
  </button>
</template>

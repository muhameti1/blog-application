import type { Ref } from "vue";

export const useLikes = () => {
  const config = useRuntimeConfig();
  const baseURL = config.public.apiBase;
  const { getAuthHeaders } = useAuth();

  /**
   * Toggle like on a post.
   */
  const toggleLike = async (
    postId: number
  ): Promise<{
    liked: boolean;
    likes_count: number;
  }> => {
    const response = await $fetch<{
      liked: boolean;
      likes_count: number;
    }>(`${baseURL}/posts/${postId}/like`, {
      method: "POST",
      headers: getAuthHeaders(),
    });
    return response;
  };

  /**
   * Check if user has liked a post and get like count.
   */
  const checkLikeStatus = async (
    postId: number
  ): Promise<{
    liked: boolean;
    likes_count: number;
  }> => {
    const response = await $fetch<{
      liked: boolean;
      likes_count: number;
    }>(`${baseURL}/posts/${postId}/like-status`, {
      headers: getAuthHeaders(),
    });
    return response;
  };

  return {
    toggleLike,
    checkLikeStatus,
  };
};

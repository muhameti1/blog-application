import type { Post, PostListItem } from "~/types/post";

interface CreatePostData {
  title: string;
  content: string;
  excerpt?: string;
  status?: "draft" | "pending";
}

interface UpdatePostData {
  title?: string;
  content?: string;
  excerpt?: string;
  status?: "draft" | "pending";
}

export const usePosts = () => {
  const config = useRuntimeConfig();
  const baseURL = config.public.apiBase;
  const { getAuthHeaders } = useAuth();

  /**
   * Get all published posts (public).
   */
  const getPosts = async (): Promise<PostListItem[]> => {
    const response = await $fetch<{ posts: PostListItem[] }>(
      `${baseURL}/posts`
    );
    return response.posts;
  };

  /**
   * Get a single post by slug.
   */
  const getPost = async (slug: string): Promise<Post> => {
    const response = await $fetch<{ post: Post }>(`${baseURL}/posts/${slug}`, {
      headers: getAuthHeaders(),
    });
    return response.post;
  };

  /**
   * Create a new post (authenticated).
   */
  const createPost = async (data: CreatePostData): Promise<Post> => {
    const response = await $fetch<{ post: Post }>(`${baseURL}/posts`, {
      method: "POST",
      body: data,
      headers: getAuthHeaders(),
    });
    return response.post;
  };

  /**
   * Update a post (authenticated).
   */
  const updatePost = async (
    id: number,
    data: UpdatePostData
  ): Promise<Post> => {
    const response = await $fetch<{ post: Post }>(`${baseURL}/posts/${id}`, {
      method: "PUT",
      body: data,
      headers: getAuthHeaders(),
    });
    return response.post;
  };

  /**
   * Delete a post (authenticated).
   */
  const deletePost = async (id: number): Promise<void> => {
    await $fetch(`${baseURL}/posts/${id}`, {
      method: "DELETE",
      headers: getAuthHeaders(),
    });
  };

  /**
   * Get user's own posts (authenticated).
   */
  const getMyPosts = async (): Promise<PostListItem[]> => {
    const response = await $fetch<{ posts: PostListItem[] }>(
      `${baseURL}/my-posts`,
      {
        headers: getAuthHeaders(),
      }
    );
    return response.posts;
  };

  /**
   * Approve a post (admin only).
   */
  const approvePost = async (id: number): Promise<Post> => {
    const response = await $fetch<{ post: Post }>(
      `${baseURL}/posts/${id}/approve`,
      {
        method: "POST",
        headers: getAuthHeaders(),
      }
    );
    return response.post;
  };

  /**
   * Reject a post (admin only).
   */
  const rejectPost = async (id: number): Promise<Post> => {
    const response = await $fetch<{ post: Post }>(
      `${baseURL}/posts/${id}/reject`,
      {
        method: "POST",
        headers: getAuthHeaders(),
      }
    );
    return response.post;
  };

  /**
   * Get all posts (admin only).
   */
  const getAdminPosts = async (): Promise<PostListItem[]> => {
    const response = await $fetch<{ posts: PostListItem[] }>(
      `${baseURL}/admin/posts`,
      {
        headers: getAuthHeaders(),
      }
    );
    return response.posts;
  };

  return {
    getPosts,
    getPost,
    createPost,
    updatePost,
    deletePost,
    getMyPosts,
    approvePost,
    rejectPost,
    getAdminPosts,
  };
};

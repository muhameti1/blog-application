import type { Comment, CreateCommentData, UpdateCommentData } from "~/types/comment";

export const useComments = () => {
  const config = useRuntimeConfig();
  const baseURL = config.public.apiBase;
  const { getAuthHeaders } = useAuth();

  /**
   * Get all comments for a post.
   */
  const getPostComments = async (postId: number): Promise<Comment[]> => {
    const response = await $fetch<{ comments: Comment[] }>(
      `${baseURL}/posts/${postId}/comments`,
      {
        headers: getAuthHeaders(),
      }
    );
    return response.comments;
  };

  /**
   * Create a new comment.
   */
  const createComment = async (data: CreateCommentData): Promise<Comment> => {
    const response = await $fetch<{ comment: Comment }>(
      `${baseURL}/comments`,
      {
        method: "POST",
        body: data,
        headers: getAuthHeaders(),
      }
    );
    return response.comment;
  };

  /**
   * Update a comment.
   */
  const updateComment = async (
    id: number,
    data: UpdateCommentData
  ): Promise<Comment> => {
    const response = await $fetch<{ comment: Comment }>(
      `${baseURL}/comments/${id}`,
      {
        method: "PUT",
        body: data,
        headers: getAuthHeaders(),
      }
    );
    return response.comment;
  };

  /**
   * Delete a comment.
   */
  const deleteComment = async (id: number): Promise<void> => {
    await $fetch(`${baseURL}/comments/${id}`, {
      method: "DELETE",
      headers: getAuthHeaders(),
    });
  };

  /**
   * Get user's own comments.
   */
  const getMyComments = async (): Promise<Comment[]> => {
    const response = await $fetch<{ comments: Comment[] }>(
      `${baseURL}/my-comments`,
      {
        headers: getAuthHeaders(),
      }
    );
    return response.comments;
  };

  /**
   * Approve a comment (admin only).
   */
  const approveComment = async (id: number): Promise<Comment> => {
    const response = await $fetch<{ comment: Comment }>(
      `${baseURL}/comments/${id}/approve`,
      {
        method: "POST",
        headers: getAuthHeaders(),
      }
    );
    return response.comment;
  };

  /**
   * Reject a comment (admin only).
   */
  const rejectComment = async (id: number): Promise<Comment> => {
    const response = await $fetch<{ comment: Comment }>(
      `${baseURL}/comments/${id}/reject`,
      {
        method: "POST",
        headers: getAuthHeaders(),
      }
    );
    return response.comment;
  };

  /**
   * Get all comments (admin only).
   */
  const getAdminComments = async (): Promise<Comment[]> => {
    const response = await $fetch<{ comments: Comment[] }>(
      `${baseURL}/admin/comments`,
      {
        headers: getAuthHeaders(),
      }
    );
    return response.comments;
  };

  return {
    getPostComments,
    createComment,
    updateComment,
    deleteComment,
    getMyComments,
    approveComment,
    rejectComment,
    getAdminComments,
  };
};

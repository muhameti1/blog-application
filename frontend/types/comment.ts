export interface Comment {
  id: number;
  post_id: number;
  parent_id: number | null;
  content: string;
  user: {
    id: number;
    name: string;
  };
  replies?: Comment[];
  created_at: string;
  updated_at: string;
}

export interface CreateCommentData {
  post_id: number;
  parent_id?: number;
  content: string;
}

export interface UpdateCommentData {
  content: string;
}

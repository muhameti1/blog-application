export interface Post {
  id: number;
  title: string;
  slug: string;
  content: string;
  excerpt: string;
  status: "draft" | "pending" | "approved" | "rejected";
  published_at: string | null;
  approved_at: string | null;
  created_at: string;
  updated_at: string;
  user?: {
    id: number;
    name: string;
    email: string;
    role: string;
  };
  approver?: {
    id: number;
    name: string;
    email: string;
    role: string;
  };
  comments_count?: number;
  likes_count?: number;
}

export interface PostListItem {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  status: string;
  published_at: string | null;
  created_at: string;
  user?: {
    id: number;
    name: string;
    role: string;
  };
  comments_count?: number;
  likes_count?: number;
}

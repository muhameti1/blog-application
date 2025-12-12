<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostListResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(
        private readonly PostService $postService
    ) {}

    /**
     * Get all published posts (public).
     */
    public function index(): JsonResponse
    {
        $posts = $this->postService->getAllPublishedPosts();

        return response()->json([
            'posts' => PostListResource::collection($posts),
        ]);
    }

    /**
     * Get a single post by slug (public if approved).
     */
    public function show(string $slug): JsonResponse
    {
        $post = $this->postService->getPostBySlug($slug);

        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $this->authorize('view', $post);

        return response()->json([
            'post' => new PostResource($post),
        ]);
    }

    /**
     * Create a new post (authenticated).
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $post = $this->postService->createPost(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Post created successfully',
            'post' => new PostResource($post),
        ], 201);
    }

    /**
     * Update a post (author or admin).
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $updatedPost = $this->postService->updatePost(
            $post,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => new PostResource($updatedPost),
        ]);
    }

    /**
     * Delete a post (author or admin).
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $this->postService->deletePost($post);

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }

    /**
     * Approve a post (admin only).
     */
    public function approve(Post $post, Request $request): JsonResponse
    {
        $this->authorize('approve', $post);

        $approvedPost = $this->postService->approvePost($post, $request->user());

        return response()->json([
            'message' => 'Post approved successfully',
            'post' => new PostResource($approvedPost),
        ]);
    }

    /**
     * Reject a post (admin only).
     */
    public function reject(Post $post, Request $request): JsonResponse
    {
        $this->authorize('reject', $post);

        $rejectedPost = $this->postService->rejectPost($post, $request->user());

        return response()->json([
            'message' => 'Post rejected successfully',
            'post' => new PostResource($rejectedPost),
        ]);
    }

    /**
     * Get user's own posts (authenticated).
     */
    public function myPosts(Request $request): JsonResponse
    {
        $posts = $this->postService->getUserPosts($request->user());

        return response()->json([
            'posts' => PostListResource::collection($posts),
        ]);
    }
}

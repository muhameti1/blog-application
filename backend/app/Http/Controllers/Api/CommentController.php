<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        private readonly CommentService $commentService
    ) {}

    /**
     * Get comments for a post.
     */
    public function index(Post $post): JsonResponse
    {
        $comments = $this->commentService->getPostComments($post);

        return response()->json([
            'comments' => CommentResource::collection($comments),
        ]);
    }

    /**
     * Create a new comment.
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $this->authorize('create', Comment::class);

        $comment = $this->commentService->createComment(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => new CommentResource($comment),
        ], 201);
    }

    /**
     * Update a comment.
     */
    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $updatedComment = $this->commentService->updateComment(
            $comment,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => new CommentResource($updatedComment),
        ]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $this->commentService->deleteComment($comment);

        return response()->json([
            'message' => 'Comment deleted successfully',
        ]);
    }

    /**
     * Get user's comments.
     */
    public function myComments(Request $request): JsonResponse
    {
        $comments = $this->commentService->getUserComments($request->user());

        return response()->json([
            'comments' => CommentResource::collection($comments),
        ]);
    }

    /**
     * Get all comments (admin only).
     */
    public function adminComments(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Comment::class);

        $comments = $this->commentService->getAllComments();

        return response()->json([
            'comments' => CommentResource::collection($comments),
        ]);
    }

    /**
     * Approve a comment (admin only).
     */
    public function approve(Comment $comment, Request $request): JsonResponse
    {
        $this->authorize('approve', $comment);

        $approvedComment = $this->commentService->approveComment($comment, $request->user());

        return response()->json([
            'message' => 'Comment approved successfully',
            'comment' => new CommentResource($approvedComment),
        ]);
    }

    /**
     * Reject a comment (admin only).
     */
    public function reject(Comment $comment, Request $request): JsonResponse
    {
        $this->authorize('reject', $comment);

        $rejectedComment = $this->commentService->rejectComment($comment, $request->user());

        return response()->json([
            'message' => 'Comment rejected successfully',
            'comment' => new CommentResource($rejectedComment),
        ]);
    }
}

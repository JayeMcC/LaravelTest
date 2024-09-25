<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;  // Include the trait to use the authorize method

    /**
     * List all comments for a specific post.
     *
     * @param  Post  $post
     * @return LengthAwarePaginator
     */
    public function index(Post $post): LengthAwarePaginator
    {
        return $post->comments()->paginate(10);
    }

    /**
     * Show a specific comment for a post.
     *
     * @param  Post  $post
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function show(Post $post, Comment $comment): JsonResponse
    {
        return response()->json($comment, 200);
    }

    /**
     * Store a new comment for a post.
     *
     * @param  CommentRequest  $request
     * @param  Post  $post
     * @return JsonResponse
     */
    public function store(CommentRequest $request, Post $post): JsonResponse
    {
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($comment, 201);
    }

    /**
     * Update an existing comment for a post.
     *
     * @param  CommentRequest  $request
     * @param  Post  $post
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function update(CommentRequest $request, Post $post, Comment $comment): JsonResponse
    {
        $this->authorize('update', $comment);

        $comment->update($request->validated());

        return response()->json($comment, 200);
    }

    /**
     * Delete a specific comment for a post.
     *
     * @param  Post  $post
     * @param  Comment  $comment
     * @return JsonResponse
     */
    public function destroy(Post $post, Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 204);
    }
}

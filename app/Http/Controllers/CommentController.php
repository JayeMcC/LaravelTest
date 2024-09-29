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
use Illuminate\Http\RedirectResponse;

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
     * Store a new comment for a post and refresh the post page.
     *
     * @param  CommentRequest  $request
     * @param  Post  $post
     * @return RedirectResponse
     */
    public function store(CommentRequest $request, Post $post): RedirectResponse
    {
        // Create the new comment
        $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        // Redirect back to the post page
        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Comment added successfully.');
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
     * Delete a specific comment for a post and redirect back to the post page.
     *
     * @param  Post  $post
     * @param  Comment  $comment
     * @return RedirectResponse
     */
    public function destroy(Post $post, Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        // Delete the comment
        $comment->delete();

        // Redirect back to the post page with a success message
        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Comment deleted successfully.');
    }
}

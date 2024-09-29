<?php

namespace App\Http\Controllers\API\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

# Implicitly requested by part 4, Features
# "Implement pagination for list endpoints (posts, comments, users)"

class CommentController extends Controller
{
  /**
   * List all comments for a specific post.
   *
   * @param  Post  $post
   * @return JsonResponse
   */
  public function index(Post $post): JsonResponse
  {
    $comments = $post->comments()->paginate(10);
    return response()->json($comments, 200);
  }

  /**
   * Show a specific comment.
   *
   * @param  Comment  $comment
   * @return JsonResponse
   */
  public function show(Comment $comment): JsonResponse
  {
    return response()->json($comment, 200);
  }

  /**
   * Store a new comment for a post and return the comment in JSON format.
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
   * Update an existing comment for a post and return it in JSON format.
   *
   * @param  CommentRequest  $request
   * @param  Comment  $comment
   * @return JsonResponse
   */
  public function update(CommentRequest $request, Comment $comment): JsonResponse
  {
    $this->authorize('update', $comment);
    $comment->update($request->validated());
    return response()->json($comment, 200);
  }

  /**
   * Delete a specific comment for a post
   *
   * @param  Comment  $comment
   * @return Response
   */
  public function destroy(Comment $comment): Response
  {
    $this->authorize('delete', $comment);
    $comment->delete();
    return response()->noContent();
  }
}

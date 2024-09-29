<?php

namespace App\Http\Controllers\Web\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
  /**
   * Store a new comment for a post and refresh the post page.
   *
   * @param  CommentRequest  $request
   * @param  Post  $post
   * @return RedirectResponse
   */
  public function store(CommentRequest $request, Post $post): RedirectResponse
  {
    $post->comments()->create([
      'content' => $request->content,
      'user_id' => $request->user()->id,
    ]);

    return redirect()->route('posts.show', $post->id)
      ->with('success', 'Comment added successfully.');
  }

  /**
   * Show the form to edit an existing comment.
   *
   * @param  Post  $post
   * @param  Comment  $comment
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function edit(Post $post, Comment $comment)
  {
    $this->authorize('update', $comment);

    return view('comments.edit', compact('post', 'comment'));
  }

  /**
   * Update an existing comment for a post.
   *
   * @param  CommentRequest  $request
   * @param  Post  $post
   * @param  Comment  $comment
   * @return RedirectResponse
   */
  public function update(CommentRequest $request, Post $post, Comment $comment): RedirectResponse
  {
    $this->authorize('update', $comment);
    $comment->update($request->validated());
    return redirect()->route('posts.show', $post->id)
      ->with('success', 'Comment updated successfully.');
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

    $comment->delete();

    return redirect()->route('posts.show', $post->id)
      ->with('success', 'Comment deleted successfully.');
  }
}

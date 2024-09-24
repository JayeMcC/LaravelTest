<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // GET /api/posts/{post}/comments (list all comments for a post)
    public function index(Post $post)
    {
        return $post->comments()->paginate(10); // Paginate comments
    }

    // GET /api/posts/{post}/comments/{comment} (get a specific comment)
    public function show(Post $post, Comment $comment)
    {
        return $comment;
    }

    // POST /api/posts/{post}/comments (create a new comment)
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id, // Authenticated user
        ]);

        return response()->json($comment, 201);
    }

    // PATCH /api/posts/{post}/comments/{comment} (update an existing comment)
    public function update(Request $request, Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json($comment, 200);
    }

    // DELETE /api/posts/{post}/comments/{comment} (delete a comment)
    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 204);
    }
}

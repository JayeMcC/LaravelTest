<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // GET /api/posts/{post}/comments (list all comments for a post)
    public function index(Post $post)
    {
        return $post->comments()->paginate(10);
    }

    // GET /api/posts/{post}/comments/{comment}
    public function show(Post $post, Comment $comment)
    {
        return response()->json($comment, 200); // 200 OK
    }

    // POST /api/posts/{post}/comments
    public function store(CommentRequest $request, Post $post)
    {
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($comment, 201); // 201 Created
    }

    // PATCH /api/posts/{post}/comments/{comment}
    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update($request->validated());

        return response()->json($comment, 200); // 200 OK
    }

    // DELETE /api/posts/{post}/comments/{comment}
    public function destroy(Post $post, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(null, 204); // 204 No Content
    }
}

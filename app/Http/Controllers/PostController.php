<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /api/posts (list all posts with pagination)
    public function index(Request $request)
    {
        $posts = Post::paginate(10);
        return response()->json($posts, 200);
    }

    // GET /api/posts/{id}
    public function show($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        return response()->json($post, 200);
    }

    // POST /api/posts
    public function store(PostRequest $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($post, 201); // 201 Created
    }

    // PATCH /api/posts/{id}
    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        $this->authorize('update', $post);

        $post->update($request->only(['title', 'content']));

        return response()->json($post, 200); // 200 OK
    }

    // DELETE /api/posts/{id}
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204); // 204 No Content
    }
}

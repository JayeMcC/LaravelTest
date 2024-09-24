<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /api/posts (list all posts with pagination)
    public function index(Request $request)
    {
        // Paginate posts with 10 posts per page
        $posts = Post::paginate(10);
        return response()->json($posts);
    }

    // GET /api/posts/{id}
    public function show($id)
    {
        return Post::findOrFail($id);
    }

    // POST /api/posts
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($post, 201);
    }

    // PATCH /api/posts/{id}
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $post->update($request->only(['title', 'content']));

        return response()->json($post, 200);
    }

    // DELETE /api/posts/{id}
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}

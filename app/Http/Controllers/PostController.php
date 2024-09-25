<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{

    use AuthorizesRequests;

    /**
     * List all posts with pagination.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $posts = Post::paginate(10);
        return response()->json($posts, 200);
    }

    /**
     * Show a specific post by ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        return response()->json($post, 200);
    }

    /**
     * Create a new post.
     *
     * @param  PostRequest  $request
     * @return JsonResponse
     */
    public function store(PostRequest $request): JsonResponse
    {
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($post, 201); // 201 Created
    }

    /**
     * Update an existing post by ID.
     *
     * @param  PostRequest  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(PostRequest $request, int $id): JsonResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        $this->authorize('update', $post);

        $post->update($request->only(['title', 'content']));

        return response()->json($post, 200); // 200 OK
    }

    /**
     * Delete a specific post by ID.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
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

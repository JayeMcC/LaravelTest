<?php

namespace App\Http\Controllers\API\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
  /**
   * Return a list of posts in JSON format.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function index(Request $request): JsonResponse
  {
    $posts = Post::paginate(10);
    return response()->json($posts, 200);
  }

  /**
   * Return a specific post in JSON format.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function show(int $id): JsonResponse
  {
    $post = Post::find($id);

    if (!$post) {
      return response()->json(['error' => 'Post not found'], 404);
    }

    return response()->json($post, 200);
  }

  /**
   * Store a new post and return the post in JSON format.
   *
   * @param PostRequest $request
   * @return JsonResponse
   */
  public function store(StorePostRequest  $request): JsonResponse
  {
    $post = Post::create([
      'title' => $request->title,
      'content' => $request->content,
      'user_id' => $request->user()->id,
    ]);

    return response()->json($post, 201);
  }

  /**
   * Update an existing post.
   *
   * @param UpdatePostRequest $request
   * @param int $id
   * @return JsonResponse
   */
  public function update(UpdatePostRequest $request, int $id): JsonResponse
  {
    $post = Post::find($id);

    if (!$post) {
      return response()->json(['error' => 'Post not found'], 404);
    }

    $this->authorize('update', $post);

    $post->update($request->validated());

    return response()->json($post, 200);
  }

  /**
   * Delete a specific post and return a success message in JSON format.
   *
   * @param int $id
   * @return JsonResponse
   */
  public function destroy(int $id): JsonResponse
  {
    $post = Post::find($id);

    if (!$post) {
      return response()->json(['error' => 'Post not found'], 404);
    }

    $this->authorize('delete', $post);

    $post->delete();

    return response()->json(['message' => 'Post deleted successfully'], 200);
  }
}

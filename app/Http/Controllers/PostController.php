<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
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
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(int $id)
    {
        // Retrieve the post by its ID
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            abort(404, 'Post not found');
        }

        // Pass the post to the view
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for creating a new post.
     *
     * @return Response
     */
    public function create(): Response
    {
        return response()->view('posts.create');
    }

    /**
     * Store a new post and redirect to the created post.
     *
     * @param  PostRequest  $request
     * @return RedirectResponse
     */
    public function store(PostRequest $request): RedirectResponse
    {
        // Create the post
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        // Redirect to the newly created post
        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Post created successfully!');
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
     * Delete a specific post and redirect to the homepage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $post = Post::find($id);

        if (!$post) {
            return redirect()->route('home')->with('error', 'Post not found.');
        }

        // Authorize the action
        $this->authorize('delete', $post);

        // Delete the post
        $post->delete();

        // Redirect back to the user's homepage with a success message
        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }
}

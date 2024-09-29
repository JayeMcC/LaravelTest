<?php

namespace App\Http\Controllers\Web\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
  /**
   * List all posts with pagination.
   *
   * @param  Request  $request
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function index(Request $request)
  {
    $posts = Post::paginate(10);
    return view('posts.index', compact('posts'));
  }

  /**
   * Show a specific post by ID.
   *
   * @param  int  $id
   * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
   */
  public function show(int $id)
  {
    $post = Post::find($id);

    if (!$post) {
      abort(404, 'Post not found');
    }

    return view('posts.show', compact('post'));
  }

  /**
   * Show the form for creating a new post.
   *
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function create()
  {
    return view('posts.create');
  }

  /**
   * Store a new post and redirect to the created post.
   *
   * @param  PostRequest  $request
   * @return RedirectResponse
   */
  public function store(PostRequest $request): RedirectResponse
  {
    $post = Post::create([
      'title' => $request->title,
      'content' => $request->content,
      'user_id' => $request->user()->id,
    ]);

    return redirect()->route('posts.show', $post->id)
      ->with('success', 'Post created successfully!');
  }

  /**
   * Show the form to edit an existing post.
   *
   * @param  int  $id
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function edit(int $id)
  {
    $post = Post::find($id);

    if (!$post) {
      abort(404, 'Post not found');
    }

    $this->authorize('update', $post);

    return view('posts.edit', compact('post'));
  }

  /**
   * Update an existing post by ID and redirect to the updated post.
   *
   * @param  PostRequest  $request
   * @param  int  $id
   * @return RedirectResponse
   */
  public function update(PostRequest $request, int $id): RedirectResponse
  {
    $post = Post::find($id);

    if (!$post) {
      return redirect()->route('home')->with('error', 'Post not found.');
    }

    $this->authorize('update', $post);

    $post->update($request->only(['title', 'content']));

    return redirect()->route('posts.show', $post->id)
      ->with('success', 'Post updated successfully!');
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

    $this->authorize('delete', $post);

    $post->delete();

    return redirect()->route('home')->with('success', 'Post deleted successfully.');
  }
}

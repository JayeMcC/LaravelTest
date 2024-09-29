<?php

namespace App\Http\Controllers\Web\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
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
   * Show a specific post.
   *
   * @param  Post  $post
   * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
   */
  public function show(Post $post)
  {
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
  public function store(StorePostRequest $request): RedirectResponse
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
   * @param  Post  $post
   * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
   */
  public function edit(Post $post)
  {
    $this->authorize('update', $post);
    return view('posts.edit', compact('post'));
  }

  /**
   * Update an existing post and redirect to the updated post.
   *
   * @param  UpdatePostRequest  $request
   * @param  Post|null  $post
   * @return RedirectResponse
   */
  public function update(UpdatePostRequest $request, Post $post = null): RedirectResponse
  {
    if (!$post) {
      return redirect()->route('home')->with('error', 'Post not found.');
    }

    $this->authorize('update', $post);
    $post->update($request->validated());

    return redirect()->route('posts.show', $post->id)
      ->with('success', 'Post updated successfully!');
  }

  /**
   * Delete a specific post and redirect to the homepage.
   *
   * @param  Post|null  $post
   * @return RedirectResponse
   */
  public function destroy(Post $post = null): RedirectResponse
  {
    if (!$post) {
      return redirect()->route('home')->with('error', 'Post not found.');
    }

    $this->authorize('delete', $post);
    $post->delete();
    return redirect()->route('home')->with('success', 'Post deleted successfully.');
  }
}

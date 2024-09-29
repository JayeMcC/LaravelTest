<?php

namespace App\Http\Controllers\API\Content;

use App\Http\Controllers\Controller;
use App\Models\Post;

class HomeController extends Controller
{
  /**
   * Show the application dashboard with the latest 10 posts.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $posts = Post::latest()->take(10)->get();

    return view('home', compact('posts'));
  }
}

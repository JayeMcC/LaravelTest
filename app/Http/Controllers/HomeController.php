<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('welcome');
    }

    /**
     * Show the guest homepage containing the project readme
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        // Load the README file
        $readmePath = base_path('README.md');
        $readme = file_exists($readmePath) ? file_get_contents($readmePath) : 'README file not found.';

        // Set up the Markdown environment
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        // Instantiate the MarkdownConverter with the environment
        $converter = new MarkdownConverter($environment);

        // Convert Markdown to HTML using the new method
        $readmeHtml = $converter->convert($readme)->getContent();

        return view('welcome', ['readme' => $readmeHtml]);
    }

    /**
     * Show the application dashboard with the latest 10 posts.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Retrieve the latest 10 posts
        $posts = Post::latest()->take(10)->get();

        return view('home', compact('posts'));
    }
}

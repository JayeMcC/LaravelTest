<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class WelcomeController extends Controller
{
  /**
   * Show the welcome page containing the project readme
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function welcome()
  {
    $readmePath = base_path('README.md');
    $readme = file_exists($readmePath) ? file_get_contents($readmePath) : 'README file not found.';

    $environment = new Environment([]);
    $environment->addExtension(new CommonMarkCoreExtension());
    $environment->addExtension(new GithubFlavoredMarkdownExtension());

    $converter = new MarkdownConverter($environment);

    $readmeHtml = $converter->convert($readme)->getContent();

    return view('welcome', ['readme' => $readmeHtml]);
  }
}

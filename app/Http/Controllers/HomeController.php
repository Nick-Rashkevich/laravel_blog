<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Services\Weather\Interfaces\WeatherServiceInterface;

class HomeController extends Controller
{
    public function index(WeatherServiceInterface $weather)
    {
        $posts = Post::paginate(2);
        return view('pages.index', [
            'posts' => $posts,
//            'tags'  => $tags,
            'weather' => $weather
        ]);
    }


    public function show($slug)
    {
        $post = Post::where('slug', $slug)->with('tags')->first();

        return view('pages.show', ['post' => $post]);

    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $posts = $tag->posts()->paginate(2);

        return view('pages.list', ['posts' => $posts]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = $category->post()->paginate(2);

        return view('pages.list', ['posts' => $posts]);

    }


}

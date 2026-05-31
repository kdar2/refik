<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $q = Post::published()->with('category')->latest('published_at');

        if ($slug = $request->query('category')) {
            $q->whereHas('category', fn ($qq) => $qq->where('slug', $slug));
        }

        if ($search = $request->query('q')) {
            $q->where(function ($qq) use ($search) {
                $qq->where('title_tr', 'like', "%{$search}%")
                   ->orWhere('excerpt_tr', 'like', "%{$search}%");
            });
        }

        return view('pages.posts.index', [
            'posts'          => $q->paginate(9)->withQueryString(),
            'categories'     => PostCategory::active()->get(),
            'activeCategory' => $slug,
            'search'         => $search,
        ]);
    }

    public function show(string $slug): View
    {
        $post = Post::published()
            ->with(['category', 'author'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Görüntülenme sayacı (gevşek — concurrency önemsiz)
        $post->increment('view_count');

        $related = Post::published()
            ->where('id', '<>', $post->id)
            ->where('post_category_id', $post->post_category_id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.posts.show', compact('post', 'related'));
    }
}

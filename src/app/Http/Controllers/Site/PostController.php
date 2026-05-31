<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private readonly ApiService $api)
    {
    }

    public function index(Request $request): View
    {
        $params = array_filter([
            'category' => $request->query('category') ?: null,
            'q'        => $request->query('q')        ?: null,
            'page'     => $request->query('page', 1),
        ], fn ($v) => $v !== null && $v !== '');

        $raw   = $this->api->get('/api/v1/posts/', $params);
        $items = data_get($raw, 'results', $raw ?: []);
        $total = data_get($raw, 'count', count($items));

        $perPage = 9;
        $page    = (int) $request->query('page', 1);

        $posts = new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        // Kategori listesi — API'de ayrı endpoint yoksa boş bırak
        $categoriesRaw = $this->api->get('/api/v1/posts/categories/', [], 3600);
        $categories    = collect(data_get($categoriesRaw, 'results', $categoriesRaw ?: []));

        $slug   = $request->query('category');
        $search = $request->query('q');

        return view('pages.posts.index', compact('posts', 'categories', 'slug', 'search') + [
            'activeCategory' => $slug,
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->api->get("/api/v1/posts/{$slug}/");

        if (empty($post)) {
            abort(404);
        }

        // İlgili haberler: aynı kategori
        $categorySlug = data_get($post, 'category.slug') ?? data_get($post, 'category_slug');
        $relatedRaw   = $this->api->get('/api/v1/posts/', array_filter([
            'category' => $categorySlug,
            'limit'    => 4,
        ]), 300);

        $related = collect(data_get($relatedRaw, 'results', $relatedRaw ?: []))
            ->reject(fn ($item) => data_get($item, 'slug') === $slug)
            ->take(3);

        return view('pages.posts.show', compact('post', 'related'));
    }
}

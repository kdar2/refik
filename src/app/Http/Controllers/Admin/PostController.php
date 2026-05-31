<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $q = Post::with('category')->latest('id');

        if ($search = $request->query('q')) {
            $q->where('title_tr', 'like', "%{$search}%");
        }
        if ($cat = $request->query('category')) {
            $q->whereHas('category', fn ($qq) => $qq->where('slug', $cat));
        }

        return view('admin.posts.index', [
            'posts'      => $q->paginate(15)->withQueryString(),
            'categories' => PostCategory::orderBy('name_tr')->get(),
            'q'          => $search,
            'category'   => $cat,
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', [
            'post'       => new Post(['is_published' => true]),
            'categories' => PostCategory::orderBy('name_tr')->get(),
            'mode'       => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['author_id'] = auth()->id();
        if (!empty($data['is_published']) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);
        return redirect()->route('admin.posts.edit', $post)->with('success', 'Haber oluşturuldu.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', [
            'post'       => $post,
            'categories' => PostCategory::orderBy('name_tr')->get(),
            'mode'       => 'edit',
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request);
        if (!empty($data['is_published']) && empty($data['published_at']) && !$post->published_at) {
            $data['published_at'] = now();
        }
        $post->update($data);

        return back()->with('success', 'Haber güncellendi.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Haber silindi.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title_tr'         => ['required', 'string', 'max:255'],
            'title_en'         => ['nullable', 'string', 'max:255'],
            'excerpt_tr'       => ['nullable', 'string', 'max:500'],
            'content_tr'       => ['required', 'string'],
            'post_category_id' => ['nullable', 'exists:post_categories,id'],
            'cover_image'      => ['required', 'string', 'max:500'],
            'is_featured'      => ['nullable', 'boolean'],
            'is_published'     => ['nullable', 'boolean'],
            'published_at'     => ['nullable', 'date'],
        ]);
    }
}

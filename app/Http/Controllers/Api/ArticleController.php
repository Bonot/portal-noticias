<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query()->with('author');

        if ($request->has('query')) {
            $query = $query->where(function ($q) use ($request){
                $q->where('title', 'ilike', '%' . $request->get('query') . '%')
                    ->orWhere('content', 'ilike', '%' . $request->get('query') . '%');
            });
        }

        if ($request->has('author_id') && $request->get('author_id') > 0) {
            $query = $query->where('author_id', $request->get('author_id'));
        }

        if ((bool)$request->paginate) {
            return $query->paginate(perPage: $request->size ?: 10);
        }

        return $query->get();
    }

    public function store(ArticleRequest $request)
    {
        return response()->json(Article::create($request->all()), 201);
    }

    public function show(Article $article)
    {
        return $article;
    }

    public function update(Article $article, ArticleRequest $request)
    {
        return response()->json($article->update($request->all()), 201);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->noContent();
    }
}

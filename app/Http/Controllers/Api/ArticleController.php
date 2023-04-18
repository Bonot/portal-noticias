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
        return Article::with('author')->paginate(perPage: $request->size ?: 10);
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
        $article->update($request->all());
        return $article;
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->noContent();
    }
}

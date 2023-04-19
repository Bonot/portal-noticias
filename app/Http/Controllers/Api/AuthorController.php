<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use App\Models\Article;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        return Author::search($request->get('name'))
                ->orderBy('name')
                ->paginate(perPage: $request->size ?: 10);
    }

    public function store(AuthorRequest $request)
    {
        return response()->json(Author::create($request->all()), 201);
    }

    public function show(Author $author)
    {
        return $author;
    }

    public function update(Author $author, AuthorRequest $request)
    {
        return response()->json($author->update($request->all()), 201);
    }

    public function destroy(Author $author)
    {
        $hasArticleForThisAuthor = Article::where('author_id', $author->id)->exists();

        if ($hasArticleForThisAuthor) {
            return response()->json([
                'success' => false,
                'status' => Response::HTTP_CONFLICT,
                'message' => 'Não foi possível remover autor. Existem notícias vínculadas a ele.'
            ]);
        }

        $author->delete();
        return response()->noContent();
    }
}

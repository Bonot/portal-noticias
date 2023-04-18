<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('name')) {
            return Author::where('name', 'ilike', '%' . $request->name . '%')->get();
        }

        return Author::paginate(perPage: $request->size ?: 10);
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
        $author->update($request->all());
        return $author;
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return response()->noContent();
    }
}

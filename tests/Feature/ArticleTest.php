<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_article_index()
    {
        $articles = Article::factory()
            ->count(2)
            ->state(new Sequence(
                ['title' => 'Artigo 1'],
                ['title' => 'Artigo 2'],
            ))
            ->create();
    
        $response = $this->get('/api/articles');
    
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'author_id',
                    ]
                ],
                'current_page',
                'last_page',
                'per_page',
                'total',
            ])
            ->assertJson([
                'data' => [
                    [
                        'title' => 'Artigo 2',
                    ],
                    [
                        'title' => 'Artigo 1',
                    ],
                ],
                'per_page' => 10,
            ]);
    }

    public function test_article_index_with_search()
    {
        $articles = Article::factory()
            ->count(5)
            ->state(new Sequence(
                ['title' => 'Artigo busca 1'],
                ['title' => 'Artigo busca 2'],
                ['title' => 'Artigo 3'],
                ['title' => 'Artigo 4'],
                ['title' => 'Artigo 5'],
            ))
            ->create();

        $response = $this->get('/api/articles?query=busca&size=10');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'author_id',
                ]
            ],
            'current_page',
            'last_page',
            'per_page',
            'total',
        ])->assertJson([
            'data' => [
                [
                    'title' => 'Artigo busca 2',
                ],
                [
                    'title' => 'Artigo busca 1',
                ],
            ],
            'per_page' => 10,
        ]);
    }

    public function test_article_store()
    {
        $author = Author::factory()->create(['name' => 'John Doe']);

        $data = [
            'title' => 'Título do artigo',
            'content' => 'Conteúdo do artigo',
            'author_id' => $author->id,
        ];

        $response = $this->post('/api/articles', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data);
    }

    public function test_article_show()
    {
        $article = Article::factory()->create();

        $response = $this->get('/api/articles/' . $article->id);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $article->id,
            'title' => $article->title,
            'content' => $article->content,
            'author_id' => $article->author_id,
        ]);
    }

    public function test_article_update()
    {
        $article = Article::factory()->create();

        $data = [
            'title' => 'Novo título do artigo',
            'content' => 'Novo conteúdo do artigo',
            'author_id' => $article->author_id,
        ];

        $response = $this->put('/api/articles/' . $article->id, $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

    public function test_article_destroy()
    {
        $article = Article::factory()->create();

        $response = $this->delete('/api/articles/' . $article->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted($article);
    }
}

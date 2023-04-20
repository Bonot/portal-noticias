<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_author_index()
    {
        Author::factory()->create(['name' => 'John Doe']);
        Author::factory()->create(['name' => 'Bob Smith']);
    
        $response = $this->get('/api/authors');
    
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
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
                        'name' => 'Bob Smith',
                    ],
                    [
                        'name' => 'John Doe',
                    ],
                ],
                'per_page' => 10,
            ]);
    }

    public function test_index_author_with_search()
    {
        Author::factory()->create(['name' => 'John Doe']);
        Author::factory()->create(['name' => 'Jane Doe']);
        Author::factory()->create(['name' => 'Bob Smith']);
    
        $response = $this->get('/api/authors?name=Doe&size=2');
    
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
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
                        'name' => 'Jane Doe',
                    ],
                    [
                        'name' => 'John Doe',
                    ],
                ],
                'per_page' => 2,
            ]);
    }

    public function test_get_resource_author()
    {
        $response = $this->get('/api/authors/resource');

        $response->assertStatus(200);
    }

    public function test_get_resource_with_author_data()
    {
        $author1 = Author::factory()->create(['name' => 'John Doe']);
        $author2 = Author::factory()->create(['name' => 'Jane Doe']);
        $author3 = Author::factory()->create(['name' => 'Bob Smith']);

        $response = $this->get('/api/authors/resource');

        $response->assertStatus(200)
            ->assertJson([
                [
                    'id' => $author3->id,
                    'name' => $author3->name,
                ],
                [
                    'id' => $author2->id,
                    'name' => $author2->name,
                ],
                [
                    'id' => $author1->id,
                    'name' => $author1->name,
                ],
            ]);
    }

    public function test_author_create_with_valid_data()
    {
        $response = $this->post('/api/authors', [
            'name' => 'John Doe',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_author_create_with_invalid_data()
    {
        $response = $this->post('/api/authors', [
            'name' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'status',
                'errors',
            ]);
    }

    public function test_author_show()
    {
        $author = Author::factory()->create();
        $author = $author->toArray();

        $response = $this->get('/api/authors/' . $author['id']);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $author['id'],
                'name' => $author['name'],
                'created_at' => $author['created_at'],
                'updated_at' => $author['updated_at'],
            ]);
    }

    public function test_author_update()
    {
        $author = Author::factory()->create();
        $updatedAuthor = Author::factory()->make();

        $response = $this->put('/api/authors/' . $author->id, [
            'name' => $updatedAuthor->name,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => $updatedAuthor->name,
        ]);
    }

    public function test_author_destroy()
    {
        $author = Author::factory()->create();

        $response = $this->delete('/api/authors/' . $author->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted($author);
    }

    public function test_cannot_author_delete_with_articles()
    {
        $author = Author::factory()->create();
        Article::factory()->create(['author_id' => $author->id]);

        $response = $this->delete('/api/authors/' . $author->id);

        $response->assertStatus(409);

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
        ]);
    }
}

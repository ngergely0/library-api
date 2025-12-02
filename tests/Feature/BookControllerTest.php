<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\User; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class BookControllerTest extends TestCase
{
    use RefreshDatabase; 
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_index_returns_all_books()
    {
        $category = Category::factory()->create();
        $author = Author::factory()->create();

        Book::factory()->create([
            'name' => 'Kalózok',
            'category_id' => $category->id,
            'price' => 20.99,
            'publication_date' => '2021-05-10',
            'edition' => 5,
            'author_id' => $author->id,
            'isbn' => '978-1-00001-001-1',
            'cover' => 'covers/book30.jpg',
        ]);
     

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Kalózok'])
            ->assertJsonFragment(['category_id' => $category->id])
            ->assertJsonFragment(['price' => 20.99])
            ->assertJsonFragment(['publication_date' => '2021-05-10'])
            ->assertJsonFragment(['edition' => 5])
            ->assertJsonFragment(['author_id' => $author->id])
            ->assertJsonFragment(['isbn' => '978-1-00001-001-1'])
            ->assertJsonFragment(['cover' => 'covers/book30.jpg']);
    } 

    public function test_index_filters_by_needle()
    {
        Book::factory()->create(['name' => 'The London Fog']);
        Book::factory()->create(['name' => 'Vuk']);

        $response = $this->getJson('/api/books?needle=London');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'The London Fog'])  // The London Fog benne van a válaszban
            ->assertJsonMissing(['name' => 'Vuk']);  // Vuk nincs benne
    }
    
    public function test_store_creates_new_book()
    {
		$user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;
        
        $category = Category::factory()->create();
        $author = Author::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/books', [
            'name' => 'Tüskevár',
            'category_id' => $category->id,
            'price' => 15.50,
            'publication_date' => '1957-01-01',
            'edition' => 1,
            'author_id' => $author->id,
            'isbn' => '978-963-11-1111-1',
            'cover' => 'covers/tuskevar.jpg',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Tüskevár']);
		
        $this->assertDatabaseHas('books', ['name' => 'Tüskevár']);
    }

    public function test_update_modifies_existing_book()
    {
        $book = Book::factory()->create(['name' => 'The London Fog']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/books/{$book->id}", [
            'name' => 'The London Fog 2'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'The London Fog 2']);

        $this->assertDatabaseHas('books', ['id' => $book->id, 'name' => 'The London Fog 2']);
    } 

    public function test_update_returns_404_for_missing_book()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/books/999', [
            'name' => 'The London Fog 999'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    } 

    public function test_delete_removes_book()
    {
        $book = Book::factory()->create(['name' => 'The London Fog']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    } 
}
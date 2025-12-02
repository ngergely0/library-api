<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
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

    public function test_index_returns_all_authors()
    {
        Author::factory()->create([
            'name' => 'Nagy József',
            'age' => 34,
            'gender' => 'male',
            'nationality' => 'Hungarian',
        ]);
     

        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Nagy József'])
            ->assertJsonFragment(['age' => 34])
            ->assertJsonFragment(['gender' => 'male'])
            ->assertJsonFragment(['nationality' => 'Hungarian']);
    } 

    public function test_index_filters_by_needle()
    {
        Author::factory()->create(['name' => 'Emma Clarke']);
        Author::factory()->create(['name' => 'Kis Béla']);

        $response = $this->getJson('/api/authors?needle=Emma');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Emma Clarke'])  
            ->assertJsonMissing(['name' => 'Kis Béla']);  
    }
    
    public function test_store_creates_new_author()
    {
		$user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/authors', [
            'name' => 'Kis Béla',
            'nationality' => 'Hungarian',
            'age' => 45,
            'gender' => 'male'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Kis Béla']);
		
        $this->assertDatabaseHas('authors', ['name' => 'Kis Béla']);
    }

    public function test_update_modifies_existing_author()
    {
        $author = Author::factory()->create(['name' => 'Emma Clarke']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/authors/{$author->id}", [
            'name' => 'Emma Clarkess'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Emma Clarkess']);

        $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Emma Clarkess']);
    } 

    public function test_update_returns_404_for_missing_author()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/authors/999', [
            'name' => 'Emma Clarkessss'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    } 

    public function test_delete_removes_author()
    {
        $author = Author::factory()->create(['name' => 'Emma Clarke']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/authors/{$author->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    } 
}
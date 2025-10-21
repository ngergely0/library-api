<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        AuthorSeeder::class,
        CategorySeeder::class,
    ]);

    $this->call(BookSeeder::class); 

    User::factory()->create([
        'name' => 'Teszt',
        'email' => 'teszt@gmail.com',
        'password' => '123',
    ]);
}

}

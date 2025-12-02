<?php

namespace Database\Factories;

    use App\Models\Book;
    use App\Models\User;    
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
		use RefreshDatabase; 
		protected $model = Book::class;

		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition()
		{
			return [
				'name' => $this->faker->unique()->word(),
                'category_id' => \App\Models\Category::factory(),
                'author_id' => \App\Models\Author::factory(),
                'price' => $this->faker->randomFloat(2, 10, 100),
                'publication_date' => $this->faker->date(),
                'edition' => $this->faker->numberBetween(1, 10),
                'isbn' => $this->faker->isbn13(),
                'cover' => 'covers/default.jpg',
			];
		}
}
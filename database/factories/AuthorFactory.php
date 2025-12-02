<?php

namespace Database\Factories;

    use App\Models\Author;
    use App\Models\User;    
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class AuthorFactory extends Factory
{
		use RefreshDatabase; 
		protected $model = Author::class;

		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition()
		{
			return [
				'name' => $this->faker->unique()->name(),
                'nationality' => $this->faker->country(),
                'age' => $this->faker->numberBetween(20, 80),
                'gender' => $this->faker->randomElement(['male', 'female']),
			];
		}
}
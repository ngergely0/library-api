<?php

namespace Database\Factories;

    use App\Models\Category;
    use App\Models\User;    
	use Illuminate\Database\Eloquent\Factories\Factory;
	use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
		use RefreshDatabase; 
		protected $model = Category::class;

		/**
		 * Define the model's default state.
		 *
		 * @return array<string, mixed>
		 */
		public function definition()
		{
			return [
				'name' => $this->faker->unique()->word(),
			];
		}
}
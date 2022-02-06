<?php
namespace Database\Factories;

use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),
'price' => $this->faker->numberBetween(1,999),
'amount' => $this->faker->numberBetween(1,999),
'taxes' => $this->faker->numberBetween(1,999),
'discount' => $this->faker->numberBetween(1,999),
'active' => $this->faker->numberBetween(0,1),
'name' => $this->faker->word()
        ];
    }
}






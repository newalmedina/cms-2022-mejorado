<?php
namespace Database\Factories;

use App\Modules\Materials\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Material::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->word(),
'amount' => $this->faker->numberBetween(1,999),
'comun' => $this->faker->word(),
'active' => $this->faker->numberBetween(0,1),
'name' => $this->faker->word()
        ];
    }
}






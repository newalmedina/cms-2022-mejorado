<?php

namespace Database\Factories;

use App\Modules\Centers\Models\Center;
use Clavel\Locations\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class CenterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Center::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->word(),
            'cp' => $this->faker->word(),
            'city' => $this->faker->word(),
            'contact' => $this->faker->word(),
            'active' => $this->faker->numberBetween(0, 1),
            'name' => $this->faker->word(),
            'phone' => $this->faker->word(),
            'email' => $this->faker->word(),
            'province_id' => Province::factory()->create()->id,
        ];
    }
}

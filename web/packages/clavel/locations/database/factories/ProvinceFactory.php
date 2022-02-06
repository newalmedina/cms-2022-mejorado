<?php

namespace Database\Factories;

use Clavel\Locations\Models\Country;
use Clavel\Locations\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Province::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'active' => $this->faker->numberBetween(0, 1),
            'name' => $this->faker->word(),
            'country_id' => Country::factory()->create()->id,
        ];
    }
}

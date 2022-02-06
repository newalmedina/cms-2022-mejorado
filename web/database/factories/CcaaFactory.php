<?php

namespace Database\Factories;

use Clavel\Locations\Models\Ccaa;
use Clavel\Locations\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CcaaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ccaa::class;

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
            'country_id' => Country::factory()->create()->id
        ];
    }
}

<?php

namespace Database\Factories;


use Clavel\Locations\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'alpha2_code' => $this->faker->lexify('??'),
            'alpha3_code' => $this->faker->lexify('???'),
            'short_name' => $this->faker->word(),
            'active' => $this->faker->numberBetween(0, 1),
            'name' => $this->faker->word(),
            'numeric_code' => $this->faker->numberBetween(1, 999)
        ];
    }
}

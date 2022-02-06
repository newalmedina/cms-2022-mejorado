<?php

namespace Database\Factories;

use Clavel\Locations\Models\Ccaa;
use Clavel\Locations\Models\City;
use Clavel\Locations\Models\Country;
use Clavel\Locations\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'active' => $this->faker->numberBetween(0, 1),
            'province_id' => Province::factory()->lazy(),
            'country_id' => Country::factory()->create()->id,
            'ccaa_id' => Ccaa::factory()->create()->id,
            'name' => $this->faker->word()
        ];
    }
}

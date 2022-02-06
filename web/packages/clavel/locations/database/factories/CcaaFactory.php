<?php

namespace Database\Factories;

use App\Modules\Ccaas\Models\Ccaa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Clavel\Locations\Models\Country;

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
            'country_id' => Country::factory()->create()->id,
        ];
    }
}

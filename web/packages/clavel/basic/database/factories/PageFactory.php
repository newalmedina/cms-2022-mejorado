<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Clavel\Basic\Models\Page::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'active' => $this->faker->boolean(),
            'css' => null,
            'javascript' => null,
            'permission' => 0,
            'permission_name' => null,
            'created_id' => 1,
            'modified_id' => 1,
        ];
    }
}

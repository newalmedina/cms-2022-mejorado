<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PageTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Clavel\Basic\Models\PageTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'locale' => 'es',
            'title' => $this->faker->sentence(5),
            'body' => $this->faker->paragraph(5),
            'meta_title' => $this->faker->name(),
            'meta_content' => $this->faker->sentence(2)
        ];
    }
}

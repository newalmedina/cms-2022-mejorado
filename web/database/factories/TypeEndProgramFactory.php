<?php
namespace Database\Factories;

use App\Modules\TypeEndPrograms\Models\TypeEndProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeEndProgramFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TypeEndProgram::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),
'active' => $this->faker->numberBetween(0,1),
'name' => $this->faker->word()
        ];
    }
}






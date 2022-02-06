<?php
namespace Database\Factories;

use App\Modules\TypeAlarms\Models\TypeAlarm;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypeAlarmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TypeAlarm::class;

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






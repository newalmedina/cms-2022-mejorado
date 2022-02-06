<?php

namespace Database\Factories;

use App\Modules\Alarms\Models\Alarms;
use App\Modules\Patients\Models\Patient;
use App\Modules\TypeAlarms\Models\TypeAlarm;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlarmsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Alarms::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->numberBetween(1, 999),
            'alarm_code' => $this->faker->word(),
            'comments' => $this->faker->sentence(),
            'typealarm_id' => TypeAlarm::factory()->create()->id,
            'patient_id' => Patient::factory()->create()->id,
        ];
    }
}

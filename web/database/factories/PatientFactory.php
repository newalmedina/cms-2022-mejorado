<?php
namespace Database\Factories;

use App\Modules\Patients\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->sentence(),
'code' => $this->faker->word(),
'email' => $this->faker->word(),
'birthday_date' => $this->faker->numberBetween(1,999),
'gender' => $this->faker->numberBetween(1,999),
'adress' => $this->faker->word(),
'city' => $this->faker->word(),
'cp' => $this->faker->word(),
'first_name' => $this->faker->word(),
'last_name' => $this->faker->word(),
'phone' => $this->faker->word(),
'second_phone' => $this->faker->word(),
'consent' => $this->faker->numberBetween(1,999),
'consent_date' => $this->faker->numberBetween(1,999),
'active' => $this->faker->numberBetween(0,1)
        ];
    }
}






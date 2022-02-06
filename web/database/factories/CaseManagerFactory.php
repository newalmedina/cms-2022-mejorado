<?php
namespace Database\Factories;

use App\Modules\CaseManagers\Models\CaseManager;
use Illuminate\Database\Eloquent\Factories\Factory;

class CaseManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CaseManager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'last_name' => $this->faker->word(),
'first_name' => $this->faker->word(),
'email' => $this->faker->numberBetween(1,999),
'gender' => $this->faker->numberBetween(1,999),
'phone' => $this->faker->word()
        ];
    }
}






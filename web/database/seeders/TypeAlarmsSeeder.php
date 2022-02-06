<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypeAlarmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\TypeAlarms\Models\TypeAlarm::factory()->count(100)->create();
    }
}

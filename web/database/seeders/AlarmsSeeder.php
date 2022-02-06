<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AlarmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\Alarms\Models\Alarms::factory()->count(100)->create();
    }
}

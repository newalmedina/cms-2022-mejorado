<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\Locations\Models\Province::factory()->count(100)->create();
    }
}

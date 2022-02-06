<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CaseManagersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\CaseManagers\Models\CaseManager::factory()->count(100)->create();
    }
}

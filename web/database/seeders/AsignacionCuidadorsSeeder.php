<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AsignacionCuidadorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\AsignacionCuidadors\Models\AsignacionCuidador::factory()->count(100)->create();
    }
}

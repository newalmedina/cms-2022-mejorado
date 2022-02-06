<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypeEndProgramsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\TypeEndPrograms\Models\TypeEndProgram::factory()->count(100)->create();
    }
}

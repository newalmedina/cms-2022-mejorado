<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EndprogramsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\Endprograms\Models\Endprogram::factory()->count(100)->create();
    }
}

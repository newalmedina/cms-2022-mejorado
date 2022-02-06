<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Modules\Materials\Models\Material::factory()->count(100)->create();
    }
}

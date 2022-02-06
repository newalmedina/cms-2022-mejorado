<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Clavel\Locations\Models\Province;

class CentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('centers')->delete();

        // Creamos un centro fijo
        $centro = [
            'name' => "Centro Pulso",
            'address' => "Rambla del Celler 117-119",
            'cp' => "08172",
            'city' => "Sant Cugat del Vallés",
            'contact' => "dpto. PSP",
            'active' => true,

            'phone' => "935896264",
            'email' => "info@pulso.com",
            'province_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];


        $centro_id = DB::table('centers')->insertGetId( $centro );

    }
}

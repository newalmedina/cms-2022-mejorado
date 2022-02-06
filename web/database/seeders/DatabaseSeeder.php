<?php

namespace Database\Seeders;

use App\Modules\EndPrograms\Models\EndProgram;
use App\Modules\TypeEndPrograms\Models\TypeEndProgram;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AdminSeeders::class);
        $this->call(IdiomasSeeder::class);
        $this->call(RolesSeeders::class);
        $this->call(PermissionSeeder::class);
        $this->call(LoginAttemptPermissionSeeder::class);
        $this->call(SuplantacionSeeder::class);

        $this->call(IdiomasPermissionSeeder::class);

        $this->call(SettingsSeeder::class);
        $this->call(SettingsPermissionSeeder::class);

        // Basic
        $this->call(MenuSeeder::class);
        $this->call(BasicPermissionSeeder::class);
        $this->call(PageBasicSeeder::class);

        // Crud generator
        $this->call(CrudGeneratorPermissionSeeder::class);
        $this->call(CrudGeneratorDataSeeder::class);

        // Locations
        $this->call(CountriesPermissionSeeder::class);
        $this->call(CcaasPermissionSeeder::class);
        $this->call(ProvincesPermissionSeeder::class);
        $this->call(CitiesPermissionSeeder::class);

        if (!App::runningUnitTests()) {
            //  $this->call(LocationsSeeder::class);
        }

        // Centros
        $this->call(CentersPermissionSeeder::class);
        if (!App::runningUnitTests()) {
            $this->call(CentersSeeder::class);
        }

        $this->call(CategoriesPermissionSeeder::class);
        $this->call(ProductsPermissionSeeder::class);
    }
}

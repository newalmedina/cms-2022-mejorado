<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('page_translations')->delete();
        DB::table('pages')->delete();

        \Clavel\Basic\Models\Page::factory()
            ->has(\Clavel\Basic\Models\PageTranslation::factory()->count(1), 'translation')
            ->count(50)
            ->create();
    }
}

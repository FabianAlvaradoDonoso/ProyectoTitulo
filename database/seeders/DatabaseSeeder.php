<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Career::factory()->count(10)->create();

    }
}

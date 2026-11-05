<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ScrapedBooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->call('libros:import-scraped');
    }
}

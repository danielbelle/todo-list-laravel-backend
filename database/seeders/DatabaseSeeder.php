<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the test users seeder for query builder testing. And TaskSeeder.
        $this->call([
            TestUsersSeeder::class,
            TaskSeeder::class,
        ]);
    }
}

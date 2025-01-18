<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\User;
=======
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
<<<<<<< HEAD
        $this->call([
            UserSeeder::class,
            SystemSeeder::class,
            SystemUserSeeder::class,
=======

        $this->call([
            ShopSeeder::class,
            UserSeeder::class,
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
        ]);
    }
}

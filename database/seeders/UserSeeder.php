<?php

namespace Database\Seeders;
<<<<<<< HEAD
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
=======

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
=======
        // Menggunakan password yang diinginkan
        $password = Hash::make('12345678');

        User::factory()->create([
            'name' => 'Admin Eleanor',
            'email' => 'eleanoradmin@admin',
            'password' => $password,
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
        ]);
    }
}

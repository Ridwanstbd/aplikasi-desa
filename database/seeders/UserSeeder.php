<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan password yang diinginkan
        $password = Hash::make('12345678');

        User::factory()->create([
            'name' => 'Admin Eleanor',
            'email' => 'eleanoradmin@admin',
            'password' => $password,
        ]);
    }
}

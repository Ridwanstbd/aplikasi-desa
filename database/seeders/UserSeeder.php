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
        $password = Hash::make('password');

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'password' => $password,
        ]);
    }
}

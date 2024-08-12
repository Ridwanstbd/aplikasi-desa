<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shop::factory()->create([
            'description' => 'Toko alat ternak tangan pertama. kami bergerak di bidang alat peternakan hewan ruminansia.
seperti alat peternakan untuk unggas, domba, kambing, sapi, kerbau, babi dll.',
        ]);
    }
}

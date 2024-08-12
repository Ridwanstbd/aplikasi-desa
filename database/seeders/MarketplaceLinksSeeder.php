<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MarketplaceLinks;

class MarketplaceLinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jumlahData = 9;

        MarketplaceLinks::factory()->count($jumlahData)->create();
    }
}

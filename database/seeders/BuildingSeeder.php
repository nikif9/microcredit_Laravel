<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::create([
            'address'   => 'г. Москва, ул. Ленина 1, офис 3',
            'latitude'  => '55.7558',
            'longitude' => '37.6176',
        ]);

        // Можно добавить дополнительные здания
        Building::create([
            'address'   => 'г. Санкт-Петербург, Невский пр., 1',
            'latitude'  => '59.9343',
            'longitude' => '30.3351',
        ]);
    }
}

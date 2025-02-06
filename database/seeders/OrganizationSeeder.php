<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationPhoneNumber;
use App\Models\Activity;
use App\Models\Building;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Получаем здание и деятельность для примера
        $building = Building::first();
        $foodActivity = Activity::where('name', 'Еда')->first();

        $org = Organization::create([
            'name'        => 'ООО "Рога и Копыта"',
            'building_id' => $building->id,
        ]);

        // Добавляем несколько телефонных номеров
        OrganizationPhoneNumber::create([
            'organization_id' => $org->id,
            'phone_number'    => '2-222-222',
        ]);
        OrganizationPhoneNumber::create([
            'organization_id' => $org->id,
            'phone_number'    => '3-333-333',
        ]);
        OrganizationPhoneNumber::create([
            'organization_id' => $org->id,
            'phone_number'    => '8-923-666-13-13',
        ]);

        // Прикрепляем виды деятельности (можно добавить несколько)
        $org->activities()->attach($foodActivity->id);
    }
}

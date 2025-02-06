<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Уровень 1
        $food = Activity::create(['name' => 'Еда']);
        $cars = Activity::create(['name' => 'Автомобили']);
        $passenger = Activity::create(['name' => 'Легковые']);

        // Уровень 2
        Activity::create(['name' => 'Мясная продукция', 'parent_id' => $food->id]);
        Activity::create(['name' => 'Молочная продукция', 'parent_id' => $food->id]);
        Activity::create(['name' => 'Грузовые', 'parent_id' => $cars->id]);
        Activity::create(['name' => 'Запчасти', 'parent_id' => $passenger->id]);
        Activity::create(['name' => 'Аксессуары', 'parent_id' => $passenger->id]);

        // Если необходимо, можно добавить уровень 3 (но не более 3 уровней)
    }
}

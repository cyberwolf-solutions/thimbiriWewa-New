<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Breakfast buffet', 'category_id' => 1, 'created_by' => 1],
            ['name' => 'Lunch buffet', 'category_id' => 1, 'created_by' => 1],
            ['name' => 'Dinner buffet', 'category_id' => 1, 'created_by' => 1],
        ];

        foreach ($data as $value) {
            Meal::firstOrCreate(['name' => $value['name']], $value);
        }
    }
}

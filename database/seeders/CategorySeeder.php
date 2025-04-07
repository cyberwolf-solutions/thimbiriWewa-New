<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void {
        Category::create([
            'name' => 'Buffet',
            'type' => 'Restaurant',
            'created_by' => '1'
        ]);
    }
}

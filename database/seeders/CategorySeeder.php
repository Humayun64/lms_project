<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            'Kolom Digital School',
            'Kolom Creative School',
            'Kolom AI School',
            'Kolom Career School',
        ];

        foreach ($schools as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => 'active']
            );
        }
    }
}

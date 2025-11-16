<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Color;
use App\Models\Coupon;
use App\Models\Size;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {

        $categories = ['Shirt', 'T-Shirt'];
        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }


        $colors = [
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
            ['name' => 'Green', 'hex_code' => '#00FF00'],
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
        ];
        foreach ($colors as $color) {
            Color::create($color);
        }
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        foreach ($sizes as $size) {
            Size::create(['name' => $size]);
        }
        Coupon::create([
            'code' => 'SAVE10',
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => 50,
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'FLAT20',
            'type' => 'fixed',
            'value' => 20,
            'min_purchase' => 100,
            'is_active' => true,
        ]);
    }
}

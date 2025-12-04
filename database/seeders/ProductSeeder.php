<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        if ($categories->count() === 0) {
            $this->command->info('No categories found. Run CategorySeeder first.');
            return;
        }

        // Sample products
        $products = [
            [
                'name' => 'Yakan Shirt',
                'description' => 'Handwoven traditional shirt.',
                'price' => 500,
                'stock' => 10,
                'category_id' => $categories->where('name', 'Apparel')->first()->id ?? $categories->first()->id,
                'image' => 'products/yakan-shirt.jpg',
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Yakan Bag',
                'description' => 'Colorful handwoven bag.',
                'price' => 750,
                'stock' => 5,
                'category_id' => $categories->where('name', 'Accessories')->first()->id ?? $categories->first()->id,
                'image' => 'products/yakan-bag.jpg',
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Yakan Earrings',
                'description' => 'Handmade traditional earrings.',
                'price' => 250,
                'stock' => 20,
                'category_id' => $categories->where('name', 'Accessories')->first()->id ?? $categories->first()->id,
                'image' => 'products/yakan-earrings.jpg',
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Yakan Basket',
                'description' => 'Decorative woven basket.',
                'price' => 600,
                'stock' => 7,
                'category_id' => $categories->where('name', 'Home & Living')->first()->id ?? $categories->first()->id,
                'image' => 'products/yakan-basket.jpg',
                'is_active' => true,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

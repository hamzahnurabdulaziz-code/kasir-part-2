<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'BRG-001',
                'name' => 'Beras Pandan Wangi 5kg',
                'price' => 75000,
                'stock' => 20,
            ],
            [
                'code' => 'BRG-002',
                'name' => 'Minyak Goreng Sania 2L',
                'price' => 38000,
                'stock' => 15,
            ],
            [
                'code' => 'BRG-003',
                'name' => 'Gula Pasir Gulaku 1kg',
                'price' => 17500,
                'stock' => 3, // Low stock <= 5 for dashboard warning check
            ],
            [
                'code' => 'BRG-004',
                'name' => 'Indomie Goreng Spesial',
                'price' => 3500,
                'stock' => 80,
            ],
            [
                'code' => 'BRG-005',
                'name' => 'Telur Ayam Negeri 1kg',
                'price' => 28000,
                'stock' => 4, // Low stock <= 5
            ],
            [
                'code' => 'BRG-006',
                'name' => 'Kopi Kapal Api Special 165g',
                'price' => 14000,
                'stock' => 25,
            ],
            [
                'code' => 'BRG-007',
                'name' => 'Susu UHT Ultra Milk Full Cream 1L',
                'price' => 21000,
                'stock' => 12,
            ],
            [
                'code' => 'BRG-008',
                'name' => 'Teh Celup Sosro Isi 30',
                'price' => 8500,
                'stock' => 30,
            ],
            [
                'code' => 'BRG-009',
                'name' => 'Sabun Mandi Lifebuoy 110g',
                'price' => 5000,
                'stock' => 0, // Out of stock to test out-of-stock validation
            ],
            [
                'code' => 'BRG-010',
                'name' => 'Air Mineral Le Minerale 600ml',
                'price' => 3500,
                'stock' => 45,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['code' => $product['code']], $product);
        }
    }
}


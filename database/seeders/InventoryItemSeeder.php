<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventoryItems = [
            // ===== T-SHIRT PRINTING MATERIALS =====
            [
                'name' => 'White Cotton T-Shirt (Small)',
                'unit' => 'piece',
                're_order_stock' => 20,
                'current_stock' => 50,
            ],
            [
                'name' => 'White Cotton T-Shirt (Medium)',
                'unit' => 'piece',
                're_order_stock' => 20,
                'current_stock' => 45,
            ],
            [
                'name' => 'White Cotton T-Shirt (Large)',
                'unit' => 'piece',
                're_order_stock' => 15,
                'current_stock' => 40,
            ],
            [
                'name' => 'Heat Transfer Vinyl (HTV)',
                'unit' => 'roll',
                're_order_stock' => 10,
                'current_stock' => 20,
            ],
            [
                'name' => 'Sublimation Paper (A4)',
                'unit' => 'pack',
                're_order_stock' => 30,
                'current_stock' => 70,
            ],
            [
                'name' => 'Sublimation Ink (Set CMYK)',
                'unit' => 'set',
                're_order_stock' => 10,
                'current_stock' => 20,
            ],
            [
                'name' => 'Silkscreen Frame 20x24',
                'unit' => 'piece',
                're_order_stock' => 7,
                'current_stock' => 15,
            ],
            [
                'name' => 'Plastisol Ink (White)',
                'unit' => 'liter',
                're_order_stock' => 7,
                'current_stock' => 19,
            ],
            [
                'name' => 'Plastisol Ink (Black)',
                'unit' => 'liter',
                're_order_stock' => 7,
                'current_stock' => 10,
            ],

            // ===== TARPAULIN PRINTING =====
            [
                'name' => 'Tarpaulin Roll (10ft x 50m)',
                'unit' => 'roll',
                're_order_stock' => 12,
                'current_stock' => 24,
            ],
            [
                'name' => 'Tarpaulin Roll (6ft x 50m)',
                'unit' => 'roll',
                're_order_stock' => 5,
                'current_stock' => 15,
            ],
            [
                'name' => 'Solvent Ink (1L)',
                'unit' => 'liter',
                're_order_stock' => 5,
                'current_stock' => 10,
            ],

            // ===== GIVEAWAYS =====
            [
                'name' => 'Sublimation Mug (White)',
                'unit' => 'piece',
                're_order_stock' => 30,
                'current_stock' => 100,
            ],
            [
                'name' => 'Sublimation Fan (Round)',
                'unit' => 'piece',
                're_order_stock' => 30,
                'current_stock' => 60,
            ],
            [
                'name' => 'Sublimation Cap (White)',
                'unit' => 'piece',
                're_order_stock' => 25,
                'current_stock' => 40,
            ],

            // ===== INVITATIONS & DOCUMENT PRINTING =====
            [
                'name' => 'Matte Cardstock (A4)',
                'unit' => 'pack',
                're_order_stock' => 10,
                'current_stock' => 15,
            ],
            [
                'name' => 'Glossy Photo Paper (A4)',
                'unit' => 'pack',
                're_order_stock' => 8,
                'current_stock' => 12,
            ],
            [
                'name' => 'Laminating Film (A4)',
                'unit' => 'roll',
                're_order_stock' => 5,
                'current_stock' => 9,
            ],
            [
                'name' => 'PVC ID Card Blanks',
                'unit' => 'box',
                're_order_stock' => 5,
                'current_stock' => 8,
            ],
            [
                'name' => 'Nylon ID Lace (Blue)',
                'unit' => 'bundle',
                're_order_stock' => 40,
                'current_stock' => 100,
            ],

            // ===== BANNERS & CERTIFICATES =====
            [
                'name' => 'Banner Cloth Roll (4ft x 30m)',
                'unit' => 'roll',
                're_order_stock' => 10,
                'current_stock' => 30,
            ],
            [
                'name' => 'Certificate Paper (A4, Cream)',
                'unit' => 'pack',
                're_order_stock' => 5,
                'current_stock' => 20,
            ],
        ];

        foreach ($inventoryItems as $item) {
            InventoryItem::create($item);
        }
    }
}

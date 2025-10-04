<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PrintWorks Supplies Trading',
                'phone' => '+63-917-101-2233',
                'email' => 'sales@printworks.com.ph',
                'address' => 'Blk 12 Lot 5, Commonwealth Ave, Quezon City, Metro Manila',
            ],
            [
                'name' => 'TexPrint Fabric Solutions',
                'phone' => '+63-926-554-7890',
                'email' => 'orders@texprint.ph',
                'address' => '45 Textile St., Sta. Mesa, Manila',
            ],
            [
                'name' => 'Inktech Philippines Inc.',
                'phone' => '+63-920-776-4321',
                'email' => 'support@inktechph.com',
                'address' => '124 Pioneer Ave., Pasig City, Metro Manila',
            ],
            [
                'name' => 'VinylPro Tarpaulin Distributors',
                'phone' => '+63-915-330-9988',
                'email' => 'info@vinylpro.ph',
                'address' => 'Phase 3, Laguna Technopark, Biñan, Laguna',
            ],
            [
                'name' => 'Creative Materials and Giveaways',
                'phone' => '+63-927-412-6680',
                'email' => 'creative@materialsph.com',
                'address' => '12 Mabini St., Davao City, Davao del Sur',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}

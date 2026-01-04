<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\MaterialRequest;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Additional standalone materials
        $standaloneMaterials = [
            [
                'name' => 'Extension Cords (25ft)',
                'description' => 'Heavy duty extension cords for electrical connections',
                'specification' => '25 feet, 12 AWG, rubber jacket',
                'unit' => 'piece',
                'quantity' => 10,
                'approved_quantity' => 0,
                'received_quantity' => 0,
                'consumed_quantity' => 0,
                'remaining_quantity' => 0,
                'unit_price' => 850,
                'total_price' => 8500,
                'status' => 'requested',
                'supplier' => 'Electrical Supplies Ltd',
                'brand' => 'PowerTech',
            ],
            [
                'name' => 'Wire Strippers Set',
                'description' => 'Professional wire stripping tool set',
                'specification' => 'Multiple gauge stripping capabilities',
                'unit' => 'set',
                'quantity' => 5,
                'approved_quantity' => 0,
                'received_quantity' => 0,
                'consumed_quantity' => 0,
                'remaining_quantity' => 0,
                'unit_price' => 1200,
                'total_price' => 6000,
                'status' => 'requested',
                'supplier' => 'Tool Master Inc',
                'brand' => 'Professional Tools',
            ],
            [
                'name' => 'Label Printer',
                'description' => 'Industrial label printer for cable marking',
                'specification' => 'Thermal transfer printer, PC connectivity',
                'unit' => 'piece',
                'quantity' => 2,
                'approved_quantity' => 0,
                'received_quantity' => 0,
                'consumed_quantity' => 0,
                'remaining_quantity' => 0,
                'unit_price' => 4500,
                'total_price' => 9000,
                'status' => 'requested',
                'supplier' => 'Industrial Solutions',
                'brand' => 'LabelTech',
            ],
            [
                'name' => 'Measurement Tools Kit',
                'description' => 'Precision measurement tools for installation',
                'specification' => 'Lasers, levels, tape measures, calipers',
                'unit' => 'kit',
                'quantity' => 3,
                'approved_quantity' => 0,
                'received_quantity' => 0,
                'consumed_quantity' => 0,
                'remaining_quantity' => 0,
                'unit_price' => 3800,
                'total_price' => 11400,
                'status' => 'requested',
                'supplier' => 'Precision Tools Co',
                'brand' => 'AccurateMeasure',
            ],
            [
                'name' => 'Personal Protective Equipment',
                'description' => 'PPE kit for construction workers',
                'specification' => 'Vests, gloves, masks, eye protection',
                'unit' => 'kit',
                'quantity' => 20,
                'approved_quantity' => 0,
                'received_quantity' => 0,
                'consumed_quantity' => 0,
                'remaining_quantity' => 0,
                'unit_price' => 750,
                'total_price' => 15000,
                'status' => 'requested',
                'supplier' => 'Safety First Corp',
                'brand' => 'SafeWork',
            ],
        ];

        // Get first material request to attach these to
        $materialRequest = MaterialRequest::first();
        
        if ($materialRequest) {
            foreach ($standaloneMaterials as $materialData) {
                $materialData['material_request_id'] = $materialRequest->id;
                Material::create($materialData);
            }
        }
    }
}
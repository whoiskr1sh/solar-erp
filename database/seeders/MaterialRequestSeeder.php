<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialRequest;
use App\Models\Material;
use App\Models\Project;
use App\Models\User;

class MaterialRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        $requestsData = [
            [
                'title' => 'Solar Panel Installation Materials - Phase 1',
                'description' => 'Materials required for solar panel installation including panels, mounting hardware, cables, and electrical components for Phase 1 of the project.',
                'priority' => 'high',
                'category' => 'electrical',
                'request_type' => 'purchase',
                'required_date' => '2024-05-15',
                'urgency_reason' => 'delay_risk',
                'justification' => 'Critical materials needed to maintain project timeline',
                'status' => 'approved',
                'approved_by' => $users[1] ?? 1,
                'approved_date' => '2024-05-10',
                'total_amount' => 450000,
                'approved_amount' => 420000,
                'consumed_amount' => 380000,
            ],
            [
                'title' => 'Safety Equipment and Tools',
                'description' => 'Safety equipment including helmets, harnesses, gloves, safety shoes, and cutting tools for site operations.',
                'priority' => 'medium',
                'category' => 'safety_items',
                'request_type' => 'purchase',
                'required_date' => '2024-06-01',
                'urgency_reason' => 'normal',
                'justification' => 'Standard safety equipment for site operations',
                'status' => 'completed',
                'approved_by' => $users[1] ?? 1,
                'approved_date' => '2024-05-25',
                'completion_date' => '2024-05-30',
                'total_amount' => 85000,
                'approved_amount' => 85000,
                'consumed_amount' => 85000,
            ],
            [
                'title' => 'Emergency Backup Generator',
                'description' => 'Emergency backup generator for site operations during power outages.',
                'priority' => 'urgent',
                'category' => 'tools_equipment',
                'request_type' => 'rental',
                'required_date' => '2024-05-05',
                'urgency_reason' => 'equipment_failure',
                'justification' => 'Critical site operations require continuous power supply',
                'status' => 'in_progress',
                'approved_by' => $users[1] ?? 1,
                'approved_date' => '2024-05-02',
                'assigned_to' => $users[2] ?? 1,
                'total_amount' => 25000,
                'approved_amount' => 25000,
                'consumed_amount' => 15000,
            ],
            [
                'title' => 'Raw Materials for Structure',
                'description' => 'Steel beams, concrete, rebar, and other structural materials for foundation and mounting structure.',
                'priority' => 'high',
                'category' => 'raw_materials',
                'request_type' => 'purchase',
                'required_date' => '2024-05-20',
                'urgency_reason' => 'deadline_critical',
                'justification' => 'Structure foundation required before panel installation',
                'status' => 'pending',
                'total_amount' => 280000,
                'approved_amount' => 0,
                'consumed_amount' => 0,
            ],
            [
                'title' => 'Cable Management System',
                'description' => 'Cable trays, conduits, junction boxes, and cable management accessories for electrical installation.',
                'priority' => 'medium',
                'category' => 'electrical',
                'request_type' => 'purchase',
                'required_date' => '2024-05-25',
                'urgency_reason' => 'normal',
                'justification' => 'Required for cable routing and protection',
                'status' => 'approved',
                'approved_by' => $users[1] ?? 1,
                'approved_date' => '2024-05-20',
                'total_amount' => 65000,
                'approved_amount' => 62000,
                'consumed_amount' => 0,
            ],
            [
                'title' => 'Consumable Supplies',
                'description' => 'Office supplies, bolts, nuts, washers, tape, and other consumable items for daily operations.',
                'priority' => 'low',
                'category' => 'consumables',
                'request_type' => 'purchase',
                'required_date' => '2024-06-10',
                'urgency_reason' => 'normal',
                'justification' => 'Regular supplies for site maintenance',
                'status' => 'draft',
                'total_amount' => 25000,
                'approved_amount' => 0,
                'consumed_amount' => 0,
            ],
            [
                'title' => 'Heavy Equipment Transfer',
                'description' => 'Transfer of excavator and crane from site A to site B for excavation work.',
                'priority' => 'medium',
                'category' => 'tools_equipment',
                'request_type' => 'transfer',
                'required_date' => '2024-05-08',
                'urgency_reason' => 'delay_risk',
                'justification' => 'Equipment needed for site preparation',
                'status' => 'completed',
                'approved_by' => $users[1] ?? 1,
                'approved_date' => '2024-05-05',
                'completion_date' => '2024-05-07',
                'total_amount' => 15000,
                'approved_amount' => 15000,
                'consumed_amount' => 15000,
            ],
            [
                'title' => 'Weather Monitoring Equipment',
                'description' => 'Weather station and monitoring equipment for site safety during adverse weather conditions.',
                'priority' => 'medium',
                'category' => 'tools_equipment',
                'request_type' => 'rental',
                'required_date' => '2024-05-12',
                'urgency_reason' => 'weather_dependent',
                'justification' => 'Safety monitoring for outdoor work',
                'status' => 'approved',
                'approved_by' => $users[1] ?? 1,
                'approved_date' => '2024-05-08',
                'total_amount' => 18000,
                'approved_amount' => 18000,
                'consumed_amount' => 8000,
            ],
            [
                'title' => 'Quality Control Instruments',
                'description' => 'Multimeters, insulation testers, current clamps, and other testing equipment for quality verification.',
                'priority' => 'high',
                'category' => 'tools_equipment',
                'request_type' => 'purchase',
                'required_date' => '2024-05-18',
                'urgency_reason' => 'deadline_critical',
                'justification' => 'Required for electrical testing and commissioning',
                'status' => 'partial',
                'approved_by' => $users[1] ?? 1,
                'approved_date' => '2024-05-15',
                'total_amount' => 95000,
                'approved_amount' => 95000,
                'consumed_amount' => 62000,
            ],
            [
                'title' => 'Communication Equipment',
                'description' => 'Wireless communication system, walkie-talkies, and communication infrastructure for site coordination.',
                'priority' => 'medium',
                'category' => 'tools_equipment',
                'request_type' => 'rental',
                'required_date' => '2024-05-30',
                'urgency_reason' => 'normal',
                'justification' => 'Essential for site coordination and safety',
                'status' => 'pending',
                'total_amount' => 12000,
                'approved_amount' => 0,
                'consumed_amount' => 0,
            ],
            [
                'title' => 'Material Transport Vehicles',
                'description' => 'Logistics arrangements for material transportation from warehouse to site locations.',
                'priority' => 'urgent',
                'category' => 'tools_equipment',
                'request_type' => 'rental',
                'required_date' => '2024-05-04',
                'urgency_reason' => 'equipment_failure',
                'justification' => 'Urgent transportation needed for material delivery',
                'status' => 'rejected',
                'rejection_reason' => 'Budget constraints - alternative transportation arranged',
                'total_amount' => 22000,
                'approved_amount' => 0,
                'consumed_amount' => 0,
            ],
            [
                'title' => 'Site Documentation Materials',
                'description' => 'Documentation supplies including folders, labels, markers, and digital storage devices.',
                'priority' => 'low',
                'category' => 'consumables',
                'request_type' => 'purchase',
                'required_date' => '2024-06-05',
                'urgency_reason' => 'normal',
                'justification' => 'Standard documentation supplies for project records',
                'status' => 'draft',
                'total_amount' => 8000,
                'approved_amount' => 0,
                'consumed_amount' => 0,
            ],
        ];

        // Material specifications for each request
        $materialSpecs = [
            'Solar Panel Installation Materials - Phase 1' => [
                ['name' => 'Solar Panels (500W)', 'quantity' => 100, 'unit_price' => 2500, 'specification' => 'Monocrystalline, 500W rated'],
                ['name' => 'Mounting Rail System', 'quantity' => 50, 'unit_price' => 1500, 'specification' => 'Aluminum rails with clamps'],
                ['name' => 'DC Cables (4mm)', 'quantity' => 3000, 'unit_price' => 45, 'specification' => 'Twin conductor DC cable'],
                ['name' => 'MC4 Connectors', 'quantity' => 200, 'unit_price' => 25, 'specification' => 'Weatherproof connectors'],
                ['name' => 'Inverter (50kW)', 'quantity' => 5, 'unit_price' => 45000, 'specification' => 'String inverter, 50kW capacity'],
            ],
            'Safety Equipment and Tools' => [
                ['name' => 'Safety Helmets', 'quantity' => 25, 'unit_price' => 800, 'specification' => 'Industrial grade safety helmets'],
                ['name' => 'Safety Harnesses', 'quantity' => 15, 'unit_price' => 2500, 'specification' => 'Full body safety harnesses'],
                ['name' => 'Safety Gloves', 'quantity' => 50, 'unit_price' => 300, 'specification' => 'Cut-resistant work gloves'],
                ['name' => 'Safety Shoes', 'quantity' => 30, 'unit_price' => 1500, 'specification' => 'Steel toe safety shoes'],
                ['name' => 'Cutting Tools Set', 'quantity' => 10, 'unit_price' => 2500, 'specification' => 'Professional cutting tool kit'],
            ],
            'Emergency Backup Generator' => [
                ['name' => 'Generator (75kVA)', 'quantity' => 1, 'unit_price' => 25000, 'specification' => 'Diesel generator with automatic switching'],
            ],
            'Raw Materials for Structure' => [
                ['name' => 'Steel Beams (I-Section)', 'quantity' => 500, 'unit_price' => 450, 'specification' => 'Mild steel I-beams, 50kg/m'],
                ['name' => 'Concrete Mix (Ready Mix)', 'quantity' => 100, 'unit_price' => 2800, 'specification' => 'M25 grade ready mix concrete'],
                ['name' => 'Rebar (12mm)', 'quantity' => 2000, 'unit_price' => 60, 'specification' => 'High tensile steel reinforcement bars'],
                ['name' => 'Foundation Bolts', 'quantity' => 400, 'unit_price' => 85, 'specification' => 'Anchor bolts for mounting'],
            ],
            'Cable Management System' => [
                ['name' => 'Cable Trays (200mm)', 'quantity' => 200, 'unit_price' => 180, 'specification' => 'Galvanized steel cable tray'],
                ['name' => 'PVC Conduit (50mm)', 'quantity' => 500, 'unit_price' => 25, 'specification' => 'Rigid PVC conduit'],
                ['name' => 'Junction Boxes', 'quantity' => 50, 'unit_price' => 350, 'specification' => 'IP65 waterproof junction boxes'],
            ],
            'Consumable Supplies' => [
                ['name' => 'Office Supplies Pack', 'quantity' => 5, 'unit_price' => 2000, 'specification' => 'Complete office supplies kit'],
                ['name' => 'Hardware Fasteners Kit', 'quantity' => 20, 'unit_price' => 850, 'specification' => 'Mixed nuts, bolts, washers'],
                ['name' => 'Electrical Tape', 'quantity' => 100, 'unit_price' => 120, 'specification' => 'Insulating electrical tape'],
            ],
            'Quality Control Instruments' => [
                ['name' => 'Digital Multimeter', 'quantity' => 10, 'unit_price' => 3500, 'specification' => 'Professional digital multimeter'],
                ['name' => 'Insulation Tester', 'quantity' => 3, 'unit_price' => 8500, 'specification' => 'Megger insulation resistance tester'],
                ['name' => 'Current Clamp Meter', 'quantity' => 5, 'unit_price' => 4500, 'specification' => 'AC/DC current clamp meter'],
                ['name' => 'Solar Panel Tester', 'quantity' => 2, 'unit_price' => 12000, 'specification' => 'IV curve tracer for solar panels'],
            ],
        ];

        foreach ($requestsData as $requestData) {
            $requestData['project_id'] = count($projects) > 0 ? fake()->randomElement($projects) : null;
            $requestData['requested_by'] = fake()->randomElement($users);
            
            $materialRequest = MaterialRequest::create($requestData);

            // Add materials if specified
            $requestTitle = $requestData['title'];
            if (isset($materialSpecs[$requestTitle])) {
                foreach ($materialSpecs[$requestTitle] as $materialData) {
                    $materialData['material_request_id'] = $materialRequest->id;
                    $materialData['description'] = $materialData['specification'];
                    $materialData['unit'] = 'piece';
                    $materialData['approved_quantity'] = $materialData['quantity'];
                    $materialData['received_quantity'] = $materialData['quantity'];
                    $materialData['consumed_quantity'] = 0;
                    $materialData['remaining_quantity'] = $materialData['quantity'];
                    $materialData['total_price'] = $materialData['quantity'] * $materialData['unit_price'];
                    $materialData['status'] = $materialRequest->status === 'completed' ? 'consumed' : ($materialRequest->status === 'approved' ? 'received' : 'requested');
                    
                    Material::create($materialData);
                }
            }
        }
    }
}
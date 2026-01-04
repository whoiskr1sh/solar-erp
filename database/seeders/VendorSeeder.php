<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\User;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        $vendors = [
            [
                'name' => 'SolarTech Solutions',
                'company' => 'SolarTech Solutions Pvt Ltd',
                'contact_person' => 'Rajesh Kumar',
                'email' => 'rajesh@solartech.com',
                'phone' => '011-23456789',
                'address' => '123 Industrial Area, Phase 2, Gurgaon, Haryana 122001',
                'gst_number' => '07AABCU9603R1ZX',
                'pan_number' => 'AABCU9603R',
                'status' => 'active',
                'credit_limit' => 500000,
                'payment_terms' => 30,
                'notes' => 'Reliable supplier for solar panels and inverters',
                'created_by' => $users->first()->id,
            ],
            [
                'name' => 'Green Energy Contractors',
                'company' => 'Green Energy Contractors',
                'contact_person' => 'Priya Sharma',
                'email' => 'priya@greenenergy.com',
                'phone' => '022-34567890',
                'address' => '456 Business Park, Andheri East, Mumbai, Maharashtra 400069',
                'gst_number' => '27AABCE1234F1Z5',
                'pan_number' => 'AABCE1234F',
                'status' => 'active',
                'credit_limit' => 750000,
                'payment_terms' => 45,
                'notes' => 'Expert installation team for commercial projects',
                'created_by' => $users->first()->id,
            ],
            [
                'name' => 'PowerMax Services',
                'company' => 'PowerMax Services Ltd',
                'contact_person' => 'Amit Singh',
                'email' => 'amit@powermax.com',
                'phone' => '080-45678901',
                'address' => '789 Tech Hub, Electronic City, Bangalore, Karnataka 560100',
                'gst_number' => '29AABCF5678G2H6',
                'pan_number' => 'AABCF5678G',
                'status' => 'active',
                'credit_limit' => 300000,
                'payment_terms' => 15,
                'notes' => 'Maintenance and service provider',
                'created_by' => $users->first()->id,
            ],
            [
                'name' => 'EcoSolar Supplies',
                'company' => 'EcoSolar Supplies',
                'contact_person' => 'Sunita Patel',
                'email' => 'sunita@ecosolar.com',
                'phone' => '079-56789012',
                'address' => '321 Green Valley, Satellite, Ahmedabad, Gujarat 380015',
                'gst_number' => '24AABCG9012H3I7',
                'pan_number' => 'AABCG9012H',
                'status' => 'active',
                'credit_limit' => 400000,
                'payment_terms' => 30,
                'notes' => 'Specialized in residential solar components',
                'created_by' => $users->first()->id,
            ],
            [
                'name' => 'SunRise Installations',
                'company' => 'SunRise Installations Pvt Ltd',
                'contact_person' => 'Vikram Reddy',
                'email' => 'vikram@sunrise.com',
                'phone' => '040-67890123',
                'address' => '654 Innovation District, HITEC City, Hyderabad, Telangana 500081',
                'gst_number' => '36AABCH3456I4J8',
                'pan_number' => 'AABCH3456I',
                'status' => 'inactive',
                'credit_limit' => 600000,
                'payment_terms' => 30,
                'notes' => 'Currently under review for quality standards',
                'created_by' => $users->first()->id,
            ],
        ];

        foreach ($vendors as $vendorData) {
            Vendor::create($vendorData);
        }
    }
}

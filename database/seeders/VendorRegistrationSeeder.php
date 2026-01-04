<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VendorRegistration;
use App\Models\User;
use Carbon\Carbon;

class VendorRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Skipping VendorRegistrationSeeder: No users found. Please run UserSeeder first.');
            return;
        }

        $statuses = ['pending', 'under_review', 'approved', 'rejected', 'suspended'];
        $registrationTypes = ['Individual', 'Partnership', 'Company', 'LLP'];
        $cities = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Pune', 'Ahmedabad'];
        $states = ['Maharashtra', 'Delhi', 'Karnataka', 'Tamil Nadu', 'West Bengal', 'Telangana', 'Gujarat', 'Rajasthan'];
        
        $businessCategories = [
            ['Solar Equipment', 'Renewable Energy', 'Electrical'],
            ['Construction', 'Engineering', 'Project Management'],
            ['Manufacturing', 'Supply Chain', 'Logistics'],
            ['Technology', 'Software', 'Automation'],
            ['Consulting', 'Services', 'Maintenance'],
        ];

        $companyNames = [
            'SolarTech Solutions', 'Green Energy Corp', 'Renewable Power Ltd', 'EcoSolar Systems',
            'SunPower Technologies', 'Clean Energy Solutions', 'Solar Innovations', 'GreenTech Industries',
            'PowerMax Solar', 'SunRise Energy', 'EcoPower Systems', 'Solar Dynamics'
        ];

        for ($i = 0; $i < 8; $i++) {
            $status = $statuses[array_rand($statuses)];
            $registrationType = $registrationTypes[array_rand($registrationTypes)];
            $city = $cities[array_rand($cities)];
            $state = $states[array_rand($states)];
            $categories = $businessCategories[array_rand($businessCategories)];
            $companyName = $companyNames[array_rand($companyNames)];
            
            $registrationDate = Carbon::now()->subDays(rand(1, 60));

            $vr = VendorRegistration::create([
                'registration_number' => 'VR-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'company_name' => $companyName,
                'contact_person' => 'Mr. ' . ['Raj', 'Kumar', 'Singh', 'Patel', 'Sharma', 'Gupta', 'Verma', 'Yadav'][array_rand(['Raj', 'Kumar', 'Singh', 'Patel', 'Sharma', 'Gupta', 'Verma', 'Yadav'])],
                'email' => 'contact@' . strtolower(str_replace(' ', '', $companyName)) . '.com',
                'phone' => '+91-' . rand(7000000000, 9999999999),
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $companyName)) . '.com',
                'address' => rand(10, 99) . ', Industrial Area, ' . $city,
                'city' => $city,
                'state' => $state,
                'pincode' => rand(100000, 999999),
                'country' => 'India',
                'gst_number' => rand(10, 99) . 'ABCDE' . rand(1000, 9999) . 'F' . rand(1, 9) . 'Z' . rand(10, 99),
                'pan_number' => 'ABCDE' . rand(1000, 9999) . 'F',
                'registration_type' => $registrationType,
                'registration_date' => $registrationDate,
                'business_description' => 'Leading provider of ' . implode(', ', $categories) . ' solutions for solar energy projects.',
                'categories' => $categories,
                'documents' => [
                    'company_registration.pdf',
                    'gst_certificate.pdf',
                    'pan_card.pdf',
                    'bank_details.pdf',
                ],
                'status' => $status,
            ]);

            // Add review details if status is not pending
            if (!in_array($status, ['pending'])) {
                $reviewer = $users->random();
                $vr->update([
                    'reviewed_by' => $reviewer->id,
                    'reviewed_at' => Carbon::now()->subDays(rand(1, 20)),
                    'review_notes' => $status === 'approved' ? 'All documents verified. Approved for vendor registration.' : null,
                    'rejection_reason' => $status === 'rejected' ? 'Incomplete documentation. Please resubmit with all required documents.' : null,
                ]);
            }
        }

        $this->command->info('Vendor Registrations seeded successfully!');
    }
}
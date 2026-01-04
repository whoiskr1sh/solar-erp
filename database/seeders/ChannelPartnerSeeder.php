<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChannelPartner;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChannelPartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there's at least one user
        $user = User::first() ?? User::factory()->create();

        // Clear existing partners to prevent duplicates on re-run
        // Disable foreign key checks temporarily to allow truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('channel_partners')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $partnersData = [
            [
                'company_name' => 'SolarTech Solutions',
                'contact_person' => 'Rajesh Kumar',
                'email' => 'rajesh@solartech.com',
                'phone' => '9876543210',
                'alternate_phone' => '9876543211',
                'address' => '123, MG Road, Sector 15',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'country' => 'India',
                'gst_number' => '27ABCDE1234F1Z5',
                'pan_number' => 'ABCDE1234F',
                'website' => 'https://solartech.com',
                'partner_type' => 'distributor',
                'status' => 'active',
                'commission_rate' => 12.50,
                'credit_limit' => 500000.00,
                'outstanding_amount' => 125000.00,
                'agreement_start_date' => now()->subMonths(6),
                'agreement_end_date' => now()->addMonths(18),
                'specializations' => ['Residential Solar', 'Commercial Solar', 'Maintenance'],
                'notes' => 'Top performing distributor with excellent customer service.',
                'bank_details' => [
                    'account_holder' => 'SolarTech Solutions',
                    'account_number' => '1234567890',
                    'ifsc_code' => 'SBIN0001234',
                    'bank_name' => 'State Bank of India'
                ],
                'documents' => [
                    'agreement' => 'agreement_solartech.pdf',
                    'gst_certificate' => 'gst_solartech.pdf',
                    'pan_card' => 'pan_solartech.pdf'
                ],
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'company_name' => 'Green Energy Partners',
                'contact_person' => 'Priya Sharma',
                'email' => 'priya@greenenergy.com',
                'phone' => '9876543220',
                'alternate_phone' => '9876543221',
                'address' => '456, Park Street, Connaught Place',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'pincode' => '110001',
                'country' => 'India',
                'gst_number' => '07FGHIJ5678K2L6',
                'pan_number' => 'FGHIJ5678K',
                'website' => 'https://greenenergy.com',
                'partner_type' => 'dealer',
                'status' => 'active',
                'commission_rate' => 10.00,
                'credit_limit' => 300000.00,
                'outstanding_amount' => 75000.00,
                'agreement_start_date' => now()->subMonths(3),
                'agreement_end_date' => now()->addMonths(21),
                'specializations' => ['Residential Solar', 'Solar Water Heaters'],
                'notes' => 'Specializes in residential solar installations.',
                'bank_details' => [
                    'account_holder' => 'Green Energy Partners',
                    'account_number' => '2345678901',
                    'ifsc_code' => 'HDFC0002345',
                    'bank_name' => 'HDFC Bank'
                ],
                'documents' => [
                    'agreement' => 'agreement_greenenergy.pdf',
                    'gst_certificate' => 'gst_greenenergy.pdf'
                ],
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'company_name' => 'EcoPower Installations',
                'contact_person' => 'Amit Patel',
                'email' => 'amit@ecopower.com',
                'phone' => '9876543230',
                'address' => '789, Brigade Road, Koramangala',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'pincode' => '560034',
                'country' => 'India',
                'gst_number' => '29KLMNO9012P3M7',
                'pan_number' => 'KLMNO9012P',
                'website' => 'https://ecopower.com',
                'partner_type' => 'installer',
                'status' => 'pending',
                'commission_rate' => 8.50,
                'credit_limit' => 200000.00,
                'outstanding_amount' => 0.00,
                'agreement_start_date' => now()->addDays(7),
                'agreement_end_date' => now()->addMonths(24),
                'specializations' => ['Commercial Solar', 'Industrial Solar', 'Installation'],
                'notes' => 'New installer partner, awaiting final approval.',
                'bank_details' => [
                    'account_holder' => 'EcoPower Installations',
                    'account_number' => '3456789012',
                    'ifsc_code' => 'ICIC0003456',
                    'bank_name' => 'ICICI Bank'
                ],
                'documents' => [
                    'agreement' => 'agreement_ecopower.pdf',
                    'gst_certificate' => 'gst_ecopower.pdf',
                    'pan_card' => 'pan_ecopower.pdf'
                ],
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'company_name' => 'Solar Consultants India',
                'contact_person' => 'Dr. Sunita Reddy',
                'email' => 'sunita@solarconsultants.com',
                'phone' => '9876543240',
                'alternate_phone' => '9876543241',
                'address' => '321, Anna Salai, Teynampet',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'pincode' => '600018',
                'country' => 'India',
                'gst_number' => '33QRSTU3456V4N8',
                'pan_number' => 'QRSTU3456V',
                'website' => 'https://solarconsultants.com',
                'partner_type' => 'consultant',
                'status' => 'active',
                'commission_rate' => 15.00,
                'credit_limit' => 100000.00,
                'outstanding_amount' => 25000.00,
                'agreement_start_date' => now()->subMonths(12),
                'agreement_end_date' => now()->addMonths(12),
                'specializations' => ['Solar Consulting', 'Project Planning', 'Technical Support'],
                'notes' => 'Expert solar consultant with 10+ years experience.',
                'bank_details' => [
                    'account_holder' => 'Solar Consultants India',
                    'account_number' => '4567890123',
                    'ifsc_code' => 'AXIS0004567',
                    'bank_name' => 'Axis Bank'
                ],
                'documents' => [
                    'agreement' => 'agreement_solarconsultants.pdf',
                    'gst_certificate' => 'gst_solarconsultants.pdf'
                ],
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'company_name' => 'PowerMax Distributors',
                'contact_person' => 'Vikram Singh',
                'email' => 'vikram@powermax.com',
                'phone' => '9876543250',
                'address' => '654, Salt Lake, Sector V',
                'city' => 'Kolkata',
                'state' => 'West Bengal',
                'pincode' => '700091',
                'country' => 'India',
                'gst_number' => '19VWXYZ7890W5O9',
                'pan_number' => 'VWXYZ7890W',
                'website' => 'https://powermax.com',
                'partner_type' => 'distributor',
                'status' => 'suspended',
                'commission_rate' => 11.00,
                'credit_limit' => 400000.00,
                'outstanding_amount' => 200000.00,
                'agreement_start_date' => now()->subMonths(18),
                'agreement_end_date' => now()->subMonths(6),
                'specializations' => ['Commercial Solar', 'Industrial Solar'],
                'notes' => 'Suspended due to payment issues. Agreement expired.',
                'bank_details' => [
                    'account_holder' => 'PowerMax Distributors',
                    'account_number' => '5678901234',
                    'ifsc_code' => 'PNB0005678',
                    'bank_name' => 'Punjab National Bank'
                ],
                'documents' => [
                    'agreement' => 'agreement_powermax.pdf',
                    'gst_certificate' => 'gst_powermax.pdf'
                ],
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ],
            [
                'company_name' => 'SunRise Solar',
                'contact_person' => 'Neha Gupta',
                'email' => 'neha@sunrise.com',
                'phone' => '9876543260',
                'address' => '987, MG Road, Banjara Hills',
                'city' => 'Hyderabad',
                'state' => 'Telangana',
                'pincode' => '500034',
                'country' => 'India',
                'gst_number' => '36ABCD1234E6P1',
                'pan_number' => 'ABCD1234E',
                'website' => 'https://sunrise.com',
                'partner_type' => 'dealer',
                'status' => 'inactive',
                'commission_rate' => 9.50,
                'credit_limit' => 150000.00,
                'outstanding_amount' => 45000.00,
                'agreement_start_date' => now()->subMonths(9),
                'agreement_end_date' => now()->addMonths(15),
                'specializations' => ['Residential Solar', 'Solar Inverters'],
                'notes' => 'Temporarily inactive due to business restructuring.',
                'bank_details' => [
                    'account_holder' => 'SunRise Solar',
                    'account_number' => '6789012345',
                    'ifsc_code' => 'KOTAK0006789',
                    'bank_name' => 'Kotak Mahindra Bank'
                ],
                'documents' => [
                    'agreement' => 'agreement_sunrise.pdf',
                    'gst_certificate' => 'gst_sunrise.pdf'
                ],
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ],
        ];

        foreach ($partnersData as $data) {
            $partner = ChannelPartner::create($data);
            // Ensure partner_code is generated
            $partner->save();
        }

        $this->command->info('ChannelPartnerSeeder completed successfully!');
    }
}
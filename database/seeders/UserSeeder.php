<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users for each role
        $users = [
            // SUPER ADMIN
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543210',
                'department' => 'IT',
                'designation' => 'Super Administrator',
                'is_active' => true,
                'employee_id' => 'EMP001',
                'joining_date' => now()->subYear(),
                'salary' => 150000,
                'address' => 'Corporate Office, Mumbai',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543211',
                'role' => 'SUPER ADMIN'
            ],

            // SALES MANAGER
            [
                'name' => 'Rajesh Kumar',
                'email' => 'sales.manager@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543212',
                'department' => 'Sales',
                'designation' => 'Sales Manager',
                'is_active' => true,
                'employee_id' => 'EMP002',
                'joining_date' => now()->subMonths(8),
                'salary' => 120000,
                'address' => 'Sales Office, Delhi',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543213',
                'role' => 'SALES MANAGER'
            ],

            // TELE SALES
            [
                'name' => 'Priya Sharma',
                'email' => 'tele.sales@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543214',
                'department' => 'Sales',
                'designation' => 'Tele Sales Executive',
                'is_active' => true,
                'employee_id' => 'EMP003',
                'joining_date' => now()->subMonths(6),
                'salary' => 45000,
                'address' => 'Call Center, Mumbai',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543215',
                'role' => 'TELE SALES'
            ],

            // FIELD SALES
            [
                'name' => 'Amit Singh',
                'email' => 'field.sales@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543216',
                'department' => 'Sales',
                'designation' => 'Field Sales Executive',
                'is_active' => true,
                'employee_id' => 'EMP004',
                'joining_date' => now()->subMonths(5),
                'salary' => 55000,
                'address' => 'Field Office, Bangalore',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543217',
                'role' => 'FIELD SALES'
            ],

            // PROJECT MANAGER
            [
                'name' => 'Vikram Patel',
                'email' => 'project.manager@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543218',
                'department' => 'Projects',
                'designation' => 'Project Manager',
                'is_active' => true,
                'employee_id' => 'EMP005',
                'joining_date' => now()->subMonths(10),
                'salary' => 100000,
                'address' => 'Project Office, Pune',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543219',
                'role' => 'PROJECT MANAGER'
            ],

            // PROJECT ENGINEER
            [
                'name' => 'Suresh Reddy',
                'email' => 'project.engineer@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543220',
                'department' => 'Projects',
                'designation' => 'Project Engineer',
                'is_active' => true,
                'employee_id' => 'EMP006',
                'joining_date' => now()->subMonths(7),
                'salary' => 70000,
                'address' => 'Site Office, Hyderabad',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543221',
                'role' => 'PROJECT ENGINEER'
            ],

            // LIASONING EXECUTIVE
            [
                'name' => 'Meera Joshi',
                'email' => 'liaisoning@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543222',
                'department' => 'Liaisoning',
                'designation' => 'Liaisoning Executive',
                'is_active' => true,
                'employee_id' => 'EMP007',
                'joining_date' => now()->subMonths(4),
                'salary' => 60000,
                'address' => 'Regulatory Office, Delhi',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543223',
                'role' => 'LIASONING EXECUTIVE'
            ],

            // QUALITY ENGINEER
            [
                'name' => 'Ravi Kumar',
                'email' => 'quality.engineer@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543224',
                'department' => 'Quality',
                'designation' => 'Quality Engineer',
                'is_active' => true,
                'employee_id' => 'EMP008',
                'joining_date' => now()->subMonths(6),
                'salary' => 65000,
                'address' => 'Quality Lab, Chennai',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543225',
                'role' => 'QUALITY ENGINEER'
            ],

            // PURCHASE MANAGER/EXECUTIVE
            [
                'name' => 'Deepak Gupta',
                'email' => 'purchase.manager@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543226',
                'department' => 'Purchase',
                'designation' => 'Purchase Manager',
                'is_active' => true,
                'employee_id' => 'EMP009',
                'joining_date' => now()->subMonths(9),
                'salary' => 90000,
                'address' => 'Purchase Office, Mumbai',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543227',
                'role' => 'PURCHASE MANAGER/EXECUTIVE'
            ],

            // ACCOUNT EXECUTIVE
            [
                'name' => 'Sunita Agarwal',
                'email' => 'account.executive@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543228',
                'department' => 'Accounts',
                'designation' => 'Account Executive',
                'is_active' => true,
                'employee_id' => 'EMP010',
                'joining_date' => now()->subMonths(8),
                'salary' => 75000,
                'address' => 'Accounts Office, Kolkata',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543229',
                'role' => 'ACCOUNT EXECUTIVE'
            ],

            // STORE EXECUTIVE
            [
                'name' => 'Manoj Verma',
                'email' => 'store.executive@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543230',
                'department' => 'Store',
                'designation' => 'Store Executive',
                'is_active' => true,
                'employee_id' => 'EMP011',
                'joining_date' => now()->subMonths(5),
                'salary' => 50000,
                'address' => 'Warehouse, Ahmedabad',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543231',
                'role' => 'STORE EXECUTIVE'
            ],

            // SERVICE ENGINEER
            [
                'name' => 'Kiran Nair',
                'email' => 'service.engineer@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543232',
                'department' => 'Service',
                'designation' => 'Service Engineer',
                'is_active' => true,
                'employee_id' => 'EMP012',
                'joining_date' => now()->subMonths(6),
                'salary' => 60000,
                'address' => 'Service Center, Kochi',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543233',
                'role' => 'SERVICE ENGINEER'
            ],

            // HR MANAGER
            [
                'name' => 'Anita Desai',
                'email' => 'hr.manager@solarerp.com',
                'password' => Hash::make('password123'),
                'phone' => '+91-9876543234',
                'department' => 'HR',
                'designation' => 'HR Manager',
                'is_active' => true,
                'employee_id' => 'EMP013',
                'joining_date' => now()->subMonths(12),
                'salary' => 110000,
                'address' => 'HR Office, Mumbai',
                'emergency_contact' => 'Emergency Contact',
                'emergency_phone' => '+91-9876543235',
                'role' => 'HR MANAGER'
            ],
        ];

        // Create users and assign roles
        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']); // Remove role from user data
            
            // Check if user already exists by email or employee_id
            $existingUser = User::where('email', $userData['email'])
                              ->orWhere('employee_id', $userData['employee_id'])
                              ->first();
            
            if ($existingUser) {
                // Update existing user
                $existingUser->update($userData);
                $existingUser->syncRoles([$role]);
                $this->command->info("Updated user: {$existingUser->name} ({$existingUser->email}) with role: {$role}");
            } else {
                // Create new user
                $user = User::create($userData);
                $user->assignRole($role);
                $this->command->info("Created user: {$user->name} ({$user->email}) with role: {$role}");
            }
        }

        $this->command->info('All users created successfully!');
    }
}
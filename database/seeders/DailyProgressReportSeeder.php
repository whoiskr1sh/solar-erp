<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyProgressReport;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class DailyProgressReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some projects and users for seeding
        $projects = Project::take(5)->get();
        $users = User::take(3)->get();
        
        if ($projects->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No projects or users found. Please seed projects and users first.');
            return;
        }

        $sampleReports = [
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(5),
                'weather_condition' => 'sunny',
                'work_performed' => 'Completed foundation work for Block A. Installed rebar reinforcement and poured concrete foundation. Quality check passed for all sections.',
                'work_hours' => 8.5,
                'workers_present' => 12,
                'materials_used' => 'Concrete (50 bags), Rebar (200 kg), Cement (25 bags), Sand (2 tons)',
                'equipment_used' => 'Concrete mixer, Crane, Vibrator, Measuring tools',
                'challenges_faced' => 'Heavy rain in the morning delayed start by 2 hours. Had to cover work area with tarpaulin.',
                'next_day_plan' => 'Continue with foundation work for Block B. Start wall construction for Block A.',
                'status' => 'approved',
                'submitted_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => Carbon::now()->subDays(4),
                'remarks' => 'Good progress despite weather challenges. Quality maintained.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(4),
                'weather_condition' => 'cloudy',
                'work_performed' => 'Started electrical wiring installation. Completed main distribution panel setup and began cable laying for ground floor.',
                'work_hours' => 7.0,
                'workers_present' => 8,
                'materials_used' => 'Electrical cables (500m), Switches (50), Outlets (30), Distribution panel (1)',
                'equipment_used' => 'Cable pulling machine, Multimeter, Wire strippers',
                'challenges_faced' => 'Some cable lengths were shorter than required. Had to order additional materials.',
                'next_day_plan' => 'Complete ground floor wiring. Start first floor electrical work.',
                'status' => 'pending',
                'submitted_by' => $users->random()->id,
                'remarks' => 'Material shortage issue needs to be addressed.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(3),
                'weather_condition' => 'rainy',
                'work_performed' => 'Plumbing installation for bathrooms completed. Installed water supply lines and drainage systems for all units.',
                'work_hours' => 6.5,
                'workers_present' => 6,
                'materials_used' => 'PVC pipes (200m), Fittings (100), Valves (20), Water meters (10)',
                'equipment_used' => 'Pipe cutter, Welding machine, Pressure tester',
                'challenges_faced' => 'Rain made outdoor work difficult. Had to focus on indoor plumbing only.',
                'next_day_plan' => 'Test all plumbing systems. Start bathroom fixture installation.',
                'status' => 'approved',
                'submitted_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => Carbon::now()->subDays(2),
                'remarks' => 'Excellent work quality. All pressure tests passed.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(2),
                'weather_condition' => 'sunny',
                'work_performed' => 'Painting work started for completed rooms. Applied primer coat to walls and ceilings. Started color painting for bedrooms.',
                'work_hours' => 8.0,
                'workers_present' => 10,
                'materials_used' => 'Primer paint (20 liters), Color paint (30 liters), Brushes (15), Rollers (10)',
                'equipment_used' => 'Paint sprayer, Ladders, Drop cloths',
                'challenges_faced' => 'Some wall surfaces needed additional preparation before painting.',
                'next_day_plan' => 'Complete bedroom painting. Start living room and kitchen painting.',
                'status' => 'pending',
                'submitted_by' => $users->random()->id,
                'remarks' => 'Wall preparation took longer than expected.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(1),
                'weather_condition' => 'sunny',
                'work_performed' => 'Flooring installation completed for ground floor. Installed ceramic tiles in bathrooms and marble flooring in living areas.',
                'work_hours' => 9.0,
                'workers_present' => 15,
                'materials_used' => 'Ceramic tiles (500 sq ft), Marble tiles (300 sq ft), Adhesive (50 kg), Grout (20 kg)',
                'equipment_used' => 'Tile cutter, Level, Spacers, Grout float',
                'challenges_faced' => 'Some tiles had size variations. Had to sort and match carefully.',
                'next_day_plan' => 'Start first floor flooring. Complete tile work in remaining bathrooms.',
                'status' => 'approved',
                'submitted_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => Carbon::now()->subHours(12),
                'remarks' => 'Outstanding work quality. Flooring looks excellent.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now(),
                'weather_condition' => 'cloudy',
                'work_performed' => 'Started HVAC installation. Installed main air conditioning units and began ductwork installation for ground floor.',
                'work_hours' => 7.5,
                'workers_present' => 9,
                'materials_used' => 'AC units (5), Ductwork (100m), Insulation (50m), Mounting brackets (20)',
                'equipment_used' => 'Drill machine, Duct cutter, Measuring tape',
                'challenges_faced' => 'Some mounting locations needed structural modifications.',
                'next_day_plan' => 'Complete ground floor ductwork. Start first floor HVAC installation.',
                'status' => 'pending',
                'submitted_by' => $users->random()->id,
                'remarks' => 'Structural modifications required for proper installation.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(6),
                'weather_condition' => 'stormy',
                'work_performed' => 'Site preparation and excavation work. Cleared site area and started foundation excavation.',
                'work_hours' => 6.0,
                'workers_present' => 8,
                'materials_used' => 'Excavation equipment rental, Safety barriers, Marking paint',
                'equipment_used' => 'Excavator, Dump truck, Safety equipment',
                'challenges_faced' => 'Heavy storm caused delays. Site became muddy and unsafe.',
                'next_day_plan' => 'Complete excavation work. Start foundation preparation.',
                'status' => 'rejected',
                'submitted_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => Carbon::now()->subDays(5),
                'remarks' => 'Work quality below standards due to weather conditions. Need to redo excavation.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(7),
                'weather_condition' => 'foggy',
                'work_performed' => 'Roofing work completed for main building. Installed waterproofing membrane and started tile installation.',
                'work_hours' => 8.5,
                'workers_present' => 12,
                'materials_used' => 'Roof tiles (1000 sq ft), Waterproofing membrane (500 sq ft), Adhesive (100 kg)',
                'equipment_used' => 'Crane, Tile cutter, Safety harnesses',
                'challenges_faced' => 'Fog reduced visibility. Had to work more carefully.',
                'next_day_plan' => 'Complete roof tile installation. Start gutter installation.',
                'status' => 'approved',
                'submitted_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => Carbon::now()->subDays(6),
                'remarks' => 'Good progress despite visibility issues. Safety maintained.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(8),
                'weather_condition' => 'sunny',
                'work_performed' => 'Landscaping work started. Prepared soil and began planting trees and shrubs around the building.',
                'work_hours' => 6.5,
                'workers_present' => 6,
                'materials_used' => 'Soil (5 tons), Trees (20), Shrubs (50), Fertilizer (100 kg)',
                'equipment_used' => 'Shovels, Wheelbarrow, Watering system',
                'challenges_faced' => 'Some plants were damaged during transport.',
                'next_day_plan' => 'Complete tree planting. Start lawn preparation.',
                'status' => 'pending',
                'submitted_by' => $users->random()->id,
                'remarks' => 'Need to replace damaged plants.',
            ],
            [
                'project_id' => $projects->random()->id,
                'report_date' => Carbon::now()->subDays(9),
                'weather_condition' => 'cloudy',
                'work_performed' => 'Final inspection and quality check completed. All systems tested and approved for handover.',
                'work_hours' => 8.0,
                'workers_present' => 15,
                'materials_used' => 'Testing equipment, Documentation materials',
                'equipment_used' => 'Multimeter, Pressure tester, Inspection tools',
                'challenges_faced' => 'Minor issues found in electrical system. Fixed immediately.',
                'next_day_plan' => 'Prepare handover documentation. Schedule client inspection.',
                'status' => 'approved',
                'submitted_by' => $users->random()->id,
                'approved_by' => $users->random()->id,
                'approved_at' => Carbon::now()->subDays(8),
                'remarks' => 'Project completed successfully. Ready for handover.',
            ],
        ];

        foreach ($sampleReports as $reportData) {
            DailyProgressReport::create($reportData);
        }

        $this->command->info('Daily Progress Reports seeded successfully!');
    }
}
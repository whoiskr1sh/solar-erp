<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $leads = Lead::all();
        $projects = Project::all();
        $users = User::all();
        
        $documents = [
            [
                'title' => 'Solar Panel Installation Proposal',
                'description' => 'Detailed proposal for residential solar panel installation including technical specifications and pricing.',
                'file_name' => 'solar_proposal_2024.pdf',
                'file_path' => 'documents/sample_proposal.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 2048576, // 2MB
                'category' => 'proposal',
                'status' => 'active',
                'lead_id' => $leads->first()->id ?? null,
                'project_id' => $projects->first()->id ?? null,
                'created_by' => $users->first()->id,
                'tags' => ['solar', 'proposal', 'residential'],
                'expiry_date' => now()->addDays(30),
            ],
            [
                'title' => 'Service Agreement Contract',
                'description' => 'Standard service agreement contract for solar panel maintenance and support.',
                'file_name' => 'service_agreement.docx',
                'file_path' => 'documents/service_contract.docx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'file_size' => 1536000, // 1.5MB
                'category' => 'contract',
                'status' => 'active',
                'lead_id' => $leads->skip(1)->first()->id ?? $leads->first()->id ?? null,
                'project_id' => null,
                'created_by' => $users->first()->id,
                'tags' => ['contract', 'service', 'maintenance'],
                'expiry_date' => now()->addDays(365),
            ],
            [
                'title' => 'Technical Specifications Document',
                'description' => 'Detailed technical specifications for commercial solar power system.',
                'file_name' => 'tech_specs_v2.pdf',
                'file_path' => 'documents/technical_specs.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 5120000, // 5MB
                'category' => 'technical_spec',
                'status' => 'active',
                'lead_id' => null,
                'project_id' => $projects->skip(1)->first()->id ?? $projects->first()->id ?? null,
                'created_by' => $users->skip(1)->first()->id ?? $users->first()->id,
                'tags' => ['technical', 'specifications', 'commercial'],
                'expiry_date' => null,
            ],
            [
                'title' => 'Monthly Performance Report',
                'description' => 'Monthly performance report showing energy generation and system efficiency.',
                'file_name' => 'performance_report_jan2024.xlsx',
                'file_path' => 'documents/performance_report.xlsx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'file_size' => 1024000, // 1MB
                'category' => 'report',
                'status' => 'active',
                'lead_id' => null,
                'project_id' => $projects->skip(2)->first()->id ?? $projects->first()->id ?? null,
                'created_by' => $users->first()->id,
                'tags' => ['report', 'performance', 'monthly'],
                'expiry_date' => null,
            ],
            [
                'title' => 'Client Presentation Deck',
                'description' => 'PowerPoint presentation for client meeting showcasing our solar solutions.',
                'file_name' => 'client_presentation.pptx',
                'file_path' => 'documents/client_presentation.pptx',
                'file_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'file_size' => 8192000, // 8MB
                'category' => 'presentation',
                'status' => 'draft',
                'lead_id' => $leads->skip(2)->first()->id ?? $leads->first()->id ?? null,
                'project_id' => null,
                'created_by' => $users->skip(1)->first()->id ?? $users->first()->id,
                'tags' => ['presentation', 'client', 'solar'],
                'expiry_date' => null,
            ],
            [
                'title' => 'Warranty Certificate',
                'description' => 'Warranty certificate for installed solar panels and equipment.',
                'file_name' => 'warranty_certificate.pdf',
                'file_path' => 'documents/warranty.pdf',
                'file_type' => 'application/pdf',
                'file_size' => 512000, // 512KB
                'category' => 'other',
                'status' => 'active',
                'lead_id' => null,
                'project_id' => $projects->skip(3)->first()->id ?? $projects->first()->id ?? null,
                'created_by' => $users->first()->id,
                'tags' => ['warranty', 'certificate', 'equipment'],
                'expiry_date' => now()->addDays(1825), // 5 years
            ],
        ];

        foreach ($documents as $documentData) {
            Document::create($documentData);
        }
    }
}

<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Lead::with(['assignedUser', 'creator'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'Email',
            'Company',
            'Address',
            'City',
            'State',
            'Pincode',
            'Industry',
            'Source',
            'Status',
            'Priority',
            'Estimated Value',
            'Expected Close Date',
            'Notes',
            'Assigned To',
            'Created By',
            'Created At',
            'Updated At'
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->name,
            $lead->phone,
            $lead->email,
            $lead->company,
            $lead->address,
            $lead->city,
            $lead->state,
            $lead->pincode,
            $lead->industry,
            $lead->source,
            $lead->status,
            $lead->priority,
            $lead->estimated_value,
            $lead->expected_close_date,
            $lead->notes,
            $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned',
            $lead->creator ? $lead->creator->name : 'System',
            $lead->created_at->format('Y-m-d H:i:s'),
            $lead->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
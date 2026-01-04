<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GRNExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $grns;

    public function __construct($grns)
    {
        $this->grns = $grns;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->grns;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'GRN Number',
            'Vendor',
            'Purchase Order',
            'Project',
            'GRN Date',
            'Received Date',
            'Status',
            'Total Amount',
            'Tax Amount',
            'Discount Amount',
            'Final Amount',
            'Delivery Address',
            'Notes',
            'Received By',
            'Verified By',
            'Verified At',
            'Created At',
        ];
    }

    /**
     * @param mixed $grn
     * @return array
     */
    public function map($grn): array
    {
        return [
            $grn->grn_number,
            $grn->vendor?->company ?? $grn->vendor?->name ?? 'N/A',
            $grn->purchaseOrder?->po_number ?? 'N/A',
            $grn->project?->name ?? 'N/A',
            $grn->grn_date->format('Y-m-d'),
            $grn->received_date->format('Y-m-d'),
            ucfirst($grn->status),
            $grn->total_amount,
            $grn->tax_amount,
            $grn->discount_amount,
            $grn->final_amount,
            $grn->delivery_address,
            $grn->notes ?? '',
            $grn->receivedBy?->name ?? 'N/A',
            $grn->verifiedBy?->name ?? 'N/A',
            $grn->verified_at?->format('Y-m-d H:i:s') ?? '',
            $grn->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}

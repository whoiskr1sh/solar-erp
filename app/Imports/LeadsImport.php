<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Lead([
            'name' => $row['name'],
            'email' => $row['email'] ?? null,
            'phone' => (string) $row['phone'], // Keep as string
            'company' => $row['company'] ?? null,
            'address' => $row['address'] ?? null,
            'source' => $row['source'],
            'status' => $row['status'],
            'priority' => $row['priority'],
            'estimated_value' => $row['estimated_value'] ?? null,
            'expected_close_date' => $row['expected_close_date'] ?? null,
            'notes' => $row['notes'] ?? null,
            'assigned_user_id' => $row['assigned_user_id'] ?? null,
            'created_by' => Auth::id(),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'source' => 'required|in:website,indiamart,justdial,meta_ads,referral,cold_call,other',
            'status' => 'required|in:interested,not_interested,partially_interested,not_reachable,not_answered',
            'priority' => 'required|in:low,medium,high,urgent',
            'estimated_value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}

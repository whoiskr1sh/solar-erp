<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'slip_number',
        'payroll_month',
        'payroll_year',
        'basic_salary',
        'hra',
        'da',
        'allowances',
        'pf',
        'esi',
        'tax',
        'other_deductions',
        'net_salary',
        'generated_date',
        'status',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'da' => 'decimal:2',
        'allowances' => 'decimal:2',
        'pf' => 'decimal:2',
        'esi' => 'decimal:2',
        'tax' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'generated_date' => 'date',
    ];

    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'generated' => 'bg-blue-100 text-blue-800',
            'sent' => 'bg-green-100 text-green-800',
            'downloaded' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    // Accessor for formatted net salary
    public function getFormattedNetSalaryAttribute()
    {
        return '₹' . number_format($this->net_salary, 2);
    }

    // Accessor for gross salary
    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary + $this->hra + $this->da + $this->allowances;
    }

    // Accessor for total deductions
    public function getTotalDeductionsAttribute()
    {
        return $this->pf + $this->esi + $this->tax + $this->other_deductions;
    }
}
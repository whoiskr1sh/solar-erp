<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id', 'category', 'name', 'path', 'status', 'related_to', 'created_by'
    ];

    // Relationships can be added here if needed
}

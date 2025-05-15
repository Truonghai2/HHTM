<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlateRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'license_plate',
        'vehicle_type',
    ];
}

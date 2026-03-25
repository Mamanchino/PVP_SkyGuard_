<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    protected $fillable = [
        'serial_number',
        'activation_code',
        'status',
        'battery_level',
        'is_registered',
        'user_id'
    ];
}
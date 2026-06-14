<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DroneNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'drone_id',
        'user_id',
        'type',
        'message',
        'confidence',
        'is_read',
    ];

    protected $casts = [
        'is_read'    => 'boolean',
        'confidence' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
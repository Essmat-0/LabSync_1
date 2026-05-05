<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user__id',
        'equipment_id',
        'safety_log_id',
        'grant_id',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function equipment(){
        return $this->belongsTo(Equipment::class);
    }
    
    public function safetyLog(){
        return $this->belongsTo(SafetyLog::class);
    }

    public function grant(){
        return $this->belongsTo(Grant::class);
    }
    
}
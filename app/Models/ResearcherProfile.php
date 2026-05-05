<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearcherProfile extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'academic_level',
        'pis_id',
    ];
}
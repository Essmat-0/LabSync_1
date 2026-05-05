<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyLog extends Model
{
    //

    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }
}
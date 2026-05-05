<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentSession extends Model
{



    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
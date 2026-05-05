<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model // AKA BillingRecord Class
{
    


    public function equipmentSession(){
        return $this->belongsTo(EquipmentSession::class);
    }
}
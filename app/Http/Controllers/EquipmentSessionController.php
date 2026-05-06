<?php

namespace App\Http\Controllers;

use App\Models\EquipmentSession;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EquipmentSessionController extends Controller
{
    public function storeSessionTime(Request $request)
    {
        return EquipmentSession::create([
            'user_id' => $request->user_id,
            'equipment_id' => $request->equipment_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,

        ]);
    }
}
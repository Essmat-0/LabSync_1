<?php

namespace App\Services;

use App\Http\Controllers\EquipmentSessionController;
use App\Models\EquipmentSession;
use App\Models\Reservation;

class GrantService
{
    public function checkBalance(float $cost): bool
    {

        $grant = auth()->user()->piProfile->grants()->first();

        if ($grant && $cost > $grant->balance) {
            return false;
        }
        $newBalance = $grant->balance - $cost;
        $grant->update(['balance' => $newBalance]);
        return true;
    }

    public function addSession(Reservation $reservation)
    {
        $data = [
            'user_id' => $reservation->user_id,
            'equipment_id' => $reservation->equipment_id,
            'start_time' => $reservation->start_time,
            'end_time'   => $reservation->end_time,
        ];
        $sessionController = app(EquipmentSessionController::class);
        $sessionController->storeSessionForReservation($data);
    }
}
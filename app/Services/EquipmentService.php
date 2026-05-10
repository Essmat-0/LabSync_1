<?php

namespace App\Services;

use App\Models\Equipment;
use App\Models\EquipmentSession;

class EquipmentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function canEquipmentBeUsed($equipmentId)
    {
        // 1. Get the equipment and its cooldown config
        $equipment = Equipment::findOrFail($equipmentId);

        // 2. Look for the most recent session or reservation that ended
        $lastSession = EquipmentSession::where('equipment_id', $equipmentId)
            ->latest('end_time')
            ->first();

        if (!$lastSession) return true;

        // 3. The Logic: Is now() within the cooldown window?
        $coolDownExpiresAt = $lastSession->end_time->addMinutes($equipment->cooldown_buffer);

        return now()->greaterThan($coolDownExpiresAt);
    }
}
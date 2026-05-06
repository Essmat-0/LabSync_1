<?php

namespace App\Http\Controllers;

use App\Models\EquipmentSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConsumableController extends Controller
{
    public function checkStock()
    {
        $sessions = EquipmentSession::with('equipment.consumables')
            ->where('end_time', '<=', now())
            ->where('stock_reduced', false)
            ->get();

        Log::info("Found " . $sessions->count() . " sessions to process.");

        foreach ($sessions as $session) {
            $equipment = $session->equipment;

            if ($equipment) {
                foreach ($equipment->consumables as $consumable) {
                    $consumable->decrement('stock_level', 5);
                }
            }

            $session->update(['stock_reduced' => true]);
        }
    }
}
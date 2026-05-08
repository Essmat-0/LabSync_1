<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Services\PiService;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function reservationPanel($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('equipment.book', compact('equipment'));
    }

    public function store(Request $request, $id)
    {

        $equipment = Equipment::findOrFail($id);

        $validated = $request->validate([
            'start_time' => 'required|date|after:now',
            'end_time'   => 'required|date|after:start_time',
            'quantity'  => 'required|integer'
        ]);
        $data = [
            'user_id'      => auth()->id(),
            'equipment_id' => $equipment->id,
            'start_time'   => $validated['start_time'],
            'end_time'     => $validated['end_time'],
            'quantity' => $validated['quantity'],
        ];


        // 3. Call the service
        $this->reservationService->makeReservation($data);

        return redirect()->route('equipment.index')->with('success', 'Reservation submitted and pending approval.');
    }
}
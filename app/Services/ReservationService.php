<?php

namespace App\Services;

use App\Models\Reservation;

class ReservationService
{
    protected $piService;
    public function __construct(PiService $piService)
    {
        $this->piService = $piService;
    }

    public function makeReservation(array $data): Reservation
    {

        $reservation = Reservation::create([
            'user_id'      => $data['user_id'],
            'equipment_id' => $data['equipment_id'],
            'start_time'   => $data['start_time'],
            'end_time'     => $data['end_time'],
            'status'       => 'Pending',
        ]);

        $this->sendToPIForApproval($reservation);
        return $reservation;
    }

    protected function sendToPIForApproval(Reservation $reservation)
    {
        $this->piService->notifyPI($reservation);
    }
}
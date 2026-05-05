<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function StoreorUpdateResearcher(array $data)
    {
        return DB::transaction(function () use ($data) {


            $role = Role::where('name', 'Researcher')->firstOrFail();

            $userValues = [
                'name'  => $data['user_name'],
                'password' => Hash::make($data['user_pass']),
                'role_id' => $role->id,
                'expiry_date' => $data['expiry_date'],
                'is_active' => true,
                'clearance_level' => $data['clearance_level'],
            ];
            if (!empty($data['user_pass'])) {
                $values['password'] = Hash::make($data['user_pass']);
            }

            $user = User::updateOrCreate(['email' => $data['user_email']], $userValues);

            $user->researcherProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'academic_leve' => $data['academic_level'],
                    'pis_id' => Auth::id(),
                ]
            );
            return $user;
        });
    }




    public function notifyPI(Reservation $reservation): void {}

    public function approve(Reservation $reservation): void
    {
        $reservation->update(['status' => 'Approved']);
    }

    public function reject(Reservation $reservation): void
    {
        $reservation->update(['status' => 'Rejected']);
    }
}
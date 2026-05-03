<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}


    public function storeUser(array $data)
    {
        $role = Role::where('name', $data['user_role'])->firstOrFail();

        return User::create([
            'name'     => $data['user_name'],
            'email'    => $data['user_email'],
            'role_id'  => $role->id,
            'password' => Hash::make($data['user_pass']),
            'budget_limit' => $data['budget_limit'] ?? null,
            'managed_Lab_Locations' => $data['lab_locations'] ?? null,
            'is_active'     => true,
            'expiry_date'   => $data['expiry_date'],
        ]);
    }

    public function deleteUser($id)
    {
        return  User::where('user_id', $id)->firstOrFail()->delete();
    }
}
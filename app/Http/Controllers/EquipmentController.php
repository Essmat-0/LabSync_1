<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::paginate(12);

        return view('welcome', compact('equipment'));
    }

    public function show($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('equipment.show', compact('equipment'));
    }
    public function book($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('equipment.book' , compact('equipment'));
    }
}
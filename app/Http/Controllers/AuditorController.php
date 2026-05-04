<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditorController extends Controller
{

    public function dashboard()
    {
        // ── Equipment (from `equipment` table only) ──
        $equipment = Equipment::orderBy('hourly_rate', 'desc')->get();

        // ── Audit trail (join users just for the name) ──
        $auditLogs = DB::table('audit_trails')
            ->join('users', 'audit_trails.user_id', '=', 'users.id')
            ->select('audit_trails.*', 'users.name as user_name')
            ->orderByDesc('audit_trails.created_at')
            ->limit(50)
            ->get();

        return view('dashboards.auditor', compact('equipment', 'auditLogs'));
    }
}
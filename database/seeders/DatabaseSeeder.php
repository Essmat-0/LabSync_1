<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ──────────────────────────────────────────
        // 1. ROLES
        // ──────────────────────────────────────────
        $roles = [
            ['name' => 'Admin',      'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PI',         'created_at' => now(), 'updated_at' => now()], // Principal Investigator
            ['name' => 'Researcher', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lab_Manager', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Auditor', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('roles')->insert($roles);

        $adminRoleId      = DB::table('roles')->where('name', 'Admin')->value('id');
        $piRoleId         = DB::table('roles')->where('name', 'PI')->value('id');
        $researcherRoleId = DB::table('roles')->where('name', 'Researcher')->value('id');
        $LabManagerRoleId = DB::table('roles')->where('name', 'Lab_Manager')->value('id');

        // ──────────────────────────────────────────
        // 2. USERS  (5 rows)
        // ──────────────────────────────────────────
        $users = [
            [
                'name'                 => 'Alice Morgan',
                'email'                => 'alice@lab.edu',
                'password'             => Hash::make('password'),
                'clearance_level'      => 3,
                'role_id'              => $adminRoleId,
                'is_active'            => true,
                'expiry_date'          => '2027-12-31',
                'academicLevel'        => null,           // Admins don't have academic levels
                'pis_id'               => null,           // Admin is not under a PI
                'affiliation'          => 'Lab Administration',
                'budget_limit'         => null,           // Admins don't have budget limits
                'managed_Lab_Locations' => 'Lab A, Lab B, Lab C',
                'audit_scope'          => 'Full System',
                'systemPrivileges'     => 'super_admin',
                'created_at'           => now(),
                'updated_at' => now(),
            ],
            [
                'name'                 => 'Dr. Brian Cole',
                'email'                => 'bcole@lab.edu',
                'password'             => Hash::make('password'),
                'clearance_level'      => 3,
                'role_id'              => $piRoleId,
                'is_active'            => true,
                'expiry_date'          => '2026-08-31',
                'academicLevel'        => 'Professor',
                'pis_id'               => null,           // PI has no PI above them
                'affiliation'          => 'Department of Biochemistry',
                'budget_limit'         => 50000.00,
                'managed_Lab_Locations' => 'Lab B',
                'audit_scope'          => 'Lab B',
                'systemPrivileges'     => 'grant_management',
                'created_at'           => now(),
                'updated_at' => now(),
            ],
            [
                'name'                 => 'Sara Kim',
                'email'                => 'skim@lab.edu',
                'password'             => Hash::make('password'),
                'clearance_level'      => 2,
                'role_id'              => $researcherRoleId,
                'is_active'            => true,
                'expiry_date'          => '2025-12-31',
                'academicLevel'        => 'PhD Student',
                'pis_id'               => 2,              // Under Dr. Brian Cole (id=2)
                'affiliation'          => 'Department of Biochemistry',
                'budget_limit'         => 5000.00,
                'managed_Lab_Locations' => null,
                'audit_scope'          => null,
                'systemPrivileges'     => 'none',
                'created_at'           => now(),
                'updated_at' => now(),
            ],
            [
                'name'                 => 'James Patel',
                'email'                => 'jpatel@lab.edu',
                'password'             => Hash::make('password'),
                'clearance_level'      => 1,
                'role_id'              => $researcherRoleId,
                'is_active'            => true,
                'expiry_date'          => '2026-05-30',
                'academicLevel'        => 'Masters Student',
                'pis_id'               => 2,              // Under Dr. Brian Cole (id=2)
                'affiliation'          => 'Department of Biochemistry',
                'budget_limit'         => 2000.00,
                'managed_Lab_Locations' => null,
                'audit_scope'          => null,
                'systemPrivileges'     => 'none',
                'created_at'           => now(),
                'updated_at' => now(),
            ],
            [
                'name'                 => 'Nina Torres',
                'email'                => 'ntorres@lab.edu',
                'password'             => Hash::make('password'),
                'clearance_level'      => 2,
                'role_id'              => $LabManagerRoleId,
                'is_active'            => true,
                'expiry_date'          => '2026-11-15',
                'academicLevel'        => 'BSc',
                'pis_id'               => 2,              // Assigned to Dr. Brian Cole's lab
                'affiliation'          => 'Core Facilities Unit',
                'budget_limit'         => null,           // Technicians don't manage budgets
                'managed_Lab_Locations' => 'Lab A',        // Technicians can manage a location
                'audit_scope'          => 'Equipment Only',
                'systemPrivileges'     => 'equipment_management',
                'created_at'           => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('users')->insert($users);

        $alice = DB::table('users')->where('email', 'alice@lab.edu')->value('id');
        $brian = DB::table('users')->where('email', 'bcole@lab.edu')->value('id');
        $sara  = DB::table('users')->where('email', 'skim@lab.edu')->value('id');
        $james = DB::table('users')->where('email', 'jpatel@lab.edu')->value('id');
        $nina  = DB::table('users')->where('email', 'ntorres@lab.edu')->value('id');

        // ──────────────────────────────────────────
        // 3. EQUIPMENT
        // ──────────────────────────────────────────
        $equipment = [
            ['name' => 'Electron Microscope',  'status' => 'Available', 'hourly_rate' => 150.00, 'required_clearance' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mass Spectrometer',    'status' => 'Available', 'hourly_rate' => 120.00, 'required_clearance' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PCR Thermal Cycler',   'status' => 'In Use',    'hourly_rate' =>  45.00, 'required_clearance' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Centrifuge XL-5000',   'status' => 'Available', 'hourly_rate' =>  30.00, 'required_clearance' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Confocal Microscope',  'status' => 'Maintenance', 'hourly_rate' => 200.00, 'required_clearance' => 3, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('equipment')->insert($equipment);

        $em   = DB::table('equipment')->where('name', 'Electron Microscope')->value('id');
        $ms   = DB::table('equipment')->where('name', 'Mass Spectrometer')->value('id');
        $pcr  = DB::table('equipment')->where('name', 'PCR Thermal Cycler')->value('id');
        $cent = DB::table('equipment')->where('name', 'Centrifuge XL-5000')->value('id');
        $conf = DB::table('equipment')->where('name', 'Confocal Microscope')->value('id');

        // ──────────────────────────────────────────
        // 4. CERTIFICATIONS
        //    Users must be certified for equipment
        //    whose required_clearance matches their level
        // ──────────────────────────────────────────
        DB::table('certifications')->insert([
            ['user_id' => $alice, 'equipment_id' => $em,   'expiry_date' => '2026-12-01', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $alice, 'equipment_id' => $conf, 'expiry_date' => '2026-08-15', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $brian, 'equipment_id' => $em,   'expiry_date' => '2026-11-20', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $brian, 'equipment_id' => $ms,   'expiry_date' => '2026-09-30', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $sara,  'equipment_id' => $ms,   'expiry_date' => '2025-12-31', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $sara,  'equipment_id' => $pcr,  'expiry_date' => '2026-06-01', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $james, 'equipment_id' => $pcr,  'expiry_date' => '2026-03-15', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $james, 'equipment_id' => $cent, 'expiry_date' => '2026-07-01', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $nina,  'equipment_id' => $ms,   'expiry_date' => '2026-10-10', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $nina,  'equipment_id' => $cent, 'expiry_date' => '2026-05-20', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ──────────────────────────────────────────
        // 5. EQUIPMENT SESSIONS
        // ──────────────────────────────────────────
        $sessions = [
            // Completed sessions (have end_time + amount)
            [
                'user_id'         => $brian,
                'equipment_id'    => $em,
                'start_time'      => Carbon::now()->subDays(10)->setTime(9, 0),
                'end_time'        => Carbon::now()->subDays(10)->setTime(11, 0),
                'status'          => 'Completed',
                'approval_status' => 'Approved',
                'created_at'      => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'         => $sara,
                'equipment_id'    => $ms,
                'start_time'      => Carbon::now()->subDays(7)->setTime(13, 0),
                'end_time'        => Carbon::now()->subDays(7)->setTime(15, 30),
                'status'          => 'Completed',
                'approval_status' => 'Approved',
                'created_at'      => now(),
                'updated_at' => now(),
            ],
            [
                'user_id'         => $james,
                'equipment_id'    => $pcr,
                'start_time'      => Carbon::now()->subDays(3)->setTime(10, 0),
                'end_time'        => Carbon::now()->subDays(3)->setTime(12, 0),
                'status'          => 'Completed',
                'approval_status' => 'Approved',
                'created_at'      => now(),
                'updated_at' => now(),
            ],
            // Active session
            [
                'user_id'         => $nina,
                'equipment_id'    => $cent,
                'start_time'      => Carbon::now()->subHours(2),
                'end_time'        => null,
                'status'          => 'Active',
                'approval_status' => 'Approved',
                'created_at'      => now(),
                'updated_at' => now(),
            ],
            // Pending session
            [
                'user_id'         => $alice,
                'equipment_id'    => $em,
                'start_time'      => Carbon::now()->addDays(2)->setTime(9, 0),
                'end_time'        => null,
                'status'          => 'Pending',
                'approval_status' => 'Pending',
                'created_at'      => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('equipment_sessions')->insert($sessions);

        $s1 = DB::table('equipment_sessions')->where('user_id', $brian)->where('equipment_id', $em)->value('id');
        $s2 = DB::table('equipment_sessions')->where('user_id', $sara)->where('equipment_id', $ms)->value('id');
        $s3 = DB::table('equipment_sessions')->where('user_id', $james)->where('equipment_id', $pcr)->value('id');

        // ──────────────────────────────────────────
        // 6. TRANSACTIONS  (only for Completed sessions)
        //    amount = hours × hourly_rate
        // ──────────────────────────────────────────
        DB::table('transactions')->insert([
            // Brian: 2h × $150 = $300
            ['session_id' => $s1, 'amount' => 300.00, 'normalized_amount' => 285.00, 'created_at' => now(), 'updated_at' => now()],
            // Sara: 2.5h × $120 = $300
            ['session_id' => $s2, 'amount' => 300.00, 'normalized_amount' => 300.00, 'created_at' => now(), 'updated_at' => now()],
            // James: 2h × $45 = $90
            ['session_id' => $s3, 'amount' =>  90.00, 'normalized_amount' =>  90.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ──────────────────────────────────────────
        // 7. GRANTS  (PI users hold grants)
        // ──────────────────────────────────────────
        DB::table('grants')->insert([
            ['pi_id' => $brian, 'balance' => 25000.00, 'created_at' => now(), 'updated_at' => now()],
            ['pi_id' => $alice, 'balance' => 10000.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ──────────────────────────────────────────
        // 8. MAINTENANCE LOGS
        // ──────────────────────────────────────────
        DB::table('maintenance_logs')->insert([
            ['equipment_id' => $conf, 'cost' => 1200.00, 'description' => 'Laser alignment and calibration after detected drift in Z-axis.', 'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $em,   'cost' =>  350.00, 'description' => 'Routine vacuum pump service and filament replacement.',           'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $ms,   'cost' =>  500.00, 'description' => 'Ion source cleaning and mass calibration verification.',           'created_at' => now(), 'updated_at' => now()],
        ]);

        // ──────────────────────────────────────────
        // 9. PUBLICATION LINKS
        // ──────────────────────────────────────────
        DB::table('publication_links')->insert([
            ['equipment_id' => $em,  'doi' => '10.1038/s41586-024-00101-1', 'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $ms,  'doi' => '10.1021/acs.analchem.4c00234', 'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $pcr, 'doi' => '10.1016/j.gene.2024.148321',  'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $em,  'doi' => '10.1126/science.adh8765',      'created_at' => now(), 'updated_at' => now()],
        ]);

        // ──────────────────────────────────────────
        // 10. ROI REPORTS
        // ──────────────────────────────────────────
        DB::table('roi_reports')->insert([
            ['equipment_id' => $em,   'roi_score' => 4.75, 'recommendation' => 'Keep',    'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $ms,   'roi_score' => 3.90, 'recommendation' => 'Keep',    'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $pcr,  'roi_score' => 2.10, 'recommendation' => 'Review',  'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $conf, 'roi_score' => 1.50, 'recommendation' => 'Retire',  'created_at' => now(), 'updated_at' => now()],
        ]);

        // ──────────────────────────────────────────
        // 11. UTILIZATION CACHE
        // ──────────────────────────────────────────
        DB::table('utilization_cache')->insert([
            ['equipment_id' => $em,   'usage_percentage' => 78.50, 'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $ms,   'usage_percentage' => 65.20, 'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $pcr,  'usage_percentage' => 91.00, 'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $cent, 'usage_percentage' => 45.75, 'created_at' => now(), 'updated_at' => now()],
            ['equipment_id' => $conf, 'usage_percentage' => 12.30, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ──────────────────────────────────────────
        // 12. AUDIT TRAILS
        // ──────────────────────────────────────────
        DB::table('audit_trails')->insert([
            ['user_id' => $alice, 'action' => 'Created equipment: Electron Microscope',      'created_at' => now()],
            ['user_id' => $alice, 'action' => 'Created equipment: Confocal Microscope',      'created_at' => now()],
            ['user_id' => $brian, 'action' => 'Started session on Electron Microscope',      'created_at' => now()],
            ['user_id' => $sara,  'action' => 'Started session on Mass Spectrometer',        'created_at' => now()],
            ['user_id' => $alice, 'action' => 'Flagged Confocal Microscope for maintenance', 'created_at' => now()],
            ['user_id' => $james, 'action' => 'Completed session on PCR Thermal Cycler',     'created_at' => now()],
        ]);
    }
}
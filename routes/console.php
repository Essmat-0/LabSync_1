<?php

use App\Services\ConsumableService;
use App\Models\EquipmentSession;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(ConsumableService::class)->checkStock();
    
    
    $cutoff = now()->subMinutes(15);
    $inactiveUserIds = User::where('is_active', true)
        ->where('updated_at', '<', $cutoff)
        ->pluck('id');

    if ($inactiveUserIds->isNotEmpty()) {
        // 2. End their active bookings (assuming you have a 'status' or 'end_time')
        EquipmentSession::whereIn('user_id', $inactiveUserIds)
            ->whereNull('end_time') 
            ->update([
                'end_time' => now(),
            ]);

        // 3. Finally, set the users to inactive
        User::whereIn('id', $inactiveUserIds)->update(['is_active' => false]);
    }
})->everyMinute();
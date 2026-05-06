<?php

use App\Http\Controllers\ConsumableController;
use App\Models\EquipmentSession;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(ConsumableController::class)->checkStock();
})->everyMinute();
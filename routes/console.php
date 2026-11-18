<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Artikel kesehatan baru dari Gemini, tiap Senin pukul 06:00 (zona waktu APP_TIMEZONE)
Schedule::command('articles:generate')->weeklyOn(1, '06:00')->withoutOverlapping();

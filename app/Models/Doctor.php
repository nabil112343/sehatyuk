<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = ['poli_id', 'name', 'specialty', 'days', 'start_time', 'end_time', 'quota'];

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function getDaysArrayAttribute(): array
    {
        return array_values(array_filter(explode(',', (string) $this->days)));
    }

    public static function dayName(Carbon $date): string
    {
        return ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$date->dayOfWeek];
    }

    public function toApi(): array
    {
        return [
            'id' => $this->id,
            'poli_id' => $this->poli_id,
            'poli_name' => $this->poli?->name,
            'poli_code' => $this->poli?->code,
            'name' => $this->name,
            'specialty' => $this->specialty,
            'days' => $this->days_array,
            'start_time' => substr($this->start_time, 0, 5),
            'end_time' => substr($this->end_time, 0, 5),
            'quota' => $this->quota,
        ];
    }
}

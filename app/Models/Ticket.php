<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id', 'doctor_id', 'visit_date', 'queue_number', 'queue_code', 'booking_code',
        'referral_number', 'phone', 'status', 'called_at', 'notified_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'called_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public const LABELS = [
        'waiting' => 'Menunggu', 'called' => 'Dipanggil', 'examining' => 'Diperiksa',
        'done' => 'Selesai', 'skipped' => 'Terlewat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /** Jumlah pasien berstatus "menunggu" di depan tiket ini (dokter & tanggal sama). */
    public function ahead(): int
    {
        return static::where('doctor_id', $this->doctor_id)
            ->where('visit_date', $this->visit_date->toDateString())
            ->where('status', 'waiting')
            ->where('queue_number', '<', $this->queue_number)
            ->count();
    }

    public function toApi(): array
    {
        $d = $this->doctor;
        $ahead = $this->status === 'waiting' ? $this->ahead() : 0;
        $start = Carbon::parse($this->visit_date->toDateString() . ' ' . $d->start_time);
        $base = ($this->visit_date->isToday() && now()->gt($start)) ? now() : $start;

        return [
            'id' => $this->id,
            'queue_code' => $this->queue_code,
            'booking_code' => $this->booking_code,
            'referral_number' => $this->referral_number,
            'status' => $this->status,
            'status_label' => self::LABELS[$this->status] ?? $this->status,
            'visit_date' => $this->visit_date->toDateString(),
            'doctor' => $d->name,
            'doctor_id' => $d->id,
            'specialty' => $d->specialty,
            'poli' => $d->poli?->name,
            'patient' => $this->user?->name,
            'phone' => $this->phone,
            'ahead' => $ahead,
            'estimate' => $base->copy()->addMinutes($ahead * 10)->format('d-m-Y H:i'),
        ];
    }
}

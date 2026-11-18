<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /** Ambil nomor antrean. */
    public function store(Request $request)
    {
        $d = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'phone' => ['required', 'regex:/^(\+62|62|0)8[0-9]{7,12}$/'],
        ], ['phone.regex' => 'Nomor HP tidak valid (contoh: 081234567890).',
            'visit_date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.']);

        $date = Carbon::parse($d['visit_date'])->startOfDay();
        $docId = (int) $d['doctor_id'];
        $userId = Auth::id();

        $ticket = DB::transaction(function () use ($d, $date, $docId, $userId) {
            $doctor = Doctor::with('poli')->findOrFail($docId);
            // Kunci baris poli supaya nomor antrean tidak ganda saat diambil bersamaan
            $poli = Poli::lockForUpdate()->findOrFail($doctor->poli_id);
            $dayName = Doctor::dayName($date);

            if (!in_array($dayName, $doctor->days_array)) {
                abort(422, "Dokter tidak praktik pada hari $dayName. Jadwal: " . implode(', ', $doctor->days_array) . '.');
            }
            if ($date->isToday() && now()->format('H:i') >= substr($doctor->end_time, 0, 5)) {
                abort(422, 'Jam praktik dokter hari ini sudah berakhir. Pilih tanggal lain.');
            }

            $dateStr = $date->toDateString();

            $already = Ticket::where('user_id', $userId)->where('doctor_id', $docId)
                ->where('visit_date', $dateStr)->where('status', '!=', 'done')->exists();
            if ($already) {
                abort(422, 'Anda sudah memiliki tiket aktif untuk dokter dan tanggal ini.');
            }

            $count = Ticket::where('doctor_id', $docId)->where('visit_date', $dateStr)->count();
            if ($count >= $doctor->quota) {
                abort(422, 'Kuota dokter pada tanggal ini sudah penuh.');
            }

            $doctorIds = Doctor::where('poli_id', $poli->id)->pluck('id');
            $number = (int) Ticket::whereIn('doctor_id', $doctorIds)->where('visit_date', $dateStr)->max('queue_number') + 1;

            return Ticket::create([
                'user_id' => $userId,
                'doctor_id' => $docId,
                'visit_date' => $dateStr,
                'queue_number' => $number,
                'queue_code' => $poli->code . '-' . str_pad($number, 3, '0', STR_PAD_LEFT),
                'booking_code' => (string) random_int(100000, 999999),
                'referral_number' => strtoupper(Str::random(17)),
                'phone' => $d['phone'],
                'status' => 'waiting',
            ]);
        });

        return $ticket->load('doctor.poli', 'user')->toApi();
    }

    /** Daftar tiket milik pasien yang login. */
    public function mine()
    {
        return Ticket::with('doctor.poli', 'user')
            ->where('user_id', Auth::id())
            ->orderByDesc('visit_date')->orderByDesc('id')
            ->get()->map->toApi()->values();
    }

    /** Data layar TV (hari ini). */
    public function tv()
    {
        $today = today();
        $dayName = Doctor::dayName($today);
        $tickets = Ticket::where('visit_date', $today->toDateString())->get()->groupBy('doctor_id');

        $items = Doctor::with('poli')->orderBy('poli_id')->get()->map(function ($d) use ($tickets, $dayName) {
            $t = $tickets->get($d->id, collect());
            $current = $t->whereIn('status', ['called', 'examining'])->sortByDesc('called_at')->first();
            $waiting = $t->where('status', 'waiting')->sortBy('queue_number');

            return [
                'poli' => $d->poli?->name,
                'doctor' => $d->name,
                'current' => $current?->queue_code,
                'current_status' => $current?->status,
                'next' => $waiting->first()?->queue_code,
                'waiting' => $waiting->count(),
                'practicing' => in_array($dayName, $d->days_array),
            ];
        })->values();

        return ['date' => $today->toDateString(), 'items' => $items];
    }
}

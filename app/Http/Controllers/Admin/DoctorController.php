<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    private function rules(): array
    {
        return [
            'poli_id' => 'required|exists:polis,id',
            'name' => 'required|string|max:120',
            'specialty' => 'required|string|max:120',
            'days' => 'required|array|min:1',
            'days.*' => 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'quota' => 'required|integer|min:1|max:500',
        ];
    }

    private function clean(Request $request): array
    {
        $data = $request->validate($this->rules(), [
            'days.required' => 'Pilih minimal satu hari praktik.',
            'days.min' => 'Pilih minimal satu hari praktik.',
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ]);
        $order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $days = array_values(array_intersect($order, $data['days']));
        $data['days'] = implode(',', $days);
        return $data;
    }

    public function store(Request $request)
    {
        return Doctor::create($this->clean($request))->load('poli')->toApi();
    }

    public function update(Request $request, Doctor $doctor)
    {
        $doctor->update($this->clean($request));
        return $doctor->load('poli')->toApi();
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete(); // tiket terkait ikut terhapus (cascade)
        return ['ok' => true];
    }
}

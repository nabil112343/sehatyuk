<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PoliController extends Controller
{
    private function clean(Request $request, ?Poli $poli = null): array
    {
        $request->merge(['code' => strtoupper((string) $request->code)]);
        return $request->validate([
            'code' => ['required', 'alpha', 'max:5', Rule::unique('polis', 'code')->ignore($poli?->id)],
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
        ], ['code.unique' => 'Kode poli sudah dipakai.', 'code.alpha' => 'Kode poli hanya huruf (maks. 5).']);
    }

    public function store(Request $request)
    {
        return Poli::create($this->clean($request))->load('doctors.poli')->toApi();
    }

    public function update(Request $request, Poli $poli)
    {
        $poli->update($this->clean($request, $poli));
        return $poli->load('doctors.poli')->toApi();
    }

    public function destroy(Poli $poli)
    {
        $poli->delete(); // dokter & tiket di poli ini ikut terhapus
        return ['ok' => true];
    }
}

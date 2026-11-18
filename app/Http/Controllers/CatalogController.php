<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Poli;

class CatalogController extends Controller
{
    public function polis()
    {
        return Poli::with('doctors.poli')->orderBy('id')->get()->map->toApi()->values();
    }

    public function doctors()
    {
        return Doctor::with('poli')->orderBy('poli_id')->orderBy('name')->get()->map->toApi()->values();
    }
}

<?php

namespace App\Exports;

use App\Models\estadia_enfermedad;
use Maatwebsite\Excel\Concerns\FromCollection;

class EstadiaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return estadia_enfermedad::where('estadia_enfermedable_type','App\Models\punto_atencion')->get();
    }
}

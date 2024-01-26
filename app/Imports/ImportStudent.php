<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportStudent implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {  
        return new Student([
            'nim' => $row[0],
            'nama' => $row[1],
            'fakultas' => $row[2],
        ]);
    }
}

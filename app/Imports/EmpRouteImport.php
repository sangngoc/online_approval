<?php

namespace App\Imports;

use App\Models\Emp_Route;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\Importable;
class EmpRouteImport implements ToModel, WithUpserts, WithCustomCsvSettings
{
    /**
     * @param array $row
     *
     */

     use Importable;
     
    public function model(array $row)
    {
        return new Emp_Route([
           'route_id' => $row[0],
           'emp_id' => $row[1], 
        ]);
    }

    public function uniqueBy()
    {
        return ['route_id', 'emp_id'];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }
}

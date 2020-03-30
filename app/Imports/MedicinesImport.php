<?php

namespace App\Imports;

use App\Medicine;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MedicinesImport implements ToModel, WithHeadingRow, WithChunkReading
{


    use Importable;

    //

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
         HeadingRowFormatter::default('none');
        return new Medicine([
            'item_code'     => $row['item_code'],
            'item_name'     => $row['item_name'],
            'batch_no'      => $row['batch_no'],
            'quantity'      => $row['quantity'],
            'cost_price'    => $row['cost_price'],
            'purchase_price'=> $row['purchase_price'],
            // 'rack_number'   => $row['rack'],
            'composition'   => $row['composition'],
            'manufacturer'  => $row['manufactured_by'],
            'marketed_by'   => $row['marketed_by'],
            'group'         => $row['group'],
            'tax'           => $row['tax'],
            'expiry'        => $row['expiry'],
            'selling_price' => $row['selling_price'],
            'discount'      => $row['discount'],
            'created_by'    => 1,
            'added_by'      => 1,
            'created_at'    => date("Y-m-d")
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }


}

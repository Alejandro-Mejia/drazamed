<?php

namespace App\Imports;

use App\Medicine;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportMedicines implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Medicine([
            // 'item_code'     => $row[0],
            // 'item_name'     => $row[1],
            // 'batch_no'      => $row[2],
            // 'quantity'      => $row[3],
            // 'cost_price'    => $row[4],
            // 'purchase_price'=> $row[5],
            // 'composition'   => $row[6],
            // 'marketed_by'   => $row[7],
            // 'group'         => $row[8],
            // 'tax'           => $row[9],
            // 'expiry'        => $row[10],
            // 'MRP'           => $row[11],
            // 'discount'      => $row[12],
        ]);
    }
}

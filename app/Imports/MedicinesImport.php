<?php

namespace App\Imports;

use App\Medicine;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsFailures;
// use Maatwebsite\Excel\Concerns\SkipsOnError;
// use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class MedicinesImport implements ToModel, WithHeadingRow, WithBatchInserts , WithChunkReading, WithValidation, SkipsOnFailure
{


    use Importable, SkipsFailures;

    //

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // HeadingRowFormatter::default('none');
        return new Medicine([

            'provider'      => $row['proveedor'],
            'item_name'     => $this->cleanDenomination($row['denominacion']),
            'denomination'  => $row['denominacion'],
            'batch_no'      => $row['lote'],
            'units'         => $row['und'],
            'quantity'      => $row['cantidad'],
            'cost_price'    => $row['venta_real'],
            'current_price' => $row['venta_cte'],
            'real_price'    => $row['venta_real'],
            'marked_price'  => $row['marcado'],
            'bonification'  => $row['boni'],
            'purchase_price'=> $row['venta_real'],
            'selling_price' => $row['venta_real'],

            'catalog'       => $row['catalogo'],
            // 'rack_number'   => $row['rack'],
            'composition'   => $row['subgrupo'],
            'manufacturer'  => $row['proveedor'],
            'marketed_by'   => $this->cleanManufacturer($row['proveedor']),
            'group'         => $row['grupo'],
            'is_pres_required' => ($row['grupo'] == 'ANTIBIOTICOS') ? 1 : 0,
            'subgroup'      => $row['subgrupo'],
            'item_code'     => $row['ean'],
            'tax_tipe'      => 'PERCENTAGE',
            'tax'           => $this->ivaImport($row['impuesto']) ,

            'discount'      => 0,
            'created_by'    => 1,
            'added_by'      => 1,
            'created_at'    => date("Y-m-d")
        ]);
    }



    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return [ // , '(C)', 'OBSEQUIO'
             // Can also use callback validation rules
            'denominacion' => function($attribute, $value, $onFailure) {
                if(Str::contains($value,  '(C)' ) || Str::contains($value,  'OBS.' ) || Str::contains($value,  'OBSEQUIO' )) {
                    $onFailure('Producto controlado u obsequio. No se importa a la BD : ' . $value);
                }
            }
        ];
    }

    public function ivaImport($value)
    {

        switch ($value) {
            case 'Excluido de Iva':
                $iva = 0;
                break;
            case 'Excluido de IVA Esp':
                $iva = 0;
                break;
            case '19%  IVA':
                $iva = 19;
                break;
            case '5%  IVA':
                $iva = 5;
                break;
            default:
                $iva = 0;
                break;
        }
        return $iva;
    }

    public function cleanManufacturer($value) {
        $value = Str::replaceLast(' SAS', '', $value);
        $value = Str::replaceLast(' SAS.', '', $value);
        $value = Str::replaceLast(' S.A.S', '', $value);
        $value = Str::replaceLast(' S.A.S.', '', $value);
        $value = Str::replaceLast(' S.A.', '', $value);
        $value = Str::replaceLast(' S.A', '', $value);
        $value = Str::replaceLast(' LTDA.', '', $value);
        $value = Str::replaceLast(' LTDA', '', $value);
        $value = Str::replaceLast(' S.A.S.', '', $value);
        $value = Str::replaceLast(' COLOMBIANA S.A', '', $value);
        $value = Str::replaceLast(' DE COLOMBIA S.A', '', $value);
        $value = Str::replaceLast(' DE COLOMBIA', '', $value);
        $value = Str::replaceLast(' DE COLOMBI', '', $value);
        $value = Str::replaceLast(' DE C', '', $value);

        return $value;

    }

    public function cleanDenomination($value) {

        $value = Str::replaceLast('(PAE)', '', $value);
        $value = Str::replaceLast('(3%+)', '', $value);
        $value = Str::replaceLast('(A)', '', $value);
        $value = Str::replaceLast('(R)', '', $value);
        $value = Str::replaceLast('(PDB)', '', $value);
        $value = Str::replaceLast('(SF)', '', $value);
        $value = Str::replaceLast('(SC)', '', $value);
        $value = Str::replaceLast('(T', '', $value);
        $value = preg_replace_array('/\(M\)\d{3,6}/', [''], $value);
        $value = preg_replace_array('/\(M\)\d{3,6}/', [''], $value);
        $value = preg_replace_array('/\(P\)\d{3,6}/', [''], $value);
        $value = Str::replaceLast('(A)', '', $value);
        $value = Str::replaceLast('(M)', '', $value);
        $value = Str::replaceLast('(E)', '', $value);
        $value = Str::replaceLast('(P)', '', $value);

        return $value;

    }



}

<?php

namespace App\Imports;

use App\Medicine;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
// use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


/** @brief Class for medicines
* @author Alejandro Mejia
* @date March 2020
*/
class MedicinesImport implements ToModel, WithHeadingRow, WithBatchInserts , WithChunkReading, WithValidation, SkipsOnFailure, SkipsOnError
{


    use Importable, SkipsFailures;

    //

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
     *
    */
    public function model(array $row)
    {
        // HeadingRowFormatter::default('none');
        return new Medicine([

            'provider'      => isset($row['proveedor']) ? $row['proveedor'] : "ND",
            'item_name'     => isset($row['denominacion']) ?  $this->cleanDenomination($row['denominacion']) : "ND",
            'denomination'  => isset($row['denominacion']) ? $row['denominacion'] : "ND",
            'batch_no'      => isset($row['lote']) ? $row['lote'] : "ND",
            'units'         => isset($row['und']) ? $row['und'] : "ND",
            'quantity'      => isset($row['cantidad']) ? $row['cantidad'] : 0,

            'marked_price'  => isset($row['marcado']) ? $row['marcado']*1000 : 0,
            // 'bonification'  => $row['boni'],


            'catalog'       => isset($row['catalogo']) ? $row['catalogo'] : "ND",
            // 'rack_number'   => $row['rack'],
            'composition'   => isset($row['subgrupo']) ? $row['subgrupo'] : "ND",
            'manufacturer'  => isset($row['proveedor']) ? $this->cleanManufacturer($row['proveedor']) : "ND",
            'marketed_by'   => isset($row['proveedor']) ? $row['proveedor'] : "ND",
            'show_priority' => isset($row['proveedor']) ? $this->setPriority($row['proveedor']) : 0,
            'group'         => isset($row['grupo']) ? $row['grupo'] : "ND",
            'is_pres_required' => isset($row['grupo']) ? (($row['grupo'] == 'ANTIBIOTICOS') ? 1 : 0) : 0,
            'subgroup'      => isset($row['subgrupo']) ? $row['subgrupo'] : "ND",
            'item_code'     => isset($row['ean']) ?  $row['ean'] : "ND",
            'tax_type'      => 'PERCENTAGE',
            'tax'           => isset($row['impuesto']) ? $this->ivaImport($row['impuesto']) : 0,
            'purchase_price'=> isset($row['venta_real']) ? $row['venta_real'] : 0,
            'selling_price' => 0,
            'cost_price'    => isset($row['venta_real']) ? $row['venta_real'] : 0,
            'current_price' => isset($row['venta_cte']) ? $row['venta_cte'] : 0,
            'real_price'    => isset($row['venta_real']) ? $row['venta_real'] : 0,

            'discount'      => 0,
            'created_by'    => 1,
            'added_by'      => 1,
            'created_at'    => date("Y-m-d")
        ]);
    }

    //$this->sellingPrice($row['venta_real'], $row['venta_cte'], $row['impuesto'], $row['proveedor'], $row['marcado'], $row['denominacion']),

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

                if(Str::contains($value,  '(C)' )) {
                    Log::info('Producto controlado :' . $value) ;
                }
                if(Str::contains($value,  'OBS ' ) || Str::contains($value,  'OBS.' ) || Str::contains($value,  'OBSEQUIO' )) {
                    Log::info('Obsequio :' . $value) ;
                }
                if(Str::contains($value,  '(C)' ) || Str::contains($value,  'OBS ' ) || Str::contains($value,  'OBS.' ) || Str::contains($value,  'OBSEQUIO' )) {
                    $onFailure('Producto controlado u obsequio. No se importa a la BD : ' . $value);
                }


            },
            'proveedor' => 'required|string',
            '*.proveedor' => 'required|string',
            'ean' => 'required|string',
            '*.ean' => 'required|string',
        ];

    }

    public function setPriority($lab)
    {
        if(Str::contains($lab,  'ICOM')) {
            return 1;
        } else {
            return 0;
        }

        // return $return;
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
        $value = Str::replaceLast(' S.A.S.', '', $value);
        $value = Str::replaceLast(' S.A.S', '', $value);
        $value = Str::replaceLast(' S.A.', '', $value);
        $value = Str::replaceLast(' S.A', '', $value);
        $value = Str::replaceLast(' LTDA.', '', $value);
        $value = Str::replaceLast(' LTDA', '', $value);
        $value = Str::replaceLast(' LTD', '', $value);
        $value = Str::replaceLast(' COLOMBIANA S.A', '', $value);
        $value = Str::replaceLast(' DE COLOMBIA S.A', '', $value);
        $value = Str::replaceLast(' DE COLOMBIA', '', $value);
        $value = Str::replaceLast(' DE COLOMBI', '', $value);
        $value = Str::replaceLast(' DE COLO', '', $value);
        $value = Str::replaceLast(' DE C', '', $value);
        $value = Str::replaceLast(' CIA.', '', $value);
        $value = Str::replaceLast(' C.I.', '', $value);
        $value = Str::replaceLast(' C.I', '', $value);


        return $value;

    }

    public function cleanDenomination($value) {

        // Limpia los codigos comerciales
        $value = Str::replaceLast('(PAE)', '', $value);
        $value = Str::replaceLast('(SAV)', '', $value);
        $value = Str::replaceLast('(3%+)', '', $value);
        $value = Str::replaceLast('(R)', '', $value);
        $value = Str::replaceLast('(PDB)', '', $value);
        $value = Str::replaceLast('(SF)', '', $value);
        $value = Str::replaceLast('(SC)', '', $value);
        $value = Str::replaceLast('(T)', '', $value);
        $value = preg_replace_array('/\(M\)\d{3,6}/', [''], $value);
        // $value = preg_replace_array('/\(M\)\d{3,6}/', [''], $value);
        $value = preg_replace_array('/\(P\)\d{3,6}/', [''], $value);
        $value = Str::replaceLast('(A)', '', $value);
        $value = Str::replaceLast('(M)', '', $value);
        $value = Str::replaceLast('(E)', '', $value);
        $value = Str::replaceLast('(P)', '', $value);
        $value = Str::replaceLast('(DES)', '', $value);
        $value = Str::replaceLast('(D)', '', $value);
        $value = Str::replaceLast('(DAD)', '', $value);
        $value = Str::replaceLast('(DSF)', '', $value);
        $value = Str::replaceLast('(A50)', '', $value);
        $value = Str::replaceLast('(LBD)', '', $value);
        $value = Str::replaceLast('(CYA)', '', $value);
        $value = Str::replaceLast('(PDI)', '', $value);
        $value = Str::replaceLast('(GDI)', '', $value);
        $value = Str::replaceLast('(G13)', '', $value);
        $value = Str::replaceLast('(ASI)', '', $value);
        $value = Str::replaceLast('(MSF)', '', $value);
        $value = Str::replaceLast('(MAX)', '', $value);
        $value = Str::replaceLast('(ALZ)', '', $value);
        $value = Str::replaceLast('(ERT)', '', $value);
        $value = Str::replaceLast('(LPO)', '', $value);
        $value = Str::replaceLast('(PAM)', '', $value);
        $value = Str::replaceLast('(DPT)', '', $value);
        $value = Str::replaceLast('(RYS)', '', $value);
        $value = Str::replaceLast('(DPD)', '', $value);
        $value = Str::replaceLast('(MGF)', '', $value);
        $value = Str::replaceLast('(FCR)', '', $value);
        $value = Str::replaceLast('(MDN)', '', $value);

        // Limpia abreviacion del proveedor
        // $value = Str::replaceLast(' AG ', '', $value);
        // $value = Str::replaceLast(' BMS ', '', $value);
        // $value = Str::replaceLast(' PC ', '', $value);
        // $value = Str::replaceLast(' EC ', '', $value);
        // $value = Str::replaceLast(' GF ', '', $value);
        // $value = Str::replaceLast(' LS ', '', $value);

        return $value;

    }

    // /**
    //  * @param Failure ...$failures
    //  */
    // public function onFailure(Failure ...$failures)
    // {
    //     Log::stack(['import-failure-logs'])->info(json_encode($failures));
    // }


    public function onError(\Throwable $e)
    {
        Log::error('['.$e->getCode().'] "'.$e->getMessage().'" on line '.$e->getTrace()[0]['line'].' of file '.$e->getTrace()[0]['file']);
        // Log::error($e);// Handle the exception how you'd like.
        echo "Error: " . $e;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }


}

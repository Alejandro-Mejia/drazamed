<?php

namespace App\Imports;

use App\Medicine;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnFailure};
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
// use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Validator;
use Artisan;


/** @brief Class for medicines
* @author Alejandro Mejia
* @date March 2020
*/
class Medicines2Import implements ToCollection, WithHeadingRow, WithBatchInserts , WithChunkReading, WithValidation, SkipsOnFailure, SkipsOnError
{


    use Importable, SkipsFailures;

    //

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
     *
    */
    public function collection(Collection $rows)
    {


        $numrows = $rows->count();
        $i = 0;
        foreach ($rows as $row) {
            $i = $i + 1;
            $exists = Medicine::where('item_code',$row['ean'])->where('batch_no',$row['lote'])->first();
            Log::info('Cargando .... ' . ( $i / $numrows) * 100 ) ;

            if($exists){

                Log::info('Producto existente en la DB :' . $exists['item_name']) ;
                $exists->manufacturer = isset($row['manufacturer']) ? $this->cleanManufacturer($row['proveedor']) : "ND";
                $exists->quantity = isset($row['cantidad']) ? $row['cantidad'] : 0;
                $exists->marked_price = isset($row['marcado']) ? $row['marcado']*1000 : 0;
                $exists->purchase_price = isset($row['venta_real']) ? $row['venta_real'] : 0;
                $exists->current_price = isset($row['venta_cte']) ? $row['venta_cte'] : 0;
                $exists->real_price = isset($row['venta_real']) ? $row['venta_real'] : 0;

                $exists->save();
            } else {
                if(Str::contains($row['denominacion'],  '(C)' ) || Str::contains($row['denominacion'],  'OBS ' ) || Str::contains($row['denominacion'],  'OBS.' ) || Str::contains($row['denominacion'],  'OBSEQUIO' )) {
                    Log::info('Producto controlado u obsequio. No se importa a la BD : ' . $row['denominacion']);
                } elseif(Str::contains($row['proveedor'],  'COMERC.MUNDIAL DE DEPORTE' )) {
                    Log::info('Laboratorio no aplicable :' . $row['proveedor']) ;
                } elseif(Str::contains($row['proveedor'],  'FLEXO SPRING S.A.' )) {
                    Log::info('Laboratorio no aplicable :' . $row['proveedor']) ;
                } elseif(Str::contains($row['proveedor'],  'ICARO DISENO Y PRODUCCION' )) {
                    Log::info('Laboratorio no aplicable :' . $row['proveedor']) ;
                } elseif(Str::contains($row['proveedor'],  'MB TECH DE COLOMBIA S.A.S' )) {
                    Log::info('Laboratorio no aplicable :' . $row['proveedor']) ;
                } elseif(Str::contains($row['proveedor'],  'MULTIOPCIONES PROMOCIONAL' )) {
                    Log::info('Laboratorio no aplicable :' . $row['proveedor']) ;
                } elseif(Str::contains($row['proveedor'],  'SFAGARO' )) {
                    Log::info('Laboratorio no aplicable :' . $row['proveedor']) ;
                } else {
                    Log::info('Producto nuevo :' . $row['denominacion']) ;
                    $medicine = new Medicine([
                        'provider'      => isset($row['proveedor']) ? $row['proveedor'] : "ND",
                        'item_name'     => isset($row['denominacion']) ?  $this->cleanDenomination($row['denominacion']) : "ND",
                        'denomination'  => isset($row['denominacion']) ? $row['denominacion'] : "ND",
                        'batch_no'      => isset($row['lote']) ? $row['lote'] : "ND",
                        'units'         => isset($row['denominacion']) ? $this->setUnits($row['denominacion']) : "ND",
                        'units_value'   => isset($row['denominacion']) ? $this->setUnitVal($row['denominacion']) : 0,
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
                    $medicine->save();
                }


            }
        }
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

                // if(Str::contains($value,  '(P)')) {
                //     $onFailure('Producto con precio marcado : ' . $value);
                // }


            },
            'proveedor' => function($attribute, $value, $onFailure) {
                if(Str::contains($value,  'COMERC.MUNDIAL DE DEPORTE' )) {
                    Log::info('Laboratorio no aplicable :' . $value) ;
                }
                if(Str::contains($value,  'FLEXO SPRING S.A.' )) {
                    Log::info('Laboratorio no aplicable :' . $value) ;
                }
                if(Str::contains($value,  'ICARO DISENO Y PRODUCCION' )) {
                    Log::info('Laboratorio no aplicable :' . $value) ;
                }
                if(Str::contains($value,  'MB TECH DE COLOMBIA S.A.S' )) {
                    Log::info('Laboratorio no aplicable :' . $value) ;
                }
                if(Str::contains($value,  'MULTIOPCIONES PROMOCIONAL' )) {
                    Log::info('Laboratorio no aplicable :' . $value) ;
                }
                if(Str::contains($value,  'SFAGARO S.A.S' )) {
                    Log::info('Laboratorio no aplicable :' . $value) ;
                }
                $onFailure('Laboratorio no aplicables. No se importa a la BD : ' . $value);
            },
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

    public function setUnits($value) {

        $value = $this->cleanDenomination($value);

        $unit = 'NoD';

        if (Str::contains($value,  ' ML' )) {
            $unit = "ML";
        }

        if (Str::contains($value,  ' GR' )) {
            $unit = "GR";
        }

        if (Str::contains($value,  ' CAP' )) {
            $unit = "CAP";
        }

        if (Str::contains($value,  ' AMPOLLA' )) {
            $unit = "AMP";
        }

        if (Str::contains($value,  ' TABLETAS' )) {
            $unit = "TAB";
        }

        if (Str::contains($value,  ' COMPRIMIDOS' )) {
            $unit = "COM";
        }

        if (Str::contains($value,  ' DOSIS' )) {
            $unit = "DOS";
        }

        if (Str::contains($value,  ' OVULOS' )) {
            $unit = "OVU";
        }

        if (Str::contains($value,  ' TBS' )) {
            $unit = "TAB";
        }

        if (Str::contains($value,  ' SBS' )) {
            $unit = "SBS";
        }

        if (Str::contains($value,  ' UDS' )) {
            $unit = "UDS";
        }

        if (Str::contains($value,  ' SUPOS' )) {
            $unit = "SUP";
        }

        if (Str::contains($value,  ' PARCHE' )) {
            $unit = "PCH";
        }

        if (Str::contains($value,  ' GRAGEAS' )) {
            $unit = "GRA";
        }

        return $unit;

    }


    public function setUnitVal($value) {

        $value = $this->cleanDenomination($value);

        $unit_val = 0;

        if(Str::contains($value,  'ML' )) {
            preg_match_all('/(\d+) (?: ML|ML)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : MILILITROS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }
        }

        if(Str::contains($value,  'DO' )) {
            preg_match_all('/(\d+) (?: DO|DO| DOSIS|DOSIS)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : DOSIS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'MILILITRO' )) {
            preg_match_all('/(\d+) (?:MILILITRO)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : MILILITROS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }
        }

        if(Str::contains($value,  ' CAP' )) {

            preg_match_all('/(\d+) (?:CAP|CAPSULAS)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : CAPSULAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

            preg_match_all('/(\d+) (?:CAP|CAPSULAS)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : CAPSULAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'UDS' )) {
            // preg_match_all('/(\d+) (?:UDS)/', $value, $matches);
            // if (isset($matches[1][0])) {
            //     Log::info('$value :' . $value . ', unidades : UNIDADES,  cantidad:' . print_r($matches[1][0], true)) ;
            //     $unit_val = $matches[1][0];
            // }

            preg_match_all('/(\d+) (?:UDS)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : UNIDADES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

            preg_match_all('/(\d+) (?:UDS)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : UNIDADES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'UNIDADES' )) {

            preg_match_all('/(\d+) (?:UNIDADES)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : UNIDADES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }
        }

        if(Str::contains($value,  ' GR' )) {
            preg_match_all('/(\d+) (?: GR|GR)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : GRAMOS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  ' MILIGRAMOS' )) {
            preg_match_all('/(\d+) (?: MILIGRAMOS|MILIGRAMOS)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : MILIGRAMOS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }
        }

        // if(Str::contains($value,  ' G(' )) {
        //     preg_match_all('/(\d+) (?:G/()/', $value, $matches);
        //     if (isset($matches[1][0])) {
        //         Log::info('$value :' . $value . ', unidades : GRAMOS,  cantidad:' . print_r($matches[1][0], true)) ;
        //         $unit_val = $matches[1][0];
        //     }

        // }


        if(Str::contains($value,  'AMPOLLA' )) {

            // Log::info('$value :' . $value . ', unidades : AMPOLLA,  cantidad:' . 1) ;
            $unit_val = 1;

        }

        if(Str::contains($value,  'AMPOLLAS' )) {
            preg_match_all('/(\d+) (?: AMPOLLAS|AMPOLLAS)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : AMPOLLAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }



        if(Str::contains($value,  'COMP' )) {
            preg_match_all('/(\d+) (?:COMP| COMP|COMPR| COMPR|COMPRIMIDOS| COMPRIMIDOS| COMP.| COMPR.)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : COMPRIMIDOS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'SUPOS' )) {
            preg_match_all('/(\d+) (?:SUPOS| SUPOS|SUPOSITO)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : SUPOSITORIOS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'PARCHE' )) {
            // Log::info('$value :' . $value . ', unidades : PARCHE,  cantidad:' . 1) ;
            $unit_val = 1;

        }

        if(Str::contains($value,  'KIT' )) {
            // Log::info('$value :' . $value . ', unidades : KIT,  cantidad:' . 1) ;
            $unit_val = 1;

        }


        if(Str::contains($value,  'CANULA' )) {
            // Log::info('$value :' . $value . ', unidades : CANULA,  cantidad:' . 1) ;
            $unit_val = 1;

        }

        if(Str::contains($value,  'PARCHES' )) {
            preg_match_all('/(\d+) (?: PARCHES|PARCHES)/', $value, $matches);
            if (isset($matches[1][0])) {
                // Log::info('$value :' . $value . ', unidades : PARCHES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }




        if(Str::contains($value,  'SBS' )) {
            // preg_match_all('/(\d+) (?:SBS)/', $value, $matches);
            // if (isset($matches[1][0])) {
            //     Log::info('$value :' . $value . ', unidades : SOBRES,  cantidad:' . print_r($matches[1][0], true)) ;
            //     $unit_val = $matches[1][0];
            // }

            preg_match_all('/(\d+) (?:SBS)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : SOBRES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }


        }

        if(Str::contains($value,  'SOBRES' )) {
            preg_match_all('/(\d+) (?:SOBRES)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : SOBRES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }
        }

        if(Str::contains($value,  'SOFTGEL' )) {
            preg_match_all('/(\d+) (?:SOFTGEL)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : SOFTGELS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }
        }

        if(Str::contains($value,  'APLICADOR' )) {
            preg_match_all('/(\d+) (?:APLICADOR)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : APLICADORES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }
        }

        if(Str::contains($value,  'OVULO' )) {
            Log::info('$value :' . $value . ', unidades : OVULO,  cantidad:' . 1) ;
            $unit_val = 1;

        }

        if(Str::contains($value,  'CASSETTE' )) {
            Log::info('$value :' . $value . ', unidades : CASSETTE,  cantidad:' . 1) ;
            $unit_val = 1;

        }
        if(Str::contains($value,  'TIRA' )) {
            Log::info('$value :' . $value . ', unidades : TIRA,  cantidad:' . 1) ;
            $unit_val = 1;

        }
        if(Str::contains($value,  'MIDSTREAM' )) {
            Log::info('$value :' . $value . ', unidades : MIDSTREAM,  cantidad:' . 1) ;
            $unit_val = 1;

        }

        if(Str::contains($value,  'OVULOS' )) {
            preg_match_all('/(\d+) (?:OVULOS)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : OVULOS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'CARPULE' )) {
            preg_match_all('/(\d+) (?:CARPULE)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : CARPULES,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }


        if(Str::contains($value,  ' TAB' )) {
            preg_match_all('/([\d]+) (?: TAB|TAB)/', $value, $matches);

            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : TABLETAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'TABLETAS' )) {
            preg_match_all('/(\d+) (?:TAB|TABLETAS| TABLETAS)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : TABLETAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'TB' )) {
            preg_match_all('/(\d+) (?: TB|TB)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : TABLETAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'TBS' )) {

            // preg_match_all('/(\d+) (?:TBS)/', $value, $matches);
            // if (isset($matches[1][0])) {
            //     Log::info('$value :' . $value . ', unidades : TABLETAS,  cantidad:' . print_r($matches[1][0], true)) ;
            //     $unit_val = $matches[1][0];
            // }


            preg_match_all('/(\d+) (?: TBS|TBS)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : TABLETAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }


        if(Str::contains($value,  'PASTILLA' )) {
            preg_match_all('/(\d+) (?: PASTILLA|PASTILLA)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : PASTILLAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }


        if(Str::contains($value,  'GRAGEAS' )) {
            preg_match_all('/(\d+) (?: GRAGEAS|GRAGEAS)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : GRAGEAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'PERLAS' )) {
            preg_match_all('/(\d+) (?: PERLAS|PERLAS)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : PERLAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        if(Str::contains($value,  'JERI' )) {
            preg_match_all('/(\d+) (?: JERI|JERI)/', $value, $matches);
            if (isset($matches[1][0])) {
                Log::info('$value :' . $value . ', unidades : JERINGAS PRELLENADAS,  cantidad:' . print_r($matches[1][0], true)) ;
                $unit_val = $matches[1][0];
            }

        }

        return $unit_val;

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

        if (Str::contains($value, 'ABBOT')) $value = "ABBOT";
        if (Str::contains($value, 'ALPINA')) $value = "ALPINA";
        if (Str::contains($value, 'GRUNENTHAL')) $value = "GRUNENTHAL";
        if (Str::contains($value, 'CORPORACION DE FOMENTO AS')) $value = "CORPAUL";
        if (Str::contains($value, 'FRESHLY')) $value = "NATURAL FRESHLY";

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
        $value = Str::replaceLast('(T', '', $value);
        $value = preg_replace_array('/\(M\)\d{3,6}/', [''], $value);
        // $value = preg_replace_array('/\(M\)\d{3,6}/', [''], $value);
        $value = preg_replace_array('/\(P\)\d{3,6}/', [''], $value);
        $value = Str::replaceLast('(A)', '', $value);
        $value = Str::replaceLast('(A', '', $value);
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

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'item_code';
    }


}

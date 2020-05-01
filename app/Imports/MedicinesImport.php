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
     *
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

            'marked_price'  => $row['marcado']*1000,
            // 'bonification'  => $row['boni'],


            'catalog'       => $row['catalogo'],
            // 'rack_number'   => $row['rack'],
            'composition'   => $row['subgrupo'],
            'manufacturer'  => $row['proveedor'],
            'marketed_by'   => $this->cleanManufacturer($row['proveedor']),
            'group'         => $row['grupo'],
            'is_pres_required' => ($row['grupo'] == 'ANTIBIOTICOS') ? 1 : 0,
            'subgroup'      => $row['subgrupo'],
            'item_code'     => $row['ean'],
            'tax_type'      => 'PERCENTAGE',
            'tax'           => $this->ivaImport($row['impuesto']) ,
            'purchase_price'=> $row['venta_real'],
            'selling_price' => $this->sellingPrice($row['venta_real'], $row['venta_cte'], $row['impuesto'], $row['proveedor'], $row['marcado']),
            'cost_price'    => $row['venta_real'],
            'current_price' => $row['venta_cte'],
            'real_price'    => $row['venta_real'],

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
        $value = Str::replaceLast(' S.A.S.', '', $value);
        $value = Str::replaceLast(' S.A.S', '', $value);
        $value = Str::replaceLast(' S.A.', '', $value);
        $value = Str::replaceLast(' S.A', '', $value);
        $value = Str::replaceLast(' LTDA.', '', $value);
        $value = Str::replaceLast(' LTDA', '', $value);
        $value = Str::replaceLast(' COLOMBIANA S.A', '', $value);
        $value = Str::replaceLast(' DE COLOMBIA S.A', '', $value);
        $value = Str::replaceLast(' DE COLOMBIA', '', $value);
        $value = Str::replaceLast(' DE COLOMBI', '', $value);
        $value = Str::replaceLast(' DE COLO', '', $value);
        $value = Str::replaceLast(' DE C', '', $value);

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

    public function sellingPrice($vtaReal, $vtaCte, $ivaImport, $manufacturer, $marked)
    {
        $sellprice = 0;
        $labs = [
            'A MENARINI LATI' => '0.1',
            'A.H ROBINS S.A.' => '0.1',
            'ABBOTT LABORATO' => '300',
            'AJB CORPORATION' => '0.2',
            'ALCON DE COLOMB' => '0.08',
            'ALFA TRADING LT' => '0.2',
            'ALLERGAN DE COL' => '0.08',
            'ALPINA PRODUCTO' => '0.05',
            'ALTIPAL S.A'     => '0.05',
            'AMAREY NOVA MED' => '0.2',
            'ASPEN COLOMBIAN' => '300',
            'ASTRA ZENECA CO' => '300',
            'AULEN PHARMA S.' => '0.1',
            'AVALON PHARMACE' => '300',
            'AXON PHARMA COL' => '0.1',
            'B C N MEDICAL S' => '0.1',
            'BAXTER S.A.'     => '300',
            'BAYER S.A.'      => '300',
            'BECTON DICKINSO' => '0.1',
            'BEIERSDORF S.A.' => '0.1',
            'BELLEZA EXPRESS' => '0.2',
            'BIG MEDICAL S.A' => '0.2',
            'BIOCHEM FARMACE' => '0.3',
            'BIOLATAM S.A.S'  => '300',
            'BIOLORE LTDA'    => '0.2',
            'BIOTOSCANA FARM' => '0.1',
            'BOEHRINGER INGE' => '300',
            'BSN MEDICAL LTD' => '0.2',
            'BUSSIE S.A.'     => '300',
            'C I BIOFLUIDOS ' => '0.2',
            'CHALVER DE COLO' => '0.1',
            'CLOSTER PHARMA ' => '0.2',
            'CML COLOMBIA LT' => '0.2',
            'COFARMA S.A.'    => '0.2',
            'COLOMBIANA KIMB' => '0.1',
            'COMERLAT PHARMA' => '0.1',
            'CORPAUL'         => '0.6',
            'DANONE BABY NUT' => '0.03',
            'DIABETRICS HEAL' => '300',
            'DISFARMACOL LTD' => '0.1',
            'DIST. DISANFER ' => '0.2',
            'DIST. RYAN  S. ' => '0.2',
            'ECAR LTDA'       => '0.3',
            'EDGEWELL PERSON' => '0.1',
            'ELI LILLY INTER' => '300',
            'ESPECIALIDADES ' => '0.1',
            'ETERNA S.A.'     => '0.4',
            'ETYC LTDA'       => '0.2',
            'EUROETIKA LTDA'  => '300',
            'EUROFARMA COLOM' => '0.2',
            'EVE DISTRIBUCIO' => '0.1',
            'EXELTIS S.A.S'   => '300',
            'FAES FARMA SAS'  => '0.2',
            'FARMA DE COLOMB' => '0.1',
            'FARMACIA DROGUE' => '0.2',
            'FARMACOL CHINOI' => '0.1',
            'FARMASER S.A.'   => '0.2',
            'FASAN LTDA'      => '0.1',
            'FRESENIUS KABI ' => '0.2',
            'GALDERMA DE COL' => '300',
            'GEDEON RICHTER ' => '300',
            'GENFAR S.A.'     => '0.2',
            'GENOMMA LAB.COL' => '0.1',
            'GERCO'           => '0.2',
            'GLAXO SMITHKLIN' => '300',
            'GLAXOSMITHKLINE' => '300',
            'GLENMARK PHARMA' => '300',
            'GRUINFACOL S.A.' => '0.1',
            'GRUNENTHAL COLO' => '300',
            'GRUPO UNIPHARM ' => '0.1',
            'HEALTHY AMERICA' => '0.2',
            'HECTOR RIVERA G' => '0.3',
            'HEEL COLOMBIA L' => '0.2',
            'HIGEA FARMACEUT' => '0.2',
            'HISPANIA LAB S.' => '0.1',
            'HUMAX PHARMACEU' => '0.2',
            'I LAB S.A.S.'    => '0.1',
            'ICOFARMA'        => '0.2',
            'INDUSTRIA NACIO' => '0.2',
            'INNOFAR'         => '0.1',
            'INTECMA S.A.'    => '0.1',
            'INTERNACIONAL D' => '0.2',
            'INVERFARMA SAS'  => '0.2',
            'IPCA LAB. LIMIT' => '0.1',
            'J.G.B. S.A.'     => '0.3',
            'JOHNSON & JOHNS' => '0.1',
            'KATORI'          => '0.2',
            'LA SANTE'        => '0.15',
            'LAB. ALDOQUIN L' => '0.2',
            'LAB. AMERICA S.' => '0.2',
            'LAB. ANTIBIL LT' => '0.4',
            'LAB. ARMOFAR'    => '0.2',
            'LAB. ASEPTIC S.' => '0.2',
            'LAB. ATHOS S.A.' => '0.2',
            'LAB. BAGO DE CO' => '0.1',
            'LAB. BEST S.A.'  => '0.2',
            'LAB. BIOPAS S.A' => '300',
            'LAB. BLOFARMA D' => '0.4',
            'LAB. FARMACEUTI' => '0.1',
            'LAB. FARPAG'     => '0.2',
            'LAB. INCOBRA S.' => '0.15',
            'LAB. LEGRAND S.' => '300',
            'LAB. LEON LTDA'  => '0.2',
            'LAB. LICOL LTDA' => '0.2',
            'LAB. NATURAL FR' => '400',
            'LAB. PRONABELL ' => '300',
            'LAB. QUIPROPHAR' => '400',
            'LAB. SERES LTDA' => '0.1',
            'LAB. SERVIER DE' => '400',
            'LAB. SOREL S.A.' => '0.1',
            'LAB. TAKEDA S.A' => '300',
            'LAB.DROFARMA S.' => '0.15',
            'LAB.SIEGFRIED S' => '400',
            'LAB.SOPHIA DE C' => '0.2',
            'LABFARVE'        => '0.1',
            'LABINCO S.A.'    => '0.15',
            'LABORATORIOS EL' => '0.2',
            'LABORATORIOS ES' => '0.1',
            'LABORATORIOS FU' => '400',
            'LABORATORIOS ME' => '0.1',
            'LABORATORIOS RI' => '400',
            'LACICO S.A.'     => '0.1',
            'LAPROFF'         => '0.3',
            'LUZ ZUL S.A.S'   => '0.1',
            'MAGDALENA RAMIR' => '0.2',
            'MARYDDAN MEDICA' => '0.2',
            'MEAD JOHNSON NU' => '300',
            'MEDICAL SUPPLIE' => '0.2',
            'MEMPHIS PRODUCT' => '400',
            'MERCK S.A.'      => '400',
            'MERCK SHARP & D' => '300',
            'MEREY LTDA'      => '0.2',
            'MINAGRO LTDA'    => '0.1',
            'MUNDIPHARMA (CO' => '400',
            'NATURCOL LTDA'   => '0.2',
            'NEO LTDA'        => '0.1',
            'NEPAL'           => '0.2',
            'NESTLE DE COLOM' => '300',
            'NEVOXFARMA'      => '400',
            'NEWELL BRANDS D' => '0.2',
            'NOVAMED S.A.'    => '300',
            'NOVARTIS DE COL' => '300',
            'NTI NEW TRADE I' => '0.2',
            'OPTION S.A'      => '0.2',
            'ORVIX FARMACEUT' => '300',
            'PELGOR S.A.'     => '0.2',
            'PFIZER S.A.S'    => '300',
            'PHARMAPRIX COLO' => '400',
            'PISA FARMACEUTI' => '0.1',
            'PREBEL'          => '0.1',
            'PROCAPS S.A.'    => '400',
            'PROCLIN PHARMA ' => '400',
            'PROCTER & GAMBL' => '400',
            'PRODUCTOS DROGA' => '0.2',
            'PRODUCTOS FAMIL' => '0.1',
            'PRODUCTOS ROCHE' => '300',
            'PROFAMILIA'      => '0.1',
            'PROMEGAN LTDA'   => '0.2',
            'PROTEX S.A.'     => '0.1',
            'PURGANTE Y TONI' => '0.2',
            'QUIBI S.A.'      => '0.1',
            'QUIDECA S.A.'    => '400',
            'QUIFARMA LTDA'   => '400',
            'RB HEALTH COLOM' => '0.2',
            'RB HEALTH COLOM' => '300',
            'RECAMIER S.A.'   => '0.1',
            'REMO LTDA'       => '0.1',
            'RIOSOL LTDA'     => '0.1',
            'ROPSOHN THERAPE' => '400',
            'S.C. JOHNSON CO' => '0.1',
            'SANOFI AVENTIS ' => '400',
            'SANULAC NUTRICI' => '300',
            'SCANDINAVIA PHA' => '400',
            'TECNOFARMA S.A'  => '400',
            'TRES M COLOMBIA' => '0.1',
            'TRIDEX S.A.'     => '400',
            'VELEZ y GOMEZ  ' => '0.2',
            'VITALIS S.A C.I' => '0.3',
            'VON HALLER LTDA' => '400',
            'ZAMBON'          => '400',
        ];

        if($marked == 0) {
            switch ($ivaImport) {

                case '19%  IVA':
                    $sellprice = $vtaReal / 0.71;
                    break;
                case '5%  IVA':
                    $sellprice = $vtaReal / 0.85;
                    break;
                default:
                    {
                        if (array_key_exists(substr($manufacturer, 0, 15),$labs)) {
                            if((int)$labs[substr($manufacturer, 0, 15)] > 1) {
                                echo ".";
                                // Descuento
                                $sellprice = $vtaReal + (int)$labs[substr($manufacturer, 0, 15)];
                            } else {
                                echo '%';
                                // Porcentaje
                                $sellprice = $vtaReal + $vtaReal * (float)$labs[substr($manufacturer, 0, 15)];
                            }

                        } else {
                            echo 'x';
                            // $sellprice = $sellprice;
                        }

                    }
                    break;
            }
        } else {
            $sellprice = $marked * 1000;
        }

        return $sellprice;
    }



}

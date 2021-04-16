<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Exports\ExportUsers;
use App\Imports\ImportUsers;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExport()
    {
       return view('import');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    // public function export()
    // {
    //     return Excel::download(new ExportUsers, 'medicine.xlsx');
    // }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        Excel::import(new ImportMedicines, request()->file('file'));

        return back();
    }
}
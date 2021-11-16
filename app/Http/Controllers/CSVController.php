<?php
namespace App\Http\Controllers;
use App\Imports\productImport;
use Maatwebsite\Excel\Facades\Excel;
use Config;
class CSVController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {
        return view('csv.import');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {
        Excel::import(new productImport,request()->file('file'));
        return back();
    }
}

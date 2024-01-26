<?php

namespace App\Http\Controllers;

use file;
use Illuminate\Http\Request;
use App\Imports\ImportStudent;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('excel_file');

        Excel::import(new ImportStudent, $file);

        return redirect()->back()->with('success', 'Data berhasil diimpor.');
    }

    public function bulkImport(Request $request)
    {
        $file = $request->file('excel_file');

        Excel::import(new ImportStudent, $file);

        return redirect()->back()->with('success', 'Data berhasil diimpor.');
    }
}

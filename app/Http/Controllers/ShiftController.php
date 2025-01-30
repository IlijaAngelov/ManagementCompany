<?php

namespace App\Http\Controllers;

use App\Actions\ImportCsvAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShiftController extends Controller
{

    public function importShifts(Request $request, ImportCsvAction $importCsvAction): RedirectResponse
    {
        $importCsvAction->handle($request);
        return redirect()->route('import')->with('success', 'Importing has been started. It may take a while!');

    }

    public function importToFile(Request $request): array
    {
        $data = [];
        $header = null;
        if(($handle = fopen($request->file("import_csv"), "r")) !== false) {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if(!$header){
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                    $rows = implode(",", $row);
                    Storage::append('import.csv', $rows);
                }
            }
            fclose($handle);
        }
        return $data;
    }

}

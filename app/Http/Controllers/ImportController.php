<?php

namespace App\Http\Controllers;

use App\Models\Import;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function importCSV(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'import_csv' => 'required|file|mimes:csv|max:61440'
        ]);

        $file = $request->file('import_csv');
        $handle = fopen($file->path(), 'r');
        fgetcsv($handle);

        while(! feof($handle))
        {
            for ($i = 0; $i < 1; $i++) {
                $data = fgetcsv($handle);
                if($data === false) {
                    break;
                }
            }

            try {
                $import = new Import();
                $import->employee = $data[1];
                $import->employer = $data[2];
                $import->hours = $data[3];
                $import->rate_per_hour = $data[4];
                $import->taxable = $data[5];
                $import->status = $data[6];
                $import->shift_type = $data[7];
                $import->paid_at = empty($data[8]) ? null : Carbon::parse($data[8]);
                $import->date = $data[0];
                $import->save();
            } catch(\Exception $e) {
                dd($e->getMessage());
            }
        }
        fclose($handle);

        return redirect()->route('import')->with('success', 'Import has been finished');

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

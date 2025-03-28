<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ImportCsvAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ShiftController extends Controller
{
    public function importShifts(Request $request, ImportCsvAction $importCsvAction): JsonResponse
    {
        try {
            $importCsvAction->handle($request);

            return response()->json([
                'message' => 'Importing has started. It may take a while'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @return array<int, array<string, string|null>>
     */
    public function importToFile(Request $request): array
    {
        $data = [];
        $header = null;
        if (($handle = fopen($request->file('import_csv'), 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                    $rows = implode(',', $row);
                    Storage::append('import.csv', $rows);
                }
            }
            fclose($handle);
        }

        return $data;
    }
}

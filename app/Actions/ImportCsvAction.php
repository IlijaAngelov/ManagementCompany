<?php

declare(strict_types=1);

namespace App\Actions;

use App\Jobs\ShiftImportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class ImportCsvAction
{
    public function __construct() {}

    public function handle(Request $request): void
    {
        $path = Storage::disk('local')->put('/import/shifts', $request->file('import_csv'));

        dispatch(new ShiftImportJob($path));

    }
}

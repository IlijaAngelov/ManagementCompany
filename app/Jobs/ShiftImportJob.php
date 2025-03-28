<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\ImportShift;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

final class ShiftImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 1200;

    public function __construct(public bool|string $path) {}

    public function handle(): void
    {
        SimpleExcelReader::create(Storage::disk('local')->path($this->path))
            ->getRows()
            ->chunk(1000)
            ->each(function ($row) {
                foreach ($row as $item) {
                    $import = new ImportShift;
                    $import->employee = $item['Employee'];
                    $import->employer = $item['Employer'];
                    $import->hours = $item['Hours'];
                    $import->rate_per_hour = $item['Rate per Hour'];
                    $import->taxable = $item['Taxable'];
                    $import->status = $item['Status'];
                    $import->shift_type = $item['Shift Type'];
                    $import->paid_at = empty($item[8]) ? null : (Carbon::hasFormat($item[8], 'Y-m-d') ? Carbon::parse($item[8]) : null);
                    $import->date = $item['Date'];
                    $import->save();
                }
            });

        Storage::delete($this->path);
    }
}

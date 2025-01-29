<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200;
    public function __construct(private string $filePath)
    {
        //
    }

    public function handle(): void
    {
        $map = [
            'date' => 0,
            'employee' => 1,
            'employer' => 2,
            'hours' => 3,
            'rate_her_hour' => 4,
            'taxable' => 5,
            'status' => 6,
            'shift_type' => 7,
            'paid_at' => 8
        ];

        $fileStream = fopen($this->filePath, 'r');
        $skipHeader = true;

        while($row = fgetcsv($fileStream)) {
            if($skipHeader) {
                $skipHeader = false;
                continue;
            }
            dispatch(new ProcessImportJob((string)$row, $map));
        }

        fclose($fileStream);
        unlink($this->filePath);
    }
}

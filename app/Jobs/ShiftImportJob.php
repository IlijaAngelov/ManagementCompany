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
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDOStatement;
use Illuminate\Support\Facades\DB;

final class ShiftImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 3600;

    public function __construct(public bool|string $path)
    {
    }

    public function handle(): void
    {
        $now = now()->format('Y-m-d H:i:s');
        $numberOfProcesses = 10;
        $filePath = Storage::disk('local')->path($this->path);
        Log::info("Started");
        $tasks = [];
        for ($i = 0; $i < $numberOfProcesses; $i++) {
            $tasks[] = function () use ($filePath, $i, $numberOfProcesses, $now) {
                DB::reconnect();

                $handle = fopen($filePath, 'r');
                fgets($handle); // Skip header
                $currentLine = 0;
                $customers = [];

                while (($line = fgets($handle)) !== false) {
                    // Each process takes every Nth line
                    if ($currentLine++ % $numberOfProcesses !== $i) {
                        continue;
                    }

                    $row = str_getcsv($line);
                    $customers[] = [
                        'employee' => $row[1],
                        'employer' => $row[2],
                        'hours' => $row[3],
                        'rate_per_hour' => $row[4],
                        'taxable' => $row[5],
                        'status' => $row[6],
                        'shift_type' => $row[7],
                        'paid_at' => $now,
                        'date' => $row[0],
                    ];

                    if (count($customers) === 1000) {
                        DB::table('imports')->insert($customers);
                        $customers = [];
                    }
                }

                if (!empty($customers)) {
                    DB::table('imports')->insert($customers);
                }

                fclose($handle);

                return true;
            };
        }

        Concurrency::run($tasks);
        Log::info("Finished");

    }

}

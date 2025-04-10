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
        Log::info("Started");
        $chunkSize = 500;
        $chunks = [];
        $handle = fopen(Storage::disk('local')->path($this->path), 'r');
        fgetcsv($handle);

        try {
            $pdo = DB::connection()->getPdo();
            $stmt = $this->prepareChunkedStatement($chunkSize);

            while (($line = fgetcsv($handle)) !== false) {
                $chunks = array_merge($chunks, [
                    $line[1], $line[2], $line[3], $line[4], $line[5], $line[6], $line[7],
                    $line[8] ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $line[8])->format('Y-m-d H:i:s') : null,
                    $line[0],
                ]);

                if (count($chunks) === $chunkSize * 9) {
                    $stmt->execute($chunks);
                    $chunks = [];
                }
            }

            if (!empty($chunks)) {
                $remainingRows = count($chunks) / 9;
                $stmt = $this->prepareChunkedStatement($remainingRows);
                $stmt->execute($chunks);
            }
        } finally {
            fclose($handle);
        }

        Log::info("Ended");

    }

    private function prepareChunkedStatement($chunkSize): PDOStatement
    {
        $rowPlaceholders = '(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $placeholders = implode(',', array_fill(0, $chunkSize, $rowPlaceholders));

        return DB::connection()->getPdo()->prepare("INSERT INTO imports (employee, employer, hours, rate_per_hour, taxable, status, shift_type, paid_at, date) VALUES {$placeholders}");
    }
}

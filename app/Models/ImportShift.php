<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ImportShift extends Model
{
    /** @use HasFactory<\Database\Factories\ImportFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'imports';

    protected $fillable = [
        'employee',
        'employer',
        'hours',
        'rate_per_hour',
        'taxable',
        'status',
        'shift_type',
        'paid_at',
        'date',
    ];
}

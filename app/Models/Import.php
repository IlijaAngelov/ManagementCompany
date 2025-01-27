<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    /** @use HasFactory<\Database\Factories\ImportFactory> */
    use HasFactory;

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

    public $timestamps = false;


}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();

            $table->string('employee');
            $table->string('employer');

            $table->string('hours');
            $table->string('rate_per_hour');
            $table->string('taxable');
            $table->string('status');
            $table->string('shift_type');

            $table->timestamp('paid_at')->nullable();
            $table->date('date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};

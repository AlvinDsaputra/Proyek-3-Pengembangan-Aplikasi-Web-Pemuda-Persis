<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizational_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('period_id')->constrained('organizational_periods')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('organizational_positions');
            // NULL untuk Ketua dan Sekretaris (tidak berada di bawah bidang)
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('organizational_departments')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizational_assignments');
    }
};

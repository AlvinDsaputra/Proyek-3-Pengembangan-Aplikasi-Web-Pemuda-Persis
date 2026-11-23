<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_number_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizational_period_id')->constrained('organizational_periods')->cascadeOnDelete();
            $table->foreignId('letter_type_id')->nullable()->constrained('letter_types')->nullOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();

            $table->unique(['organizational_period_id', 'year'], 'unique_period_year_sequence');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_number_sequences');
    }
};
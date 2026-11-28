<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')
                ->constrained('organizational_departments')
                ->restrictOnDelete();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('speaker_name')->nullable();
            $table->string('location');
            $table->string('contact_person')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->unsignedInteger('quota')->default(0);
            $table->enum('status', [
                'draft',
                'published',
                'closed',
                'completed',
                'cancelled',
            ])->default('draft');
            $table->text('cancellation_reason')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['status', 'start_datetime']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

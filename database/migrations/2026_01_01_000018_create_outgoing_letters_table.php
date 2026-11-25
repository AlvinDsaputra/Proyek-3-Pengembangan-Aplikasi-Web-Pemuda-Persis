<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outgoing_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizational_period_id')->constrained('organizational_periods')->restrictOnDelete();
            $table->foreignId('letter_type_id')->constrained('letter_types')->restrictOnDelete();
            $table->foreignId('letter_destination_code_id')->nullable()->constrained('letter_destination_codes')->nullOnDelete();
            $table->foreignId('letter_template_id')->nullable()->constrained('letter_templates')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->unsignedInteger('sequence_number')->nullable();
            $table->string('letter_number', 100)->nullable()->unique();
            $table->date('letter_date');
            $table->string('subject', 255);
            $table->string('recipient_name', 255);
            $table->text('recipient_address')->nullable();
            $table->string('attachment', 100)->nullable()->default('-');
            $table->longText('body');

            $table->enum('status', [
                'draft',
                'pending_approval',
                'revision_needed',
                'approved',
                'final',
            ])->default('draft');

            $table->text('revision_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->string('verification_token', 64)->nullable()->unique();
            $table->timestamps();

            $table->index(['organizational_period_id', 'status']);
            $table->index('letter_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outgoing_letters');
    }
};
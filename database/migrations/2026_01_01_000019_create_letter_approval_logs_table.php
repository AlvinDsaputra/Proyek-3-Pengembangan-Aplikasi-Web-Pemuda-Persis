<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_approval_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outgoing_letter_id')->constrained('outgoing_letters')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('action', [
                'submitted',
                'resubmitted',
                'approved',
                'revision_requested',
                'rejected',
                'finalized',
            ]);
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_approval_logs');
    }
};
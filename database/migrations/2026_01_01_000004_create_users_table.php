<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('npa', 50)->nullable()->unique();
            // FK ke provincial_branches.code (varchar); null = admin/pengguna tanpa PC
            $table->string('asal_pc_code', 20)->nullable();
            $table->foreign('asal_pc_code')
                ->references('code')
                ->on('provincial_branches')
                ->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini hanya berisi SATU baris: profil PD Pemuda Persis
        Schema::create('organization_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('org_name');
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path', 500)->nullable();
            // Cap organisasi — disimpan di storage/app/private/stamps/
            $table->string('stamp_path', 500)->nullable();
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_profiles');
    }
};

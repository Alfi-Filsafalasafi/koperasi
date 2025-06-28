<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('simpanans', function (Blueprint $table) {
            $table->id();
            $table->string('id_simpanan', 20)->nullable();
            $table->foreignId('anggota_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis_simpanan', ['Simpanan Pokok', 'Simpanan Wajib', 'Simpanan Sukarela']);
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->decimal('total_saldo', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};
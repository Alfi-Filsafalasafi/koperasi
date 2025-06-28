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
        Schema::create('bagi_hasil_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan_bagi_hasils')->onDelete('cascade');
            $table->foreignId('anggota_id')->constrained('users')->onDelete('cascade');
            $table->decimal('persentase', 5, 2);
            $table->decimal('jumlah_dibagi', 15, 2);
            $table->enum('status_aktif', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bagi_hasil_detail');
    }
};
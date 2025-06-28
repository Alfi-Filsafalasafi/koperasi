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
        Schema::create('transaksi_simpanans', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi_simpanan')->nullable();
            $table->foreignId('simpanan_id')->constrained('simpanans')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['setor', 'tarik']);
            $table->decimal('jumlah', 15, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_simpanan');
    }
};
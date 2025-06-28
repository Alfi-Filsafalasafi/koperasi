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
        Schema::create('transaksi_pinjamans', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi_pinjaman')->nullable();
            $table->foreignId('pinjaman_id')->constrained('pinjamans')->onDelete('cascade');
            $table->date('tanggal_bayar');
            $table->decimal('pembayaran_pokok', 15, 2);
            $table->decimal('pembayaran_bunga', 15, 2);
            $table->decimal('pembayaran_denda', 15, 2);
            $table->integer('cicilan_ke');
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
        Schema::dropIfExists('transaksi_pinjaman');
    }
};

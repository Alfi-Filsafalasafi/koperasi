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
        Schema::create('jurnal_kas_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggota_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tanggal'); // Tanggal
            $table->string('no_bukti'); // No bukti
            $table->text('uraian'); // Uraian
            $table->string('akun_debit'); // Akun debit
            $table->string('akun_kredit'); // Akun kredit
            $table->decimal('nominal_debit', 15, 2); // Nominal debit
            $table->decimal('nominal_kredit', 15, 2); // Nominal kredit
            $table->decimal('pembayaran_pokok', 15, 2); // Nominal debit
            $table->decimal('pembayaran_kredit', 15, 2); // Nominal kredit
            $table->decimal('pembayaran_denda', 15, 2); // Nominal debit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_kas_masuk');
    }
};

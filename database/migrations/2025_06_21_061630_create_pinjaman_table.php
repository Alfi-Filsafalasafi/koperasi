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
        Schema::create('pinjamans', function (Blueprint $table) {
            $table->id();
            $table->string('id_pinjaman')->nullable();
            $table->foreignId('anggota_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis_pinjaman', ['Pinjaman Modal Usaha', 'Pinjaman Pembiayaan Multi Guna', 'Pinjaman Pembiayaan Umroh']);
            $table->date('tanggal_pinjaman');
            $table->string('jangka_waktu');
            $table->decimal('jumlah_pinjaman', 15, 2);
            $table->decimal('bunga', 15, 2);
            $table->decimal('nisbah', 15, 2);
            $table->decimal('angsuran_pokok', 15, 2)->default(0);
            $table->date('tanggal_jatuh_tempo');
            $table->decimal('total_pinjaman', 15, 2)->default(0);
            $table->decimal('sisa_pinjaman', 15, 2)->default(0);
            $table->enum('status', ['belum lunas', 'lunas'])->default('belum lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
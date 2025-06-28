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
        Schema::create('laporan_bagi_hasils', function (Blueprint $table) {
            $table->id(); // No
            $table->string('kode', 20)->unique(); // Kode laporan (misal: BGH-2025-01)

            // Nisbah (persentase bagi hasil untuk anggota koperasi ini)
            $table->decimal('nisbah', 5, 2); // misal 60.00 berarti 60%

            $table->year('tahun_awal'); // Tahun awal periode
            $table->year('tahun_akhir'); // Tahun akhir periode (bisa sama dengan awal kalau 1 tahun)
            $table->string('periode'); // Periode detail (misal: 'Januari - Juni 2025')

            $table->decimal('besaran_nisbah', 15, 2); // Uang yang diterima anggota (nisbah * pendapatan_dibagi)

            $table->decimal('total_pendapatan', 15, 2); // Total pendapatan koperasi selama periode
            $table->decimal('pendapatan_dibagi', 15, 2); // Pendapatan yang dibagikan (nisbah * total)
            $table->decimal('pendapatan_ditahan', 15, 2); // Sisanya (untuk kas koperasi, cadangan, dll)

            $table->integer('jumlah_hari'); // Durasi periode pembagian
            $table->boolean('is_cetak')->default(false); // Status cetak laporan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_bagi_hasil');
    }
};

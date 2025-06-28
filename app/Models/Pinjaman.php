<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjamans';
    protected $fillable = ['id_pinjaman', 'anggota_id', 'jenis_pinjaman', 'tanggal_pinjaman', 'jangka_waktu',
                            'jumlah_pinjaman', 'bunga', 'nisbah',  'angsuran_pokok', 'tanggal_jatuh_tempo',
                            'sisa_pinjaman', 'total_pinjaman', 'status'];

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiPinjaman::class);
    }
}

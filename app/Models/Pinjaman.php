<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjamans';
    protected $fillable = ['id_pinjaman', 'anggota_id', 'jumlah_pinjaman', 'bunga', 'jangka_waktu',
                            'jenis_pinjaman','tanggal_pinjaman', 'tanggal_jatuh_tempo', 'angsuran_pokok', 'sisa_pinjaman', 'status'];

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiPinjaman::class);
    }
}

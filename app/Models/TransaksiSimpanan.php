<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiSimpanan extends Model
{
    protected $table = 'transaksi_simpanans';
    protected $fillable = ['id_transaksi_simpanan', 'simpanan_id', 'tanggal', 'jenis_transaksi', 'jumlah', 'keterangan', 'user_id'];

    public function simpanan()
    {
        return $this->belongsTo(Simpanan::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
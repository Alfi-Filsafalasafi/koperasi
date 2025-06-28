<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPinjaman extends Model
{
    protected $table = 'transaksi_pinjamans';
    protected $fillable = ['id_transaksi_pinjaman', 'pinjaman_id', 'tanggal_bayar', 'pembayaran_pokok', 'pembayaran_bunga',
                            'pembayaran_denda', 'cicilan_ke',
                            'keterangan', 'user_id'];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    protected $table = 'simpanans';
    protected $fillable = ['id_simpanan', 'anggota_id', 'jenis_simpanan', 'saldo_awal', 'total_saldo', 'keterangan'];

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiSimpanan::class);
    }
}
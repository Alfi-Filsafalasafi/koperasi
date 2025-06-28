<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_anggota', 'nama_lengkap', 'no_ktp', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'alamat', 'pekerjaan', 'tanggal_masuk', 'no_telp', 'email', 'password',
        'role', 'status_aktif', 'is_anggota'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function simpanan(): HasMany
    {
        return $this->hasMany(Simpanan::class, 'anggota_id');
    }

    public function pinjaman(): HasMany
    {
        return $this->hasMany(Pinjaman::class, 'anggota_id');
    }

    public function jurnalKasMasuk(): HasMany
    {
        return $this->hasMany(JurnalKasMasuk::class, 'anggota_id');
    }

    public function jurnalKasKeluar(): HasMany
    {
        return $this->hasMany(JurnalKasKeluar::class, 'anggota_id');
    }

    public function bagiHasil(): HasMany
    {
        return $this->hasMany(BagiHasilDetail::class, 'anggota_id');
    }
}
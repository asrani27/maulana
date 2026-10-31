<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keluarga extends Model
{
    use HasFactory;

    protected $table = 'keluarga';

    protected $fillable = [
        'no_kk',
        'nik_kepala',
        'nama_kepala_keluarga',
        'jenis_kelamin',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
    ];

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'keluarga_id');
    }

    public function hasilSaw(): HasMany
    {
        return $this->hasMany(HasilSaw::class, 'keluarga_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode',
        'nama_kriteria',
        'bobot',
        'atribut',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'float',
        ];
    }

    public function subKriteria(): HasMany
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }
}

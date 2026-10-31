<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periode';

    protected $fillable = [
        'nama_periode',
        'tahun',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
        ];
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'periode_id');
    }

    public function hasilSaw(): HasMany
    {
        return $this->hasMany(HasilSaw::class, 'periode_id');
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'periode_id',
        'keluarga_id',
        'kriteria_id',
        'sub_kriteria_id',
        'nilai',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'float',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(Keluarga::class, 'keluarga_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function subKriteria(): BelongsTo
    {
        return $this->belongsTo(SubKriteria::class, 'sub_kriteria_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSaw extends Model
{
    use HasFactory;

    protected $table = 'hasil_saw';

    protected $fillable = [
        'periode_id',
        'keluarga_id',
        'nilai_akhir',
        'ranking',
        'status_kelayakan',
    ];

    protected function casts(): array
    {
        return [
            'nilai_akhir' => 'float',
            'ranking' => 'integer',
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
}

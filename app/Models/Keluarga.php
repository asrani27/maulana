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

    public function getNikKepalaKeluargaAttribute(): ?string
    {
        return $this->attributes['nik_kepala'] ?? null;
    }

    public function setNikKepalaKeluargaAttribute($value): void
    {
        $this->attributes['nik_kepala'] = $value;
    }

    public function getPekerjaanAttribute(): ?string
    {
        if (isset($this->attributes['pekerjaan'])) {
            return $this->attributes['pekerjaan'];
        }

        $c1 = $this->relationLoaded('penilaian')
            ? $this->penilaian->firstWhere('kriteria.kode', 'C1')
            : $this->penilaian()->whereHas('kriteria', fn ($q) => $q->where('kode', 'C1'))->with('subKriteria')->first();

        return $c1?->subKriteria?->nama_subkriteria ?? '-';
    }

    public function getJumlahTanggunganAttribute(): string|int
    {
        if (isset($this->attributes['jumlah_tanggungan'])) {
            return $this->attributes['jumlah_tanggungan'];
        }

        $c2 = $this->relationLoaded('penilaian')
            ? $this->penilaian->firstWhere('kriteria.kode', 'C2')
            : $this->penilaian()->whereHas('kriteria', fn ($q) => $q->where('kode', 'C2'))->with('subKriteria')->first();

        return $c2?->subKriteria?->nama_subkriteria ?? '-';
    }

    public function getPenghasilanBulananAttribute(): mixed
    {
        if (isset($this->attributes['penghasilan_bulanan'])) {
            return $this->attributes['penghasilan_bulanan'];
        }

        $c4 = $this->relationLoaded('penilaian')
            ? $this->penilaian->firstWhere('kriteria.kode', 'C4')
            : $this->penilaian()->whereHas('kriteria', fn ($q) => $q->where('kode', 'C4'))->with('subKriteria')->first();

        return $c4?->subKriteria?->nama_subkriteria ?? '-';
    }
}

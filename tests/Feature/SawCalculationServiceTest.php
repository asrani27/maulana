<?php

use App\Models\HasilSaw;
use App\Models\Keluarga;
use App\Models\Kriteria;
use App\Models\Periode;
use App\Services\SawCalculationService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('saw calculation service returns correct matrix and rankings', function () {
    $this->seed(DatabaseSeeder::class);

    $periode = Periode::where('status', 'aktif')->first();
    expect($periode)->not->toBeNull();

    $service = new SawCalculationService();

    // 1. Matrix X
    $matrix = $service->getDecisionMatrix($periode->id);
    expect($matrix['rows'])->toHaveCount(5);

    // 2. MinMax
    $kriteriaList = Kriteria::orderBy('kode')->get();
    $minMax = $service->getMinMaxValues($matrix, $kriteriaList);
    expect($minMax)->toHaveKeys(['C1', 'C2', 'C3', 'C4']);
    expect($minMax['C4']['min'])->toBeGreaterThan(0);

    // 3. Normalization
    $normalized = $service->calculateNormalization($matrix, $kriteriaList, $minMax);
    expect($normalized['rows'])->toHaveCount(5);

    // 4. Preferences & Rankings
    $ranked = $service->getRankedResults($periode->id);
    expect($ranked)->toHaveCount(5);
    expect($ranked[0]['ranking'])->toBe(1);
    expect($ranked[0]['nilai_akhir'])->toBeGreaterThanOrEqual($ranked[1]['nilai_akhir']);

    // 5. Saved results
    $savedCount = HasilSaw::where('periode_id', $periode->id)->count();
    expect($savedCount)->toBe(5);
});


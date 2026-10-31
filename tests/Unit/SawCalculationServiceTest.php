<?php

namespace Tests\Unit;

use App\Models\Keluarga;
use App\Models\Kriteria;
use App\Services\SawCalculationService;
use Tests\TestCase;

class SawCalculationServiceTest extends TestCase
{
    public function test_normalization_and_preference_math(): void
    {
        $service = new SawCalculationService();

        $c1 = new Kriteria(['kode' => 'C1', 'nama_kriteria' => 'Tanggungan', 'bobot' => 0.25, 'atribut' => 'benefit']);
        $c2 = new Kriteria(['kode' => 'C2', 'nama_kriteria' => 'Penghasilan', 'bobot' => 0.30, 'atribut' => 'cost']);

        $kriteriaList = collect([$c1, $c2]);

        $rows = [
            [
                'keluarga_id' => 1,
                'keluarga' => new Keluarga(['nama_kepala_keluarga' => 'Budi']),
                'values' => ['C1' => 4.0, 'C2' => 1000000.0],
            ],
            [
                'keluarga_id' => 2,
                'keluarga' => new Keluarga(['nama_kepala_keluarga' => 'Siti']),
                'values' => ['C1' => 2.0, 'C2' => 500000.0],
            ],
        ];

        $minMax = $service->getMinMaxValues($rows, $kriteriaList);

        // C1 Benefit: Max is 4.0, Min is 2.0
        $this->assertEquals(4.0, $minMax['C1']['max']);
        $this->assertEquals(2.0, $minMax['C1']['min']);

        // C2 Cost: Max is 1000000, Min is 500000
        $this->assertEquals(1000000.0, $minMax['C2']['max']);
        $this->assertEquals(500000.0, $minMax['C2']['min']);

        // Normalization:
        $normalized = $service->calculateNormalization($rows, $kriteriaList, $minMax);
        $row1 = $normalized['rows'][0];
        $row2 = $normalized['rows'][1];

        // Budi C1 (benefit): 4 / 4 = 1.0000
        $this->assertEquals(1.0, $row1['values']['C1']);
        // Budi C2 (cost): 500,000 / 1,000,000 = 0.5000
        $this->assertEquals(0.5, $row1['values']['C2']);

        // Siti C1 (benefit): 2 / 4 = 0.5000
        $this->assertEquals(0.5, $row2['values']['C1']);
        // Siti C2 (cost): 500,000 / 500,000 = 1.0000
        $this->assertEquals(1.0, $row2['values']['C2']);

        // Preferences:
        $preferences = $service->calculatePreferences($normalized, $kriteriaList);
        $p1 = $preferences['rows'][0]; // Budi: 0.25*1.0 + 0.30*0.5 = 0.25 + 0.15 = 0.40
        $p2 = $preferences['rows'][1]; // Siti: 0.25*0.5 + 0.30*1.0 = 0.125 + 0.30 = 0.425

        $this->assertEquals(0.4000, $p1['nilai_akhir']);
        $this->assertEquals(0.4250, $p2['nilai_akhir']);
    }
}

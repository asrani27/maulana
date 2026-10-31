<?php

namespace App\Services;

use App\Models\HasilSaw;
use App\Models\Keluarga;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Periode;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SawCalculationService
{
    /**
     * Get the decision matrix X for the given period.
     *
     * @return array<string, mixed>
     */
    public function getDecisionMatrix(int $periodeId): array
    {
        $periode = Periode::find($periodeId);
        if (! $periode) {
            return [
                'periode' => null,
                'kriteria' => collect(),
                'rows' => [],
            ];
        }

        $kriteriaList = Kriteria::orderBy('kode')->get();
        if ($kriteriaList->isEmpty()) {
            return [
                'periode' => $periode,
                'kriteria' => collect(),
                'rows' => [],
            ];
        }

        $penilaians = Penilaian::with(['keluarga', 'kriteria', 'subKriteria'])
            ->where('periode_id', $periodeId)
            ->get();

        if ($penilaians->isEmpty()) {
            return [
                'periode' => $periode,
                'kriteria' => $kriteriaList,
                'rows' => [],
            ];
        }

        $grouped = $penilaians->groupBy('keluarga_id');
        $rows = [];

        foreach ($grouped as $keluargaId => $items) {
            $keluarga = $items->first()->keluarga;
            if (! $keluarga) {
                continue;
            }

            $values = [];
            $subKriteriaNames = [];

            foreach ($kriteriaList as $kriteria) {
                $penilaianItem = $items->firstWhere('kriteria_id', $kriteria->id);
                $values[$kriteria->kode] = $penilaianItem ? (float) $penilaianItem->nilai : 0.0;
                $subKriteriaNames[$kriteria->kode] = $penilaianItem?->subKriteria?->nama_subkriteria ?? '-';
            }

            $rows[] = [
                'keluarga_id' => $keluarga->id,
                'keluarga' => $keluarga,
                'values' => $values,
                'sub_kriteria' => $subKriteriaNames,
            ];
        }

        return [
            'periode' => $periode,
            'kriteria' => $kriteriaList,
            'rows' => $rows,
        ];
    }

    /**
     * Get Min and Max values for each criteria from decision matrix.
     *
     * @param  array<string, mixed>|list<array<string, mixed>>  $matrix
     * @param  Collection<int, Kriteria>|array<int, Kriteria>  $kriteriaList
     * @return array<string, array{min: float, max: float}>
     */
    public function getMinMaxValues(array $matrix, $kriteriaList): array
    {
        $rows = isset($matrix['rows']) ? $matrix['rows'] : $matrix;
        $minMax = [];

        foreach ($kriteriaList as $kriteria) {
            $kode = $kriteria->kode;
            $columnValues = [];

            foreach ($rows as $row) {
                if (isset($row['values'][$kode])) {
                    $columnValues[] = (float) $row['values'][$kode];
                }
            }

            if (empty($columnValues)) {
                $minMax[$kode] = ['min' => 0.0, 'max' => 0.0];
            } else {
                $minMax[$kode] = [
                    'min' => (float) min($columnValues),
                    'max' => (float) max($columnValues),
                ];
            }
        }

        return $minMax;
    }

    /**
     * Calculate normalized matrix R (rij).
     *
     * Benefit: r_ij = x_ij / Max(x_j)
     * Cost:    r_ij = Min(x_j) / x_ij
     *
     * @param  array<string, mixed>|list<array<string, mixed>>  $matrix
     * @param  Collection<int, Kriteria>|array<int, Kriteria>  $kriteriaList
     * @param  array<string, array{min: float, max: float}>  $minMax
     * @return array<string, mixed>
     */
    public function calculateNormalization(array $matrix, $kriteriaList, array $minMax): array
    {
        $rows = isset($matrix['rows']) ? $matrix['rows'] : $matrix;
        $periode = $matrix['periode'] ?? null;
        $normalizedRows = [];

        foreach ($rows as $row) {
            $rValues = [];
            $formulas = [];

            foreach ($kriteriaList as $kriteria) {
                $kode = $kriteria->kode;
                $x = (float) ($row['values'][$kode] ?? 0.0);
                $min = (float) ($minMax[$kode]['min'] ?? 0.0);
                $max = (float) ($minMax[$kode]['max'] ?? 0.0);

                if ($kriteria->atribut === 'cost') {
                    if ($x > 0) {
                        $r = $min / $x;
                        $formula = sprintf('%.2f / %.2f', $min, $x);
                    } else {
                        $r = 0.0;
                        $formula = '0';
                    }
                } else {
                    if ($max > 0) {
                        $r = $x / $max;
                        $formula = sprintf('%.2f / %.2f', $x, $max);
                    } else {
                        $r = 0.0;
                        $formula = '0';
                    }
                }

                $rValues[$kode] = round($r, 4);
                $formulas[$kode] = $formula;
            }

            $normalizedRows[] = [
                'keluarga_id' => $row['keluarga_id'],
                'keluarga' => $row['keluarga'],
                'raw_values' => $row['values'],
                'values' => $rValues,
                'formulas' => $formulas,
            ];
        }

        return [
            'periode' => $periode,
            'kriteria' => $kriteriaList,
            'min_max' => $minMax,
            'rows' => $normalizedRows,
        ];
    }

    /**
     * Calculate preference values Vi = SUM(W_j * r_ij).
     *
     * @param  array<string, mixed>|list<array<string, mixed>>  $normalizedMatrix
     * @param  Collection<int, Kriteria>|array<int, Kriteria>  $kriteriaList
     * @return array<string, mixed>
     */
    public function calculatePreferences(array $normalizedMatrix, $kriteriaList): array
    {
        $rows = isset($normalizedMatrix['rows']) ? $normalizedMatrix['rows'] : $normalizedMatrix;
        $periode = $normalizedMatrix['periode'] ?? null;
        $preferenceRows = [];

        foreach ($rows as $row) {
            $totalVi = 0.0;
            $details = [];

            foreach ($kriteriaList as $kriteria) {
                $kode = $kriteria->kode;
                $w = (float) $kriteria->bobot;
                $r = (float) ($row['values'][$kode] ?? 0.0);
                $product = $w * $r;

                $details[$kode] = [
                    'bobot' => $w,
                    'r' => $r,
                    'product' => round($product, 4),
                    'formula' => sprintf('%.2f × %.4f', $w, $r),
                ];

                $totalVi += $product;
            }

            $nilaiAkhir = round($totalVi, 4);

            $preferenceRows[] = [
                'keluarga_id' => $row['keluarga_id'],
                'keluarga' => $row['keluarga'],
                'normalized_values' => $row['values'],
                'details' => $details,
                'nilai_akhir' => $nilaiAkhir,
                'status_kelayakan' => $nilaiAkhir >= 0.50 ? 'Layak' : 'Tidak Layak',
            ];
        }

        return [
            'periode' => $periode,
            'kriteria' => $kriteriaList,
            'rows' => $preferenceRows,
        ];
    }


    /**
     * Get ranked results for the given period.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getRankedResults(int $periodeId): array
    {
        $decisionMatrix = $this->getDecisionMatrix($periodeId);
        if (empty($decisionMatrix['rows'])) {
            return [];
        }

        $kriteriaList = $decisionMatrix['kriteria'];
        $minMax = $this->getMinMaxValues($decisionMatrix, $kriteriaList);
        $normalized = $this->calculateNormalization($decisionMatrix, $kriteriaList, $minMax);
        $preferences = $this->calculatePreferences($normalized, $kriteriaList);

        $results = $preferences['rows'];

        usort($results, function ($a, $b) {
            if ($b['nilai_akhir'] == $a['nilai_akhir']) {
                return 0;
            }

            return ($b['nilai_akhir'] > $a['nilai_akhir']) ? 1 : -1;
        });

        $ranked = [];
        $rank = 1;
        foreach ($results as $item) {
            $item['ranking'] = $rank++;
            $ranked[] = $item;
        }

        return $ranked;
    }

    /**
     * Save/Lock the calculation results into hasil_saw table.
     */
    public function saveCalculations(int $periodeId, float $threshold = 0.50): bool
    {
        $rankedResults = $this->getRankedResults($periodeId);
        if (empty($rankedResults)) {
            return false;
        }

        return DB::transaction(function () use ($periodeId, $rankedResults, $threshold) {
            HasilSaw::where('periode_id', $periodeId)->delete();

            foreach ($rankedResults as $item) {
                HasilSaw::create([
                    'periode_id' => $periodeId,
                    'keluarga_id' => $item['keluarga_id'],
                    'nilai_akhir' => $item['nilai_akhir'],
                    'ranking' => $item['ranking'],
                    'status_kelayakan' => $item['nilai_akhir'] >= $threshold ? 'Layak' : 'Tidak Layak',
                ]);
            }

            return true;
        });
    }
}


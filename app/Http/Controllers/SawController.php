<?php

namespace App\Http\Controllers;

use App\Models\HasilSaw;
use App\Models\Kriteria;
use App\Models\Periode;
use App\Services\SawCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SawController extends Controller
{
    public function __construct(
        protected SawCalculationService $sawService
    ) {}

    /**
     * Resolves the selected period or falls back to the active/latest one.
     */
    protected function getPeriode(Request $request): ?Periode
    {
        $id = $request->integer('periode_id');
        if ($id) {
            return Periode::find($id);
        }

        return Periode::where('status', 'aktif')->first() ?? Periode::latest()->first();
    }

    /**
     * Menu Normalisasi: Matriks X dan Matriks R.
     */
    public function normalisasi(Request $request): View
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $periode = $this->getPeriode($request);

        $matrix = [];
        $kriterias = collect();
        $minMax = [];
        $normalized = [];

        if ($periode) {
            $matrix = $this->sawService->getDecisionMatrix($periode->id);
            $kriterias = $matrix['kriteria'] ?? Kriteria::orderBy('kode')->get();
            $minMax = $this->sawService->getMinMaxValues($matrix, $kriterias);
            $normalized = $this->sawService->calculateNormalization($matrix, $kriterias, $minMax);
        }

        return view('spk.normalisasi', compact('periodes', 'periode', 'matrix', 'kriterias', 'minMax', 'normalized'));
    }

    /**
     * Menu Perhitungan SAW: Vektor Bobot dan Matriks Preferensi (W * R).
     */
    public function perhitungan(Request $request): View
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $periode = $this->getPeriode($request);

        $matrix = [];
        $kriterias = collect();
        $minMax = [];
        $normalized = [];
        $preferences = [];

        if ($periode) {
            $matrix = $this->sawService->getDecisionMatrix($periode->id);
            $kriterias = $matrix['kriteria'] ?? Kriteria::orderBy('kode')->get();
            $minMax = $this->sawService->getMinMaxValues($matrix, $kriterias);
            $normalized = $this->sawService->calculateNormalization($matrix, $kriterias, $minMax);
            $preferences = $this->sawService->calculatePreferences($normalized, $kriterias);
        }

        return view('spk.perhitungan', compact('periodes', 'periode', 'kriterias', 'preferences'));
    }

    /**
     * Menu Perankingan: Tabel Hasil Akhir, Ranking, dan Aksi Simpan / Cetak.
     */
    public function perangkingan(Request $request): View
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $periode = $this->getPeriode($request);

        $rankings = [];
        $isLocked = false;

        if ($periode) {
            $rankings = $this->sawService->getRankedResults($periode->id);
            $isLocked = HasilSaw::where('periode_id', $periode->id)->exists();
        }

        return view('spk.perangkingan', compact('periodes', 'periode', 'rankings', 'isLocked'));
    }

    /**
     * Simpan / Kunci Hasil SPK ke database hasil_saw.
     */
    public function simpanHasil(Request $request): RedirectResponse
    {
        $request->validate([
            'periode_id' => ['required', 'exists:periode,id'],
            'threshold' => ['nullable', 'numeric', 'min:0', 'max:1'],
        ]);

        $periodeId = $request->integer('periode_id');
        $threshold = $request->float('threshold', 0.50);

        $saved = $this->sawService->saveCalculations($periodeId, $threshold);

        if (! $saved) {
            return back()->with('error', 'Gagal menyimpan hasil! Pastikan data penilaian pada periode ini sudah terisi.');
        }

        return redirect()->route('spk.perangkingan', ['periode_id' => $periodeId])
            ->with('success', 'Hasil perangkingan SPK SAW berhasil dikunci dan disimpan ke database.');
    }
}

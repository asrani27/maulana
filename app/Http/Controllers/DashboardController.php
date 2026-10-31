<?php

namespace App\Http\Controllers;

use App\Models\HasilSaw;
use App\Models\Keluarga;
use App\Models\Kriteria;
use App\Models\Periode;
use App\Services\SawCalculationService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected SawCalculationService $sawService
    ) {}

    public function index(): View
    {
        $totalKeluarga = Keluarga::count();
        $totalKriteria = Kriteria::count();
        $periodeAktif = Periode::where('status', 'aktif')->first() ?? Periode::latest()->first();

        $topPenerima = collect();
        $totalHasilPerankingan = 0;

        if ($periodeAktif) {
            $savedHasil = HasilSaw::with('keluarga')
                ->where('periode_id', $periodeAktif->id)
                ->orderBy('ranking')
                ->take(5)
                ->get();

            if ($savedHasil->isNotEmpty()) {
                $topPenerima = $savedHasil;
                $totalHasilPerankingan = HasilSaw::where('periode_id', $periodeAktif->id)->count();
            } else {
                // Calculate dynamically on runtime
                $ranked = $this->sawService->getRankedResults($periodeAktif->id);
                $totalHasilPerankingan = count($ranked);
                $topPenerima = collect(array_slice($ranked, 0, 5))->map(function ($item) {
                    return (object) [
                        'ranking' => $item['ranking'],
                        'keluarga' => $item['keluarga'],
                        'nilai_akhir' => $item['nilai_akhir'],
                        'status_kelayakan' => $item['status_kelayakan'],
                    ];
                });
            }
        }

        return view('dashboard.index', compact(
            'totalKeluarga',
            'totalKriteria',
            'periodeAktif',
            'totalHasilPerankingan',
            'topPenerima'
        ));
    }
}

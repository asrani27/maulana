<?php

namespace App\Http\Controllers;

use App\Models\HasilSaw;
use App\Models\Periode;
use App\Services\SawCalculationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function __construct(
        protected SawCalculationService $sawService
    ) {}

    protected function getPeriode(Request $request): ?Periode
    {
        $id = $request->integer('periode_id');
        if ($id) {
            return Periode::find($id);
        }

        $periode = Periode::where('status', 'aktif')->first() ?? Periode::latest()->first();

        if (! $periode) {
            $periode = Periode::firstOrCreate(
                ['nama_periode' => 'Periode I Tahun '.date('Y')],
                ['tahun' => (int) date('Y'), 'status' => 'aktif']
            );
        }

        return $periode;
    }

    public function index(Request $request): View
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $periode = $this->getPeriode($request);

        $rankings = [];
        if ($periode) {
            // Check if saved results exist
            $saved = HasilSaw::with('keluarga')
                ->where('periode_id', $periode->id)
                ->orderBy('ranking')
                ->get();

            if ($saved->isNotEmpty()) {
                $rankings = $saved->map(function ($item) {
                    return [
                        'ranking' => $item->ranking,
                        'keluarga' => $item->keluarga,
                        'nilai_akhir' => $item->nilai_akhir,
                        'status_kelayakan' => $item->status_kelayakan,
                    ];
                })->toArray();
            } else {
                $rankings = $this->sawService->getRankedResults($periode->id);
            }
        }

        return view('laporan.index', compact('periodes', 'periode', 'rankings'));
    }

    public function downloadPdf(Request $request): Response
    {
        $periode = $this->getPeriode($request);

        if (! $periode) {
            abort(404, 'Periode tidak ditemukan.');
        }

        $saved = HasilSaw::with('keluarga')
            ->where('periode_id', $periode->id)
            ->orderBy('ranking')
            ->get();

        if ($saved->isNotEmpty()) {
            $rankings = $saved->map(function ($item) {
                return [
                    'ranking' => $item->ranking,
                    'keluarga' => $item->keluarga,
                    'nilai_akhir' => $item->nilai_akhir,
                    'status_kelayakan' => $item->status_kelayakan,
                ];
            })->toArray();
        } else {
            $rankings = $this->sawService->getRankedResults($periode->id);
        }

        $tanggalCetak = now()->translatedFormat('d F Y');

        $pdf = Pdf::loadView('laporan.pdf', compact('periode', 'rankings', 'tanggalCetak'))
            ->setPaper('a4', 'portrait');

        $filename = 'Laporan_Penerima_PKH_'.str_replace(' ', '_', $periode->nama_periode).'.pdf';

        return $pdf->stream($filename);
    }
}

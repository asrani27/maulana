<?php

namespace App\Http\Controllers;

use App\Models\Keluarga;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Periode;
use App\Models\SubKriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(Request $request): View
    {
        $periodes = Periode::orderBy('tahun', 'desc')->get();
        $selectedPeriodeId = $request->integer('periode_id') ?: optional(Periode::where('status', 'aktif')->first())->id;

        if (! $selectedPeriodeId && $periodes->isNotEmpty()) {
            $selectedPeriodeId = $periodes->first()->id;
        }

        $kriterias = Kriteria::with(['subKriteria' => function ($q) {
            $q->orderBy('nilai', 'desc');
        }])->orderBy('kode')->get();

        $search = $request->get('search');
        $keluargasQuery = Keluarga::query();

        if ($search) {
            $keluargasQuery->where(function ($q) use ($search) {
                $q->where('nama_kepala_keluarga', 'like', "%{$search}%")
                    ->orWhere('no_kk', 'like', "%{$search}%");
            });
        }

        $keluargas = $keluargasQuery->orderBy('nama_kepala_keluarga')->paginate(10)->withQueryString();

        // Get existing assessments for this period
        $penilaians = Penilaian::where('periode_id', $selectedPeriodeId)
            ->get()
            ->groupBy('keluarga_id');

        $penilaianMap = [];
        foreach (Penilaian::where('periode_id', $selectedPeriodeId)->get() as $p) {
            $penilaianMap[$p->keluarga_id][$p->kriteria_id] = [
                'nilai' => $p->nilai,
                'sub_kriteria_id' => $p->sub_kriteria_id,
            ];
        }

        $periodeAktif = $periodes->firstWhere('id', $selectedPeriodeId) ?? $periodes->first();
        $periodeList = $periodes;
        $kriteriaList = $kriterias;
        $keluargaList = $keluargas;

        return view('penilaian.index', compact(
            'periodes',
            'selectedPeriodeId',
            'kriterias',
            'keluargas',
            'penilaians',
            'periodeAktif',
            'periodeList',
            'kriteriaList',
            'keluargaList',
            'penilaianMap'
        ));
    }

    public function edit(Request $request, Keluarga $keluarga): View
    {
        $periodeId = $request->integer('periode_id') ?: optional(Periode::where('status', 'aktif')->first())->id;
        $periode = Periode::findOrFail($periodeId);

        $kriterias = Kriteria::with(['subKriteria' => function ($q) {
            $q->orderBy('nilai', 'desc');
        }])->orderBy('kode')->get();

        $kriteriaList = $kriterias;

        $existing = Penilaian::where('periode_id', $periodeId)
            ->where('keluarga_id', $keluarga->id)
            ->pluck('sub_kriteria_id', 'kriteria_id')
            ->toArray();

        return view('penilaian.edit', compact('keluarga', 'periode', 'kriterias', 'kriteriaList', 'existing'));
    }

    public function update(Request $request, Keluarga $keluarga): RedirectResponse
    {
        $rawValues = $request->input('sub_kriteria_id') ?? $request->input('kriteria') ?? [];
        $request->merge(['kriteria' => $rawValues, 'sub_kriteria_id' => $rawValues]);

        $validated = $request->validate([
            'periode_id' => ['required', 'exists:periode,id'],
            'kriteria' => ['required', 'array'],
            'kriteria.*' => ['required', 'exists:sub_kriteria,id'],
        ]);

        foreach ($validated['kriteria'] as $kriteriaId => $subKriteriaId) {
            $sub = SubKriteria::findOrFail($subKriteriaId);

            Penilaian::updateOrCreate(
                [
                    'periode_id' => $validated['periode_id'],
                    'keluarga_id' => $keluarga->id,
                    'kriteria_id' => $kriteriaId,
                ],
                [
                    'sub_kriteria_id' => $sub->id,
                    'nilai' => $sub->nilai,
                ]
            );
        }

        return redirect()->route('penilaian.index', ['periode_id' => $validated['periode_id']])
            ->with('success', 'Penilaian untuk '.$keluarga->nama_kepala_keluarga.' berhasil disimpan.');
    }

    public function bulkAutoFill(Request $request): RedirectResponse
    {
        $periodeId = $request->integer('periode_id') ?: optional(Periode::where('status', 'aktif')->first())->id;
        if (! $periodeId) {
            return back()->with('error', 'Pilih periode aktif terlebih dahulu.');
        }

        $keluargas = Keluarga::all();
        $kriterias = Kriteria::with('subKriteria')->get();

        if ($kriterias->isEmpty()) {
            return back()->with('error', 'Kriteria belum tersedia.');
        }

        $filledCount = 0;

        foreach ($keluargas as $keluarga) {
            $hasMissing = false;

            foreach ($kriterias as $kriteria) {
                $exists = Penilaian::where('periode_id', $periodeId)
                    ->where('keluarga_id', $keluarga->id)
                    ->where('kriteria_id', $kriteria->id)
                    ->exists();

                if (! $exists) {
                    $hasMissing = true;
                    // Pick a random sub-kriteria
                    $sub = $kriteria->subKriteria->random();
                    if ($sub) {
                        Penilaian::create([
                            'periode_id' => $periodeId,
                            'keluarga_id' => $keluarga->id,
                            'kriteria_id' => $kriteria->id,
                            'sub_kriteria_id' => $sub->id,
                            'nilai' => $sub->nilai,
                        ]);
                    }
                }
            }

            if ($hasMissing) {
                $filledCount++;
            }
        }

        return redirect()->route('penilaian.index', ['periode_id' => $periodeId])
            ->with('success', "Penilaian otomatis berhasil dilengkapi untuk {$filledCount} keluarga.");
    }
}

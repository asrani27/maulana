<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    public function index(): View
    {
        $kriterias = Kriteria::with(['subKriteria' => function ($query) {
            $query->orderBy('nilai', 'desc');
        }])->orderBy('kode')->get();

        $totalBobot = $kriterias->sum('bobot');
        $kriteriaList = $kriterias;

        return view('kriteria.index', compact('kriterias', 'kriteriaList', 'totalBobot'));
    }

    public function updateBobot(Request $request): RedirectResponse
    {
        $request->validate([
            'bobot' => ['required', 'array'],
            'bobot.*' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $total = array_sum($request->bobot);

        if (abs($total - 1.00) > 0.001) {
            return back()->with('error', 'Total bobot kriteria harus tepat sama dengan 1.00 (Saat ini: '.number_format($total, 2).')');
        }

        foreach ($request->bobot as $id => $bobot) {
            Kriteria::where('id', $id)->update(['bobot' => $bobot]);
        }

        return redirect()->route('kriteria.index')->with('success', 'Bobot kriteria berhasil diperbarui.');
    }

    public function storeSubKriteria(Request $request, Kriteria $kriteria): RedirectResponse
    {
        $validated = $request->validate([
            'nama_subkriteria' => ['required', 'string', 'max:100'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $kriteria->subKriteria()->create($validated);

        return redirect()->route('kriteria.index')->with('success', 'Sub-kriteria berhasil ditambahkan.');
    }

    public function updateSubKriteria(Request $request, SubKriteria $subKriteria): RedirectResponse
    {
        $validated = $request->validate([
            'nama_subkriteria' => ['required', 'string', 'max:100'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $subKriteria->update($validated);

        return redirect()->route('kriteria.index')->with('success', 'Sub-kriteria berhasil diperbarui.');
    }

    public function destroySubKriteria(SubKriteria $subKriteria): RedirectResponse
    {
        $subKriteria->delete();

        return redirect()->route('kriteria.index')->with('success', 'Sub-kriteria berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodeController extends Controller
{
    public function index(): View
    {
        $periodes = Periode::withCount(['penilaian', 'hasilSaw'])
            ->orderBy('tahun', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('periode.index', compact('periodes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_periode' => ['required', 'string', 'max:100'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2050'],
            'status' => ['required', 'in:aktif,arsip'],
        ]);

        if ($validated['status'] === 'aktif') {
            Periode::where('status', 'aktif')->update(['status' => 'arsip']);
        }

        Periode::create($validated);

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function activate(Periode $periode): RedirectResponse
    {
        Periode::where('status', 'aktif')->update(['status' => 'arsip']);
        $periode->update(['status' => 'aktif']);

        return redirect()->route('periode.index')->with('success', 'Periode "'.$periode->nama_periode.'" berhasil diaktifkan.');
    }

    public function destroy(Periode $periode): RedirectResponse
    {
        $periode->delete();

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus.');
    }
}

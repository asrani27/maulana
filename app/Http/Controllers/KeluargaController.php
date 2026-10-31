<?php

namespace App\Http\Controllers;

use App\Models\Keluarga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KeluargaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Keluarga::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kepala_keluarga', 'like', "%{$search}%")
                    ->orWhere('no_kk', 'like', "%{$search}%")
                    ->orWhere('nik_kepala', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }

        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        $keluargas = $query->orderBy('nama_kepala_keluarga')->paginate(10)->withQueryString();
        $keluargaList = $keluargas;
        $keluarga = $keluargas;

        $rtList = Keluarga::select('rt')->distinct()->whereNotNull('rt')->orderBy('rt')->pluck('rt');
        $rwList = Keluarga::select('rw')->distinct()->whereNotNull('rw')->orderBy('rw')->pluck('rw');

        return view('keluarga.index', compact('keluargas', 'keluargaList', 'keluarga', 'rtList', 'rwList'));
    }

    public function create(): View
    {
        return view('keluarga.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $request->filled('nik_kepala') && $request->filled('nik_kepala_keluarga')) {
            $request->merge(['nik_kepala' => $request->nik_kepala_keluarga]);
        }

        if (! $request->filled('jenis_kelamin')) {
            $request->merge(['jenis_kelamin' => 'Laki-laki']);
        }

        $validated = $request->validate([
            'no_kk' => ['required', 'string', 'size:16', 'unique:keluarga,no_kk'],
            'nik_kepala' => ['required', 'string', 'size:16'],
            'nama_kepala_keluarga' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'alamat' => ['required', 'string'],
            'rt' => ['required', 'string', 'max:3'],
            'rw' => ['required', 'string', 'max:3'],
            'desa' => ['nullable', 'string', 'max:50'],
            'kecamatan' => ['nullable', 'string', 'max:50'],
            'kabupaten' => ['nullable', 'string', 'max:50'],
            'provinsi' => ['nullable', 'string', 'max:50'],
        ]);

        $validated['desa'] = $validated['desa'] ?? 'Thaibah Raya';
        $validated['kecamatan'] = $validated['kecamatan'] ?? 'Tatah Makmur';
        $validated['kabupaten'] = $validated['kabupaten'] ?? 'Banjar';
        $validated['provinsi'] = $validated['provinsi'] ?? 'Kalimantan Selatan';

        Keluarga::create($validated);

        return redirect()->route('keluarga.index')->with('success', 'Data keluarga berhasil ditambahkan.');
    }

    public function edit(Keluarga $keluarga): View
    {
        return view('keluarga.edit', compact('keluarga'));
    }

    public function update(Request $request, Keluarga $keluarga): RedirectResponse
    {
        if (! $request->filled('nik_kepala') && $request->filled('nik_kepala_keluarga')) {
            $request->merge(['nik_kepala' => $request->nik_kepala_keluarga]);
        }

        if (! $request->filled('jenis_kelamin')) {
            $request->merge(['jenis_kelamin' => 'Laki-laki']);
        }

        $validated = $request->validate([
            'no_kk' => ['required', 'string', 'size:16', Rule::unique('keluarga', 'no_kk')->ignore($keluarga->id)],
            'nik_kepala' => ['required', 'string', 'size:16'],
            'nama_kepala_keluarga' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'alamat' => ['required', 'string'],
            'rt' => ['required', 'string', 'max:3'],
            'rw' => ['required', 'string', 'max:3'],
            'desa' => ['nullable', 'string', 'max:50'],
            'kecamatan' => ['nullable', 'string', 'max:50'],
            'kabupaten' => ['nullable', 'string', 'max:50'],
            'provinsi' => ['nullable', 'string', 'max:50'],
        ]);

        $keluarga->update($validated);

        return redirect()->route('keluarga.index')->with('success', 'Data keluarga berhasil diperbarui.');
    }

    public function destroy(Keluarga $keluarga): RedirectResponse
    {
        $keluarga->delete();

        return redirect()->route('keluarga.index')->with('success', 'Data keluarga berhasil dihapus.');
    }
}

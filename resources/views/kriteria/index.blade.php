@extends('layouts.app')

@section('content')
@php
    $kriteriaList = $kriteriaList ?? $kriterias ?? [];
@endphp
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Kriteria & Sub-Kriteria SAW</h1>
            <p class="text-xs text-slate-500">Parameter pembobotan seleksi penerima PKH Desa Thaibah Raya</p>
        </div>
        <div class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold font-mono">
            Total Bobot: {{ number_format($totalBobot, 2) }} / 1.00
        </div>
    </div>

    <!-- Form Bobot -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
        <h2 class="text-xs font-bold text-slate-700 uppercase mb-3">Atur Bobot Kriteria (W)</h2>
        <form action="{{ route('kriteria.update-bobot') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach($kriteriaList as $k)
                    <div class="p-3 rounded-lg border {{ $k->jenis === 'benefit' ? 'border-emerald-200 bg-emerald-50/20' : 'border-amber-200 bg-amber-50/20' }}">
                        <div class="flex justify-between items-center text-xs font-bold mb-1">
                            <span class="px-1.5 py-0.5 rounded bg-slate-800 text-white text-[10px]">{{ $k->kode }}</span>
                            <span class="uppercase text-[10px] {{ $k->jenis === 'benefit' ? 'text-emerald-700' : 'text-amber-700' }}">{{ $k->jenis }}</span>
                        </div>
                        <p class="text-xs font-bold text-slate-800 truncate mb-2">{{ $k->nama_kriteria }}</p>
                        <input type="number" step="0.01" min="0.01" max="1" 
                               name="bobot[{{ $k->id }}]" value="{{ $k->bobot }}" required
                               class="w-full px-2 py-1 rounded border border-slate-300 text-xs font-mono font-bold">
                    </div>
                @endforeach
            </div>
            <div class="mt-3 flex justify-end">
                <button type="submit" class="px-4 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">
                    Simpan Perubahan Bobot
                </button>
            </div>
        </form>
    </div>

    <!-- Sub-Kriteria List -->
    <div class="space-y-4">
        @foreach($kriteriaList as $k)
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-900">{{ $k->kode }} - {{ $k->nama_kriteria }} (Bobot: {{ $k->bobot }}, {{ ucfirst($k->jenis) }})</span>
                </div>
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="px-3 py-2 w-10 text-center">No</th>
                            <th class="px-3 py-2">Parameter Sub-Kriteria</th>
                            <th class="px-3 py-2 text-center w-24">Nilai Skala</th>
                            <th class="px-3 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($k->subKriteria as $idx => $sub)
                            <tr class="hover:bg-slate-50">
                                <td class="px-3 py-2 text-center text-slate-400">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2 font-bold text-slate-800">{{ $sub->nama_sub_kriteria }}</td>
                                <td class="px-3 py-2 text-center font-mono font-bold text-emerald-700 bg-emerald-50/40">
                                    {{ number_format($sub->nilai, 2) }}
                                </td>
                                <td class="px-3 py-2 text-slate-500">{{ $sub->keterangan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
@endsection

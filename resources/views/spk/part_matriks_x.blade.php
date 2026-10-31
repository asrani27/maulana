<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-3 bg-slate-50 border-b border-slate-200 font-bold text-xs uppercase text-slate-800">
        1. Matriks Keputusan (X) — Skala Nilai Kriteria
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs font-mono">
            <thead class="bg-slate-100 text-slate-600 font-sans uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="px-3 py-2 text-center w-10">No</th>
                    <th class="px-3 py-2 font-sans">Alternatif (Kepala Keluarga)</th>
                    @foreach($kriterias as $crit)
                        <th class="px-3 py-2 text-center">{{ $crit->kode }} ({{ ucfirst($crit->atribut ?? $crit->jenis) }})</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse(($matrix['rows'] ?? []) as $idx => $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2 text-center font-sans text-slate-400">{{ $idx + 1 }}</td>
                        <td class="px-3 py-2 font-sans font-bold text-slate-900">{{ $row['keluarga']->nama_kepala_keluarga ?? '-' }}</td>
                        @foreach($kriterias as $crit)
                            <td class="px-3 py-2 text-center text-slate-700 font-bold">
                                {{ isset($row['values'][$crit->kode]) ? number_format($row['values'][$crit->kode], 2) : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ 2 + count($kriterias ?? []) }}" class="px-4 py-4 text-center font-sans text-slate-400">Belum ada data penilaian pada periode ini.</td></tr>
                @endforelse
            </tbody>
            @if(!empty($matrix['rows']))
                <tfoot class="bg-slate-50 border-t-2 border-slate-200 font-bold text-slate-700">
                    <tr>
                        <td colspan="2" class="px-3 py-1.5 text-right font-sans uppercase">Nilai Maksimum (Max):</td>
                        @foreach($kriterias as $crit)
                            <td class="px-3 py-1.5 text-center text-emerald-700">{{ number_format($minMax[$crit->kode]['max'] ?? 0, 2) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="2" class="px-3 py-1.5 text-right font-sans uppercase">Nilai Minimum (Min):</td>
                        @foreach($kriterias as $crit)
                            <td class="px-3 py-1.5 text-center text-amber-700">{{ number_format($minMax[$crit->kode]['min'] ?? 0, 2) }}</td>
                        @endforeach
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>

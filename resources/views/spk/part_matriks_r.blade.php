<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
        <span class="font-bold text-xs uppercase text-slate-800">2. Matriks Ternormalisasi (R)</span>
        <a href="{{ route('spk.perhitungan', ['periode_id' => $periode->id ?? null]) }}" class="px-3 py-1 rounded bg-slate-800 text-white font-bold text-xs hover:bg-slate-900">
            Perhitungan Preferensi (W × R) →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs font-mono">
            <thead class="bg-slate-100 text-slate-600 font-sans uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="px-3 py-2 text-center w-10">No</th>
                    <th class="px-3 py-2 font-sans">Alternatif (Kepala Keluarga)</th>
                    @foreach($kriterias as $crit)
                        <th class="px-3 py-2 text-center">r_{{ $crit->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse(($normalized['rows'] ?? []) as $idx => $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2 text-center font-sans text-slate-400">{{ $idx + 1 }}</td>
                        <td class="px-3 py-2 font-sans font-bold text-slate-900">{{ $row['keluarga']->nama_kepala_keluarga ?? '-' }}</td>
                        @foreach($kriterias as $crit)
                            <td class="px-3 py-2 text-center text-emerald-700 font-bold">
                                {{ isset($row['values'][$crit->kode]) ? number_format($row['values'][$crit->kode], 4) : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ 2 + count($kriterias ?? []) }}" class="px-4 py-4 text-center font-sans text-slate-400">Belum ada data ternormalisasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

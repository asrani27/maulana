<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
        <span class="font-bold text-xs uppercase text-slate-800">Matriks Hasil Kali (W × R) & Total Preferensi (V_i)</span>
        <a href="{{ route('spk.perangkingan', ['periode_id' => $periode->id ?? null]) }}" class="px-3 py-1 rounded bg-slate-800 text-white font-bold text-xs hover:bg-slate-900">
            Perankingan & Keputusan →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs font-mono">
            <thead class="bg-slate-100 text-slate-600 font-sans uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="px-3 py-2 text-center w-10">No</th>
                    <th class="px-3 py-2 font-sans">Alternatif (Kepala Keluarga)</th>
                    @foreach(($kriterias ?? $kriteria ?? []) as $crit)
                        <th class="px-3 py-2 text-center">
                            w_{{ substr($crit->kode, 1) }} × r_{{ $crit->kode }}
                        </th>
                    @endforeach
                    <th class="px-3 py-2 text-center font-bold text-slate-900 bg-emerald-50/80">Total V_i</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse(($preferences['rows'] ?? []) as $idx => $row)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2 text-center font-sans text-slate-400">{{ $idx + 1 }}</td>
                        <td class="px-3 py-2 font-sans font-bold text-slate-900">{{ $row['keluarga']->nama_kepala_keluarga ?? '-' }}</td>
                        @foreach(($kriterias ?? $kriteria ?? []) as $crit)
                            <td class="px-3 py-2 text-center text-slate-700">
                                {{ isset($row['details'][$crit->kode]['product']) ? number_format($row['details'][$crit->kode]['product'], 4) : '-' }}
                            </td>
                        @endforeach
                        <td class="px-3 py-2 text-center font-bold text-emerald-700 bg-emerald-50/50">
                            {{ number_format($row['nilai_akhir'] ?? 0, 4) }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ 3 + count($kriterias ?? $kriteria ?? []) }}" class="px-4 py-4 text-center font-sans text-slate-400">Belum ada data perhitungan pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

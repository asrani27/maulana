<div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-center w-14">Rank</th>
                    <th class="px-4 py-3">Nama Kepala Keluarga</th>
                    <th class="px-4 py-3 font-mono">No. KK</th>
                    <th class="px-4 py-3">Wilayah</th>
                    <th class="px-4 py-3 text-center">Tanggungan</th>
                    <th class="px-4 py-3 text-center font-mono">Nilai Akhir (V_i)</th>
                    <th class="px-4 py-3 text-center">Status Kelayakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse(($rankings ?? $ranking ?? []) as $r)
                    <tr class="hover:bg-slate-50 {{ $r['ranking'] <= 3 ? 'bg-amber-50/20' : '' }}">
                        <td class="px-4 py-3 text-center font-bold">
                            @if($r['ranking'] == 1)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-400 text-slate-950 font-black text-xs shadow-xs">1</span>
                            @elseif($r['ranking'] == 2)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-300 text-slate-900 font-black text-xs shadow-xs">2</span>
                            @elseif($r['ranking'] == 3)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-200 text-amber-900 font-black text-xs shadow-xs">3</span>
                            @else
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">{{ $r['ranking'] }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-bold text-slate-900">
                            {{ $r['keluarga']->nama_kepala_keluarga }}
                            <span class="block text-[11px] font-normal text-slate-400 truncate max-w-xs">{{ $r['keluarga']->alamat }}</span>
                        </td>
                        <td class="px-4 py-3 font-mono text-slate-600">{{ $r['keluarga']->no_kk }}</td>
                        <td class="px-4 py-3 font-medium text-slate-700">RT {{ $r['keluarga']->rt }} / RW {{ $r['keluarga']->rw }}</td>
                        <td class="px-4 py-3 text-center font-bold text-slate-700">{{ $r['keluarga']->jumlah_tanggungan }} Orang</td>
                        <td class="px-4 py-3 text-center font-mono font-bold text-emerald-700 bg-emerald-50/30 text-sm">
                            {{ number_format($r['nilai_akhir'], 4) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($r['status_kelayakan'] === 'Layak')
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    ✓ Layak PKH
                                </span>
                            @else
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                    ✕ Tidak Layak
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-400">Belum ada alternatif yang dinilai pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="flex items-center justify-between">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Matriks Keputusan (X) & Normalisasi (R)</h1>
        <p class="text-xs text-slate-500">Tahap 1 & 2 Simple Additive Weighting (SAW)</p>
    </div>
    <form method="GET" action="{{ route('spk.normalisasi') }}" class="flex items-center gap-2">
        <span class="text-xs font-bold text-slate-700">Periode:</span>
        <select name="periode_id" onchange="this.form.submit()" class="px-2.5 py-1 rounded border border-slate-300 text-xs font-semibold">
            @foreach(($periodes ?? $periodeList ?? []) as $p)
                <option value="{{ $p->id }}" {{ ($periode && $periode->id == $p->id) ? 'selected' : '' }}>
                    {{ $p->nama_periode }} {{ ($p->status === 'aktif' || $p->is_aktif) ? '(Aktif)' : '' }}
                </option>
            @endforeach
        </select>
    </form>
</div>

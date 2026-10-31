@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Input Penilaian Calon Penerima</h1>
            <p class="text-xs text-slate-500">Keluarga: <strong>{{ $keluarga->nama_kepala_keluarga }}</strong> (No KK: {{ $keluarga->no_kk }})</p>
        </div>
        <a href="{{ route('penilaian.index', ['periode_id' => $periode->id]) }}" class="text-xs font-semibold text-slate-600 hover:underline">
            ← Kembali
        </a>
    </div>

    <!-- Info Profil Keluarga -->
    <div class="bg-emerald-50/60 border border-emerald-200 rounded-xl p-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
        <div>
            <span class="text-slate-500 block">NIK:</span>
            <span class="font-bold text-slate-800">{{ $keluarga->nik_kepala_keluarga }}</span>
        </div>
        <div>
            <span class="text-slate-500 block">Wilayah:</span>
            <span class="font-bold text-slate-800">RT {{ $keluarga->rt }} / RW {{ $keluarga->rw }}</span>
        </div>
        <div>
            <span class="text-slate-500 block">Pekerjaan:</span>
            <span class="font-bold text-slate-800">{{ $keluarga->pekerjaan ?? '-' }}</span>
        </div>
        <div>
            <span class="text-slate-500 block">Penghasilan:</span>
            <span class="font-bold text-slate-800">
                @if(is_numeric($keluarga->penghasilan_bulanan))
                    Rp {{ number_format((float) $keluarga->penghasilan_bulanan, 0, ',', '.') }}
                @elseif(!empty($keluarga->penghasilan_bulanan) && $keluarga->penghasilan_bulanan !== '0')
                    {{ $keluarga->penghasilan_bulanan }}
                @else
                    -
                @endif
            </span>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs">
        <form action="{{ route('penilaian.update', $keluarga) }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <input type="hidden" name="periode_id" value="{{ $periode->id }}">

            @foreach($kriteriaList as $k)
                @php
                    $selectedSubId = old('sub_kriteria_id.' . $k->id, $existing[$k->id] ?? null);
                @endphp
                <div class="p-3.5 rounded-lg border border-slate-200 bg-slate-50/50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-900 text-sm">{{ $k->kode }} - {{ $k->nama_kriteria }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $k->jenis === 'benefit' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $k->jenis }} (Bobot: {{ $k->bobot }})
                        </span>
                    </div>

                    <label class="block text-slate-600 mb-1 font-semibold">Pilih Parameter Sub-Kriteria *</label>
                    <select name="sub_kriteria_id[{{ $k->id }}]" required
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 font-medium focus:ring-1 focus:ring-emerald-500">
                        <option value="">-- Pilih Nilai Sub Kriteria --</option>
                        @foreach($k->subKriteria as $sub)
                            <option value="{{ $sub->id }}" {{ $selectedSubId == $sub->id ? 'selected' : '' }}>
                                {{ $sub->nama_sub_kriteria }} (Skala: {{ is_numeric($sub->nilai) ? number_format((float) $sub->nilai, 2) : $sub->nilai }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach

            <div class="pt-2 flex justify-end gap-2">
                <a href="{{ route('penilaian.index', ['periode_id' => $periode->id]) }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 font-semibold hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-xs">
                    Simpan Nilai Penilaian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

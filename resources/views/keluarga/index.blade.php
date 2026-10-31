@extends('layouts.app')

@section('content')
@php
    $keluargaList = $keluargaList ?? $keluarga ?? $keluargas ?? collect();
@endphp
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Data Kepala Keluarga - Data Calon Penerima PKH</h1>
            <p class="text-xs text-slate-500">Database kependudukan calon penerima PKH Desa Thaibah Raya</p>
        </div>
        <a href="{{ route('keluarga.create') }}" class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs">
            + Tambah KK
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl border border-slate-200 p-3 shadow-xs">
        <form method="GET" action="{{ route('keluarga.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari No KK, NIK, atau Nama..." 
                   class="sm:col-span-2 px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:ring-1 focus:ring-emerald-500">
            <select name="rt" class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs">
                <option value="">Semua RT</option>
                @foreach($rtList as $r)
                    <option value="{{ $r }}" {{ request('rt') == $r ? 'selected' : '' }}>RT {{ $r }}</option>
                @endforeach
            </select>
            <div class="flex gap-1.5">
                <select name="rw" class="flex-1 px-3 py-1.5 rounded-lg border border-slate-300 text-xs">
                    <option value="">Semua RW</option>
                    @foreach($rwList as $w)
                        <option value="{{ $w }}" {{ request('rw') == $w ? 'selected' : '' }}>RW {{ $w }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-bold hover:bg-slate-900">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-2.5 text-center w-10">No</th>
                        <th class="px-3 py-2.5">Nama Kepala Keluarga</th>
                        <th class="px-3 py-2.5">No. KK</th>
                        <th class="px-3 py-2.5">NIK</th>
                        <th class="px-3 py-2.5">RT/RW</th>
                        <th class="px-3 py-2.5 text-center">Tanggungan</th>
                        <th class="px-3 py-2.5">Pekerjaan</th>
                        <th class="px-3 py-2.5 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($keluargaList as $index => $k)
                        <tr class="hover:bg-slate-50">
                            <td class="px-3 py-2.5 text-center text-slate-500">{{ $keluargaList->firstItem() + $index }}</td>
                            <td class="px-3 py-2.5 font-bold text-slate-900">
                                {{ $k->nama_kepala_keluarga }}
                                <span class="block text-[10px] font-normal text-slate-400 truncate max-w-xs">{{ $k->alamat }}</span>
                            </td>
                            <td class="px-3 py-2.5 font-mono text-slate-700">{{ $k->no_kk }}</td>
                            <td class="px-3 py-2.5 font-mono text-slate-600">{{ $k->nik_kepala_keluarga }}</td>
                            <td class="px-3 py-2.5 font-semibold text-slate-700">RT {{ $k->rt }} / RW {{ $k->rw }}</td>
                            <td class="px-3 py-2.5 text-center font-bold text-slate-800">{{ $k->jumlah_tanggungan }} Org</td>
                            <td class="px-3 py-2.5 text-slate-600">{{ $k->pekerjaan ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('keluarga.edit', $k) }}" class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[10px]">
                                        Edit
                                    </a>
                                    <form action="{{ route('keluarga.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus keluarga ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-[10px]">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-slate-400">Tidak ada data keluarga.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($keluargaList->hasPages())
            <div class="p-3 border-t border-slate-100">
                {{ $keluargaList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
